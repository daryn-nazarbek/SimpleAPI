<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Project;
use App\Client;

class ProjectApiTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */

    use WithFaker;

    public function testProjectsListedCorrectly()
    {
        $response = $this->get("api/projects");

        $response->assertStatus(200)->assertJsonStructure([
                'data' => ['*' => ['id', 'name', 'description', 'status', 'created_at', 'updated_at']],
            ]);;
    }

    public function testProjectsShownCorrectly()
    {
        $project = factory(Project::class)->create();
        $response = $this->get("api/projects/".$project->id);

        $response->assertStatus(200)->assertJson([
            'data' => [
                'id' => $project->id,
                'name' => $project->name,
                'description' => $project->description,
                'status' => $project->status
            ]
        ]);
    }
    public function testProjectsShowWithBadRequest()
    {
        $response = $this->get("api/projects/999999999");//bad request. There is no such client

        $response->assertStatus(404)->assertJson([
            'error' => 'Resource not found!'
        ]);
    }

    public function testProjectsCreatedCorrectly()
    {
        $client = factory(Client::class)->create();

        $statuses = ['planned', 'running', 'onhold', 'finished', 'cancel'];
        $params = [
            'name' => $this->faker->sentence(5),
            'description' => $this->faker->text,
            'status' => $statuses[array_rand($statuses)],
            'client_id' => $client->id
        ];
        $response = $this->postJson('api/projects', $params);
        
        $response->assertStatus(201)
            ->assertJson([
                'name' => $params['name'],
                'description' => $params['description'],
                'status' => $params['status']
            ]);
    }

    public function testProjectsCreateWithBadRequest()
    {
        $client = factory(Client::class)->create();

        $statuses = ['planned', 'running', 'onhold', 'finished', 'cancel'];
        $params = [
            'name' => '', //empty name field
            'description' => $this->faker->text,
            'status' => $statuses[array_rand($statuses)],
            'client_id' => 9999999 //bad client_d. There is no client with such id
        ];
        $response = $this->postJson('api/projects', $params);

        $response->assertStatus(422)
            ->assertJson([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'client_id' => [
                        0 => 'The selected client id is invalid.'
                    ],
                    'name' => [
                        0 => 'The name field is required.'
                    ]
                ]
            ]);
    }

    public function testsProjectsUpdatedCorrectly()
    {
        $project = factory(Project::class)->create();

        $statuses = ['planned', 'running', 'onhold', 'finished', 'cancel'];
        $params = [
            'name' => $this->faker->sentence(5),
            'description' => $this->faker->text,
            'status' => $statuses[array_rand($statuses)]
        ];

        $response = $this->json('PUT', '/api/projects/' . $project->id, $params)
            ->assertStatus(200)
            ->assertJson([ 
                'id' => $project->id, 
                'name' => $params['name'], 
                'description' => $params['description'],
                'status' => $params['status']
            ]);
    }

    public function testsProjectsUpdateWithBadRequest()
    {
        $project = factory(Project::class)->create();

        $statuses = ['planned', 'running', 'onhold', 'finished', 'cancel'];
        $params = [
            'name' => $this->faker->sentence(5),
            'description' => 123.45, //bad description
            'status' => 'awaiting' //incorrect option for status field
        ];

        $response = $this->json('PUT', '/api/projects/' . $project->id, $params);
        $response->assertStatus(422)
            ->assertJson([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'description' => [
                        0 => 'The description must be a string.'
                    ],
                    'status' => [
                        0 => 'The selected status is invalid.'
                    ]
                ]
            ]);
    }

    public function testProjectsDeletedCorrectly()
    {
        $project = factory(Project::class)->create();

        $this->json('DELETE', '/api/projects/'.$project->id)->assertStatus(204);
    }

    public function testProjectsDeleteWithBadRequest()
    {
        $response = $this->json('DELETE', '/api/projects/9999999999');//bad request. There is no such client
        
        $response->assertStatus(404)->assertJson([
            'error' => 'Resource not found!'
        ]);
    }
}
