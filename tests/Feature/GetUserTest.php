<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\ClientRepository;
use Tests\TestCase;

class GetUserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_should_show_info_to_auth_user()
    {
        $clientRepository = new ClientRepository();
        $clientRepository->createPersonalAccessClient(
            null,
            'Personal Access Client',
            'http://example.com/callback'
        );
        $user1 = User::factory()->create();
        $token = $user1->createToken('API Token')->accessToken;
        $headers = ['Authorization' => "Bearer $token"];
        $payload = [
            'email' => 'newemail@example.com',
        ];
        $user2 = User::factory()->create();
        $response = $this->actingAs($user1)->json('GET', route('user.show', $user2->getAttribute('id')), $payload, $headers)->assertStatus(403);
        $response->assertStatus(403);
    }
}
