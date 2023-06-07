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
        $user = User::factory()->make();
        $response = $this->actingAs($user)->json('POST', route('auth.login'), $user->getAttributes())->assertStatus(200);
        $response->assertJsonStructure(['token']);
    }
}