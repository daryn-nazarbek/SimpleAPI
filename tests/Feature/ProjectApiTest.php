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

    public function testProjectsDeletedCorrectly()
    {
        $project = factory(Project::class)->create();

        $this->json('DELETE', '/api/projects/'.$project->id)->assertStatus(204);
    }
}
