<?php

$factory->define(App\Comment::class, function (Faker\Generator $faker) {
    return [
        "comment" => $faker->name,
        "user_id" => factory('App\User')->create(),
        "room_id" => factory('App\Room')->create(),
        "rate" => $faker->randomNumber(2),
    ];
});
