<?php

use Faker\Generator as Faker;

$factory->define(App\Post::class, function (Faker $faker) {
    $title = $faker->sentence;
    return [
        'user_id' => function () {
            return factory('App\User')->create()->id;
        },
        'category_id' => function () {
            return factory('App\Category')->create()->id;
        },
        'title' => $title,
        'slug' => str_slug($title),
        'body' => $faker->paragraph,
        'published' => true
    ];
});
