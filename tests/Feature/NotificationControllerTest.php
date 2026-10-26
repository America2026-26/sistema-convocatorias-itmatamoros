<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Tests\TestCase;

class NotificationControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_notification_inbox_is_newest_first_and_filters_by_date_range(): void
    {
        $this->travelTo('2026-10-26 12:00:00');
        $user = User::factory()->create();
        $this->createNotification($user, 'Notificación anterior', '2026-10-20 09:00:00');
        $this->createNotification($user, 'Notificación reciente', '2026-10-25 09:00:00');
        $this->createNotification($user, 'Notificación de hoy', '2026-10-26 09:00:00');

        $response = $this->actingAs($user)->get(route('notifications.index', [
            'fecha_desde' => '2026-10-25',
            'fecha_hasta' => '2026-10-26',
        ]));

        $response->assertOk()
            ->assertSeeInOrder(['Notificación de hoy', 'Notificación reciente'])
            ->assertSee('value="2026-10-25"', false)
            ->assertSee('value="2026-10-26"', false)
            ->assertViewHas('notifications', function ($notifications): bool {
                return $notifications->getCollection()
                    ->pluck('data.titulo')
                    ->all() === ['Notificación de hoy', 'Notificación reciente'];
            });
    }

    public function test_notification_details_mark_the_owned_notification_as_read(): void
    {
        $user = User::factory()->create();
        $notification = $this->createNotification($user, 'Nueva postulación', '2026-10-25 09:00:00', [
            'mensaje' => 'María se postuló a Convocatoria Docente.',
            'url' => route('dashboard'),
        ]);

        $this->actingAs($user)
            ->get(route('notifications.show', $notification->id))
            ->assertOk()
            ->assertSee('Nueva postulación')
            ->assertSee('María se postuló a Convocatoria Docente.')
            ->assertSee('Ver información relacionada');

        $this->assertDatabaseMissing('notifications', [
            'id' => $notification->id,
            'read_at' => null,
        ]);
    }

    public function test_user_cannot_view_another_users_notification_details(): void
    {
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();
        $notification = $this->createNotification($owner, 'Notificación privada', '2026-10-25 09:00:00');

        $this->actingAs($otherUser)
            ->get(route('notifications.show', $notification->id))
            ->assertNotFound();
    }

    public function test_notification_preview_is_visible_from_the_authenticated_navbar(): void
    {
        $user = User::factory()->create();
        $this->createNotification($user, 'Aviso de prueba', '2026-10-25 09:00:00');
        $notificationQueries = [];

        DB::listen(function (QueryExecuted $query) use (&$notificationQueries): void {
            $sql = strtolower($query->sql);

            if (str_contains($sql, 'notifications') && str_contains($sql, 'order by')) {
                $notificationQueries[] = $query->sql;
            }
        });

        $this->actingAs($user)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSee('data-notifications-toggle', false)
            ->assertSee('Aviso de prueba')
            ->assertSee(route('notifications.index'));

        $this->assertCount(1, $notificationQueries);

        $orderBy = strtolower(substr(
            $notificationQueries[0],
            stripos($notificationQueries[0], 'order by') + strlen('order by'),
        ));
        $orderBy = str_replace(['[', ']', '`', '"'], '', $orderBy);

        $this->assertStringStartsWith(' created_at desc, id desc', $orderBy);
    }

    public function test_notification_inbox_and_details_require_authentication(): void
    {
        $this->get(route('notifications.index'))->assertRedirect(route('login'));
        $this->get(route('notifications.show', (string) Str::uuid()))
            ->assertRedirect(route('login'));
    }

    public function test_notification_date_filter_rejects_an_end_date_before_the_start_date(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->from(route('notifications.index'))
            ->get(route('notifications.index', [
                'fecha_desde' => '2026-10-26',
                'fecha_hasta' => '2026-10-25',
            ]))
            ->assertRedirect(route('notifications.index'))
            ->assertSessionHasErrors('fecha_hasta');
    }

    /**
     * @param  array<string, mixed>  $data
     */
    private function createNotification(
        User $user,
        string $title,
        string $createdAt,
        array $data = [],
    ): DatabaseNotification {
        return $user->notifications()->create([
            'id' => (string) Str::uuid(),
            'type' => 'App\\Notifications\\TestNotification',
            'data' => [
                'titulo' => $title,
                'mensaje' => 'Descripción de '.$title,
                ...$data,
            ],
            'created_at' => $createdAt,
            'updated_at' => $createdAt,
        ]);
    }
}
