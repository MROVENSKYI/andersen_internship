<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\ClientRepository;
use Tests\TestCase;

class ResetLinkTest extends TestCase
{
    use DatabaseMigrations;
    use RefreshDatabase;

    /** @test */
    public function it_should_deliver_reset_password_link()
    {
        $clientRepository = new ClientRepository();
        $clientRepository->createPersonalAccessClient(
            null,
            'Personal Access Client',
            'http://example.com/callback'
        );
        $userData = User::factory()->make();
        $response = $this->json('POST', route('password.link'), $userData->getAttributes())->assertStatus(200);
        $response->assertStatus(200);
    }

    /** @test */
    public function it_should_throw_validation_exception()
    {
        $response = $this->json('POST', route('password.link'))->assertStatus(422);
        $response->assertJsonStructure(['message', 'errors']);
    }
}
