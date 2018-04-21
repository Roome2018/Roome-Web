<?php

$factory->define(App\Room::class, function (Faker\Generator $faker) {
    return [
        "title" => $faker->name,
        "info" => $faker->name,
        "price" => $faker->randomNumber(2),
        "max_tenants" => $faker->randomNumber(2),
        "view_count" => $faker->randomNumber(2),
        "is_available" => collect(["true","false",])->random(),
    ];
});
