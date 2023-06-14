<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\ClientRepository;
use Tests\TestCase;

class UpdateUserTest extends TestCase
{
    use RefreshDatabase;
    /** @test */
    public function it_should_update_user()
    {
        $clientRepository = new ClientRepository();
        $clientRepository->createPersonalAccessClient(
            null,
            'Personal Access Client',
            'http://example.com/callback'
        );
        $user = User::factory()->create(['email' => 'markrovensky@gmail.com']);
        $token = $user->createToken('API Token')->accessToken;
        $headers = ['Authorization' => "Bearer $token"];
        $payload = [
            'email' => 'newemail@example.com',
        ];
        $response = $this->json('PUT', route('update.user', $user->getAttribute('id')), $payload, $headers)->assertStatus(200);
        $response->assertStatus(200);
    }

    /** @test */
    public function it_should_not_authorized_user_for_update_data()
    {
        $clientRepository = new ClientRepository();
        $clientRepository->createPersonalAccessClient(
            null,
            'Personal Access Client',
            'http://example.com/callback'
        );
        $user = User::factory()->create(['email' => 'markrovensky@gmail.com']);
        $user = User::factory()->create();

        $response = $this->json('PUT', route('update.user', $user->getAttribute('id')), [
            'email' => 'newemail@example.com'
        ])->assertStatus(401);
        $response->assertStatus(401);
    }
}