<?php

$factory->define(App\Booking::class, function (Faker\Generator $faker) {
    return [
        "room_id" => factory('App\Room')->create(),
        "user_id" => factory('App\User')->create(),
        "date" => $faker->date("d-m-Y", $max = 'now'),
        "status" => collect(["0","1","2",])->random(),
    ];
});
