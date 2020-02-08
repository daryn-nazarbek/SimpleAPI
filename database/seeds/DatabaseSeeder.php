<?php

use Illuminate\Database\Seeder;

use App\Client;
use App\Project;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $clients = factory(App\Client::class, 5)
            ->create()
            ->each(function ($client) {
                $client->projects()->createMany(factory(App\Project::class, rand(0, 4))->make()->toArray());
            });
    }
}
