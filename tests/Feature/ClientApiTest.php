<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Client;

class ClientApiTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */

    use WithFaker;

    public function testClientsListedCorrectly()
    {
        $response = $this->get("api/clients");

        $response->assertStatus(200)->assertJsonStructure([
                'data' => ['*' => ['id', 'first_name', 'last_name', 'projects','email', 'password','created_at', 'updated_at']],
            ]);;
    }

    public function testClientShownCorrectly()
    {
        $client = factory(Client::class)->create();
        $response = $this->get("api/clients/".$client->id);

        $response->assertStatus(200)->assertJson([
            'data' => [
                'id' => $client->id,
                'first_name' => $client->first_name,
                'last_name' => $client->last_name,
                'email' => $client->email,
                'password' => $client->password,
            ]
        ]);
    }

    public function testClientsCreatedCorrectly()
    {
        $params = [
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'email' => $this->faker->email,
            'password' => $this->faker->password
        ];
        $response = $this->postJson('api/clients', $params);
        
        $response->assertStatus(201)
            ->assertJson([
                'first_name' => $params['first_name'],
                'last_name' => $params['last_name'],
                'email' => $params['email'],
                'password' => $params['password']
            ]);
    }

    public function testClientsDeletedCorrectly()
    {
        $client = factory(Client::class)->create();
        $response = $this->json('DELETE', '/api/clients/'.$client->id);
        $response->assertStatus(204);
    }
}
