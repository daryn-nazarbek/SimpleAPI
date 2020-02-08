<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Project;
use App\Client;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(Project::class, function (Faker $faker) {
	$statuses = ['planned', 'running', 'onhold', 'finished', 'cancel'];
    return [
        'name' => $faker->sentence(5),
        'description' => $faker->text,
        'status' => $statuses[array_rand($statuses)],
        'client_id' => factory(App\Client::class),
    ];
});
