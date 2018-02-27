<?php

use App\Post;
use App\User;
use Illuminate\Database\Seeder;

class PostsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::all()->each(function ($user) {
            factory(Post::class, 10)->create(['user_id' => $user->id, 'category_id' => rand(1, 5)]);
        });
    }
}
