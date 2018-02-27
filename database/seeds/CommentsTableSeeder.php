<?php

use App\Comment;
use App\Post;
use Illuminate\Database\Seeder;

class CommentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Post::all()->each(function ($post) {
            factory(Comment::class, 5)->create(['user_id' => rand(1, 5), 'post_id' => $post->id]);
        });
    }
}
