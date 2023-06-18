<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\ClientRepository;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class DeleteUserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_should_delete_user()
    {
        $clientRepository = new ClientRepository();
        $clientRepository->createPersonalAccessClient(
            null,
            'Personal Access Client',
            'http://example.com/callback'
        );

        $user = User::factory()->create();
        $token = $user->createToken('API Token')->accessToken;
        $headers = ['Authorization' => "Bearer $token"];
        $payload = [
            'email' => 'newemail@example.com',
        ];

        $this->withoutExceptionHandling();

        $response = $this->actingAs($user)
            ->json('DELETE', route('user.destroy', $user->getAttribute('id')), $payload, $headers)
            ->assertStatus(Response::HTTP_OK);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'status' => User::INACTIVE,
        ]);
    }
}
