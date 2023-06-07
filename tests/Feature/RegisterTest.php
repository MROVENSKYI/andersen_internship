<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\ClientRepository;
use Tests\TestCase;


class RegisterTest extends TestCase
{
    use DatabaseMigrations;
    use RefreshDatabase;
    /** @test */
    public function it_should_register_user()
    {
        $clientRepository = new ClientRepository();
        $clientRepository->createPersonalAccessClient(
            null,
            'Personal Access Client',
            'http://example.com/callback'
        );
        $userData = User::factory()->make();
        $params = $userData->getAttributes() + ['password_confirmation' => $userData->getAttribute('password')];
        $response = $this->json('POST', route('auth.register'), $params)->assertStatus(201);
        $response->assertJsonStructure(['token']);
    }

    /** @test */
    public function it_should_throw_validation_exception()
    {
        $response = $this->json('POST', route('auth.register'))->assertStatus(422);
        $response->assertJsonStructure(['message', 'errors']);
    }
}