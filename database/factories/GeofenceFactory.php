<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Geofence;
use App\Photo;
use App\User;
use Faker\Generator as Faker;
use Grimzy\LaravelMysqlSpatial\Types\LineString;
use Grimzy\LaravelMysqlSpatial\Types\Point;
use Grimzy\LaravelMysqlSpatial\Types\Polygon;

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

$factory->define(Geofence::class, function (Faker $faker) {
    return [
        'uuid' => (string) $faker->uuid,
        'user_uuid' => factory(User::class)->create()->uuid,
        'photo_uuid' => factory(Photo::class)->create()->uuid,
        'name' => $faker->name,
        'is_public' => true,
        'shape' => (new Polygon(\App\Repositories\GeofenceRepository::createSamplePolygon())),
    ];
});
