<?php

namespace Tests\Feature;

use App\Models\ResetPassword;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
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
        $password = Hash::make(fake()->password);
        $user = User::factory()->create(['email' => 'markrovensky@gmail.com']);
        $userReset = ResetPassword::factory()->create(['id' => $user->getAttribute('id'), 'email' => $user->getAttribute('email'), 'password' => $password]);
        $params = ['token' => $userReset['token'], 'email' => $userReset['email'], 'password' => $userReset['password'], 'password_confirmation' => $userReset['password']];
        $response = $this->json('POST', route('password.reset'), $params)->assertStatus(200);
        $response->assertStatus(200);
    }

    /** @test */
    public function it_should_throw_validation_exception()
    {
        $response = $this->json('POST', route('password.reset'))->assertStatus(422);
        $response->assertJsonStructure(['message', 'errors']);
    }

}