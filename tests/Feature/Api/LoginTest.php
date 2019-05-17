<?php

namespace Tests\Feature\Api;

use App\User;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LoginTest extends TestCase
{

    public function testRequiresEmailAndLogin()
    {
        $response = $this->json('POST', 'api/login');

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
//            ->assertJsonStructure(
//                [
//                    "success" => false,
//                    "message" => [],
//                    "payload" => [],
//                ]
//            )
            ->assertJson([
                'success' => false,
            ]);
    }


    public function testUserLoginsSuccessfully()
    {
        $user = factory(User::class)->create([
            'email' => 'testlogin@user1.com2',
            'password' => bcrypt('toptal123'),
        ]);

        $payload = ['email' => 'testlogin@user.com', 'password' => 'toptal123'];

        $this->json('POST', 'api/login', $payload)
            ->assertStatus(Response::HTTP_OK);
//            ->assertJsonStructure(
//                [
//                    "success" => true,
//                    "message" => [],
//                    "payload" => [],
//                ]
//            );

    }
}
