<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\User;
use Faker\Generator as Faker;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Str;
use Grimzy\LaravelMysqlSpatial\Types\Point;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(User::class, function (Faker $faker) {
    $now = Date::now();
    return [
        'name' => $faker->name,
        'uuid' => (string) $faker->uuid,
//        'photo_id' => factory(Photo::class)->create()->id, Check why this line broke the pipe!!!
        'email' => $faker->unique()->safeEmail,
        'location' => (new Point($faker->latitude(), $faker->longitude())),
        'email_verified_at' => $now,
        'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        'api_token' => $faker->uuid,
        'remember_token' => Str::random(10),
        'created_at' => $now,
        'is_public' => true,
    ];
});