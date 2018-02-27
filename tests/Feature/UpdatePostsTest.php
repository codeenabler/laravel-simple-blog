<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UpdatePostsTest extends TestCase
{
    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();

        $this->withExceptionHandling();

        $this->signIn();
    }

    /** @test */
    public function unauthorized_users_may_not_update_posts()
    {
        $post = create('App\Post', ['user_id' => create('App\User')->id]);

        $this->patch($post->path(), [])->assertStatus(403);
    }

    /** @test */
    public function a_post_requires_a_title_and_body_to_be_updated()
    {
        $post = create('App\Post', ['user_id' => auth()->id()]);

        $this->patch($post->path(), [
            'title' => 'Changed'
        ])->assertSessionHasErrors('body');

        $this->patch($post->path(), [
            'body' => 'Changed'
        ])->assertSessionHasErrors('title');
    }

    /** @test */
    public function a_post_can_be_updated_by_its_author()
    {
        $post = create('App\Post', ['user_id' => auth()->id()]);

        $this->patch($post->path(), [
            'title' => 'Changed',
            'body' => 'Changed body.'
        ]);

        tap($post->fresh(), function ($post) {
            $this->assertEquals('Changed', $post->title);
            $this->assertEquals('Changed body.', $post->body);
        });
    }
}
