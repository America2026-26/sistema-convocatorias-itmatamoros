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
        $this->get('/')
            ->assertRedirect(route('login'));

        foreach (['/login', '/registro', '/convocatorias'] as $path) {
            $response = $this->get($path);

            $response->assertOk()
                ->assertDontSee('data-navbar-toggle', false)
                ->assertDontSee('data-navbar-menu', false)
                ->assertDontSee('data-public-convocatorias-link', false)
                ->assertSee('aria-label="Enlaces del sitio"', false);
        }
    }

    public function test_registration_page_offers_a_back_arrow_to_login(): void
    {
        $this->get(route('register'))
            ->assertOk()
            ->assertSee('href="'.route('login').'"', false)
            ->assertSee('aria-label="Volver al inicio de sesión"', false)
            ->assertDontSee('Volver al catálogo de convocatorias');
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
