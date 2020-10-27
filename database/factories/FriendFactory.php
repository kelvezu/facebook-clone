<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Friend;
use App\User;
use Faker\Generator as Faker;

$factory->define(Friend::class, function (Faker $faker) {
    return [
        'user_id' => factory(User::class),
        'friend_id' => factory(Friend::class),
        'status' => $faker->boolean,
        'confirmed_at' => $faker->date(),
    ];
});
