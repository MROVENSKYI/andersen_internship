<?php

namespace Tests\Feature;

use App\Models\ResetPassword;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\ClientRepository;
use Tests\TestCase;


class ResetPasswordTest extends TestCase
{
    use DatabaseMigrations;
    use RefreshDatabase;


    /** @test */
    public function it_should_reset_password_and_generate_new_password()
    {
        $clientRepository = new ClientRepository();
        $clientRepository->createPersonalAccessClient(
            null,
            'Personal Access Client',
            'http://example.com/callback'
        );
        $password = fake()->password;
        $user = ResetPassword::factory()->make(['email' => 'markrovensky@gmail.com']);
        $params = $user->getAttributes() + ['password' => $password] + ['password_confirmation' => $password];
        $response = $this->json('POST', route('password.reset'), $params)->assertStatus(200);
        $response->assertJsonStructure();
    }

    /** @test */
    public function it_should_throw_validation_exception()
    {
        $response = $this->json('POST', route('password.reset'))->assertStatus(422);
        $response->assertJsonStructure(['message', 'errors']);
    }

}