<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guests_can_register_as_authors()
    {
        $response = $this->post('/register', [
            'name' => 'New Author',
            'email' => 'author@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect(route('admin.posts.index'));
        
        $this->assertDatabaseHas('users', [
            'email' => 'author@example.com',
            'role' => User::ROLE_EDITOR,
        ]);

        $this->assertAuthenticated();
    }

    /** @test */
    public function registration_requires_valid_data()
    {
        $response = $this->post('/register', [
            'name' => '',
            'email' => 'invalid-email',
            'password' => '123',
        ]);

        $response->assertSessionHasErrors(['name', 'email', 'password']);
        $this->assertGuest();
    }
}
