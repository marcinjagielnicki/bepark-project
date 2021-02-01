<?php

namespace Tests\Feature;

use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_users_can_see_dashboard()
    {
        $user = User::factory()->create();
        $this->be($user);
        $response = $this->get('/dashboard');

        $response->assertStatus(200);
    }

    public function test_users_can_add_game()
    {
        $user = User::factory()->create();

        $this->be($user);
        $this->assertAuthenticated();

        $response = $this->post('/add-game', [
            'name' => 'Postal 2',
        ]);

        $response->assertRedirect(RouteServiceProvider::HOME);

        $this->assertDatabaseHas('games', ['name' => 'Postal 2']);
        // Test related game with similar name
        $this->assertDatabaseHas('games', ['name' => 'Postal']);
        $this->assertDatabaseHas('user_games', ['user_id' => $user->id]);

    }

    public function test_users_can_add_double_same_game()
    {
        $user = User::factory()->create();

        $this->be($user);
        $this->assertAuthenticated();

        $response = $this->post('/add-game', [
            'name' => 'Postal 2',
        ]);

        $response->assertRedirect(RouteServiceProvider::HOME);

        $this->assertDatabaseHas('games', ['name' => 'Postal 2']);
        // Test related game with similar name
        $this->assertDatabaseHas('games', ['name' => 'Postal']);
        $this->assertDatabaseHas('user_games', ['user_id' => $user->id]);

        $response = $this->post('/add-game', [
            'name' => 'Postal 2',
        ]);

        // Should redirect back to /
        $response->assertRedirect('/')->assertSessionHas('errors');

    }

}
