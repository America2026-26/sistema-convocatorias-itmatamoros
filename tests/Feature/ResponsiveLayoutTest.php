<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ResponsiveLayoutTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_views_use_the_shared_responsive_layout(): void
    {
        foreach (['/', '/login', '/registro'] as $path) {
            $response = $this->get($path);

            $response->assertOk()
                ->assertSee('data-navbar-toggle', false)
                ->assertSee('id="mobile-navigation"', false)
                ->assertSee('data-public-convocatorias-link', false)
                ->assertSee('aria-label="Enlaces del sitio"', false);
        }
    }

    public function test_dashboard_uses_the_shared_responsive_layout(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertOk()
            ->assertSee('data-navbar-toggle', false)
            ->assertSee('id="mobile-navigation"', false)
            ->assertSee(route('dashboard'))
            ->assertDontSee('data-public-convocatorias-link', false)
            ->assertSee('data-notifications-toggle', false)
            ->assertSee('aria-label="Enlaces del sitio"', false);
    }

    public function test_applicant_sees_convocatorias_link_in_desktop_and_mobile_navigation(): void
    {
        $this->actingAs(User::factory()->create())
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSee(route('home').'#convocatorias')
            ->assertSee('data-applicant-convocatorias-link', false);
    }

    public function test_reviewer_sees_panel_before_role_specific_navigation(): void
    {
        $response = $this->actingAs(User::factory()->create([
            'role' => User::ROLE_REVIEWER,
        ]))->get('/dashboard');

        $response->assertOk()
            ->assertSeeInOrder([
                'href="'.route('dashboard').'"',
                'href="'.route('dictaminador.convocatorias.index').'"',
            ], false)
            ->assertDontSee('data-public-convocatorias-link', false);
    }
}
