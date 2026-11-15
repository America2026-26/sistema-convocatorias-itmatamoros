<?php

namespace Tests\Feature;

use App\Models\CalendarioEvento;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CalendarioEventoControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_and_reviewer_can_create_update_and_delete_calendar_events(): void
    {
        $this->travelTo('2026-11-14 12:00:00');

        $calendarUrl = route('calendario.institucional', ['mes' => '2026-11']);

        foreach ([User::ROLE_ADMIN, User::ROLE_REVIEWER] as $index => $role) {
            $user = User::factory()->create(['role' => $role]);
            $title = 'Evento '.$index + 1;

            $this->actingAs($user)
                ->from($calendarUrl)
                ->post(route('calendario.eventos.store'), [
                    'titulo' => $title,
                    'fecha' => '2026-11-20',
                    'descripcion' => 'Descripción inicial',
                    'mes' => '2026-11',
                ])
                ->assertRedirect($calendarUrl);

            $event = CalendarioEvento::query()->where('titulo', $title)->firstOrFail();
            $this->assertDatabaseHas('calendario_eventos', [
                'id' => $event->id,
                'descripcion' => 'Descripción inicial',
            ]);
            $this->assertSame('2026-11-20', $event->fecha->toDateString());

            $this->from($calendarUrl)
                ->patch(route('calendario.eventos.update', $event), [
                    'titulo' => $title.' actualizado',
                    'fecha' => '2026-11-21',
                    'descripcion' => 'Descripción actualizada',
                    'mes' => '2026-11',
                ])
                ->assertRedirect($calendarUrl);

            $this->assertDatabaseHas('calendario_eventos', [
                'id' => $event->id,
                'titulo' => $title.' actualizado',
                'descripcion' => 'Descripción actualizada',
            ]);
            $this->assertSame('2026-11-21', $event->refresh()->fecha->toDateString());

            $this->from($calendarUrl)
                ->delete(route('calendario.eventos.destroy', $event), ['mes' => '2026-11'])
                ->assertRedirect($calendarUrl);

            $this->assertDatabaseMissing('calendario_eventos', ['id' => $event->id]);
        }
    }

    public function test_event_mutations_support_json_requests_without_redirects(): void
    {
        $this->travelTo('2026-11-14 12:00:00');
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);

        $this->actingAs($admin)
            ->postJson(route('calendario.eventos.store'), [
                'titulo' => 'Evento asíncrono',
                'fecha' => '2026-11-20',
                'descripcion' => 'Creado sin recargar la página',
                'mes' => '2026-11',
            ])
            ->assertOk()
            ->assertJsonPath('message', 'El evento se creó correctamente.');

        $event = CalendarioEvento::query()->where('titulo', 'Evento asíncrono')->firstOrFail();
        $this->assertDatabaseHas('calendario_eventos', [
            'id' => $event->id,
            'descripcion' => 'Creado sin recargar la página',
        ]);

        $calendarResponse = $this->getJson(route('calendario.institucional', ['mes' => '2026-11']))
            ->assertOk()
            ->assertJsonStructure(['html']);
        $this->assertStringContainsString('Evento asíncrono', $calendarResponse->json('html'));

        $this->patchJson(route('calendario.eventos.update', $event), [
            'titulo' => 'Evento actualizado',
            'fecha' => '2026-11-21',
            'mes' => '2026-11',
        ])->assertOk()
            ->assertJsonPath('message', 'El evento se actualizó correctamente.');

        $this->deleteJson(route('calendario.eventos.destroy', $event), [
            'mes' => '2026-11',
        ])->assertOk()
            ->assertJsonPath('message', 'El evento se eliminó correctamente.');

        $this->assertDatabaseMissing('calendario_eventos', ['id' => $event->id]);
    }

    public function test_json_event_validation_returns_errors_without_saving(): void
    {
        $this->travelTo('2026-11-14 12:00:00');
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);

        $this->actingAs($admin)
            ->postJson(route('calendario.eventos.store'), [
                'titulo' => 'Evento anterior',
                'fecha' => '2026-11-13',
                'mes' => '2026-11',
            ])
            ->assertJsonValidationErrors('fecha');

        $this->assertDatabaseCount('calendario_eventos', 0);
    }

    public function test_applicant_cannot_create_update_or_delete_calendar_events(): void
    {
        $calendarUrl = route('calendario.institucional');
        $user = User::factory()->create(['role' => User::ROLE_USER]);
        $event = CalendarioEvento::factory()->create([
            'titulo' => 'Evento protegido',
            'fecha' => today()->addDay()->toDateString(),
        ]);

        $this->actingAs($user)
            ->from($calendarUrl)
            ->post(route('calendario.eventos.store'), [
                'titulo' => 'Evento no autorizado',
                'fecha' => today()->addDays(2)->toDateString(),
            ])
            ->assertForbidden();

        $this->patch(route('calendario.eventos.update', $event), [
            'titulo' => 'Evento alterado',
            'fecha' => today()->addDays(2)->toDateString(),
        ])->assertForbidden();

        $this->delete(route('calendario.eventos.destroy', $event))
            ->assertForbidden();

        $this->assertDatabaseCount('calendario_eventos', 1);
        $this->assertDatabaseHas('calendario_eventos', [
            'id' => $event->id,
            'titulo' => 'Evento protegido',
        ]);
    }

    public function test_guest_cannot_create_calendar_events(): void
    {
        $this->post(route('calendario.eventos.store'), [
            'titulo' => 'Evento no autorizado',
            'fecha' => today()->addDay()->toDateString(),
        ])->assertRedirect(route('login'));
    }

    public function test_event_with_a_past_date_is_rejected(): void
    {
        $this->travelTo('2026-11-14 12:00:00');
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);

        $this->actingAs($admin)
            ->from(route('calendario.institucional'))
            ->post(route('calendario.eventos.store'), [
                'titulo' => 'Evento anterior',
                'fecha' => '2026-11-13',
            ])
            ->assertRedirect(route('calendario.institucional'))
            ->assertSessionHasErrors([
                'fecha' => 'La fecha del evento no puede ser anterior a hoy.',
            ]);

        $this->assertDatabaseCount('calendario_eventos', 0);
    }
}
