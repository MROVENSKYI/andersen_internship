<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\ClientRepository;
use Tests\TestCase;

class GetUsersListTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_should_show_list_of_users()
    {
        $clientRepository = new ClientRepository();
        $clientRepository->createPersonalAccessClient(
            null,
            'Personal Access Client',
            'http://example.com/callback'
        );
        $users = User::factory()->count(5)->create();
        $response = $this->json('GET', route('users.list'))->assertStatus(200);
        $response->assertJsonStructure();
    }
}
