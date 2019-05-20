<?php

namespace Tests\Feature\Api;

use App\User;
use Faker\Factory;
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
            ->assertJsonStructure(
                [
                    "success",
                    "message",
                    "payload" => [
                    ],
                ]
            )
            ->assertJson([
                'success' => false,
            ]);
    }


    public function testUserLoginsSuccessfully()
    {
        $faker = Factory::create();
        $data = ['email' => $faker->email, 'password' => bcrypt('thinker')];
        factory(User::class)->create($data);

        $this->json('POST', 'api/login', ['email' => $data['email'], 'password' => 'thinker'])
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure(
                [
                    "success",
                    "message",
                    "payload" => [],
                ]
            );

    }
}
