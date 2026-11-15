<?php

namespace Tests\Feature;

use App\Models\CalendarioEvento;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CalendarioControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_users_see_today_and_future_events_but_not_expired_events(): void
    {
        $this->travelTo('2026-11-14 12:00:00');

        $user = User::factory()->create(['role' => User::ROLE_USER]);
        CalendarioEvento::factory()->create([
            'titulo' => 'Evento vencido',
            'fecha' => '2026-11-13',
        ]);
        CalendarioEvento::factory()->create([
            'titulo' => 'Evento de hoy',
            'fecha' => '2026-11-14',
        ]);
        CalendarioEvento::factory()->create([
            'titulo' => 'Evento futuro',
            'fecha' => '2026-11-15',
        ]);

        $response = $this->actingAs($user)
            ->get(route('calendario.institucional', ['mes' => '2026-11']))
            ->assertOk()
            ->assertSeeText('Evento de hoy')
            ->assertSeeText('Evento futuro')
            ->assertSee('data-event-date-state="today"', false)
            ->assertSee('data-event-date-state="upcoming"', false)
            ->assertSee('border-amber-200 bg-amber-50/60', false)
            ->assertSee('border-gray-200 bg-gray-50', false);

        $this->assertSame(
            2,
            preg_match_all('/<article data-event-date-state="(?:today|upcoming)"/', $response->getContent()),
            'Only today and future events should be present in the visible event list.',
        );
        $response->assertSee('<dialog id="calendar-event-dialog"', false)
            ->assertSee('data-calendar-date="2026-11-14"', false)
            ->assertSee('<template data-calendar-events-for="2026-11-13">', false);
    }

    public function test_calendar_displays_the_requested_month_and_navigation_links(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('calendario.institucional', ['mes' => '2027-02']))
            ->assertOk()
            ->assertSee('data-calendar-month="2027-02"', false)
            ->assertSee(route('calendario.institucional', ['mes' => '2027-01']), false)
            ->assertSee(route('calendario.institucional', ['mes' => '2027-03']), false);
    }

    public function test_ajax_month_navigation_returns_calendar_content_without_a_full_page(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->getJson(route('calendario.institucional', ['mes' => '2027-03']))
            ->assertOk()
            ->assertJsonStructure(['html']);

        $html = $response->json('html');
        $this->assertStringContainsString('data-calendar-month="2027-03"', $html);
        $this->assertStringNotContainsString('<html', $html);
    }

    public function test_guest_is_redirected_to_login_from_the_calendar(): void
    {
        $this->get(route('calendario.institucional'))
            ->assertRedirect(route('login'));
    }

    public function test_calendar_escapes_event_titles(): void
    {
        $user = User::factory()->create();
        CalendarioEvento::factory()->create([
            'titulo' => '<script>alert("x")</script>',
            'fecha' => today()->addDay()->toDateString(),
        ]);

        $this->actingAs($user)
            ->get(route('calendario.institucional'))
            ->assertOk()
            ->assertSee('&lt;script&gt;', false)
            ->assertDontSee('<script>alert("x")</script>', false);
    }
}
