<?php

$factory->define(App\Like::class, function (Faker\Generator $faker) {
    return [
        "user_id" => factory('App\User')->create(),
        "room_id" => factory('App\Room')->create(),
    ];
});
