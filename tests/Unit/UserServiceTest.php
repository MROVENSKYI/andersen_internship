<?php

namespace Tests\Unit;

use App\Models\User;
use App\Services\UserService;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;



class UserServiceTest extends TestCase
{
    use DatabaseMigrations;
    /** @test */
    public function it_should_store_user()
    {
        $userService = new UserService();
        $userData = User::factory()->make();
        $createdUser = $userService->store($userData->getAttributes());

        $this->assertInstanceOf(User::class, $createdUser);
        $this->assertDatabaseHas('users', [
            'email' => $userData->email
        ]);
    }
}