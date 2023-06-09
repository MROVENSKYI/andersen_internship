<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\ClientRepository;
use Tests\TestCase;


class LoginTest extends TestCase
{
    use DatabaseMigrations;
    use RefreshDatabase;
    /** @test */
    public function it_should_login_user()
    {
        $clientRepository = new ClientRepository();
        $clientRepository->createPersonalAccessClient(
            null,
            'Personal Access Client',
            'http://example.com/callback'
        );
        $password = fake()->password;
        $user = User::factory()->create(['password' => $password]);
        $response = $this->json('POST', route('auth.login'), ['email' => $user->email, 'password' => $password])->assertStatus(200);
        $response->assertJsonStructure(['token']);
    }

    /** @test */
    public function it_should_throw_validation_exception()
    {
        $response = $this->json('POST', route('password.link'))->assertStatus(422);
        $response->assertJsonStructure(['message', 'errors']);
    }
}