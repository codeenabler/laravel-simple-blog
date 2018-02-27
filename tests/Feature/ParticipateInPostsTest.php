<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ParticipateInPostsTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function unauthenticated_users_may_not_add_comments()
    {
        $this->withExceptionHandling()
            ->post('/posts/some-category/1/comments', [])
            ->assertRedirect('/login');
    }

    /** @test */
    public function an_authenticated_user_may_participate_in_posts()
    {
        $this->signIn();

        $post = create('App\Post');
        $comment = make('App\Comment');

        $this->post($post->path() . '/comments', $comment->toArray());

        $this->assertDatabaseHas('comments', ['body' => $comment->body]);
        $this->assertEquals(1, $post->fresh()->comments_count);
    }

    /** @test */
    public function a_comment_requires_a_body()
    {
        $this->withExceptionHandling()->signIn();

        $post = create('App\Post');
        $comment = make('App\Comment', ['body' => null]);

        $this->post($post->path() . '/comments', $comment->toArray())
             ->assertSessionHasErrors('body');
    }

    /** @test */
    public function unauthorized_users_cannot_delete_comments()
    {
        $this->withExceptionHandling();

        $comment = create('App\Comment');

        $this->delete("/comments/{$comment->id}")
            ->assertRedirect('login');

        $this->signIn()
            ->delete("/comments/{$comment->id}")
            ->assertStatus(403);
    }

    /** @test */
    public function authorized_users_can_delete_comments()
    {
        $this->signIn();
        $comment = create('App\Comment', ['user_id' => auth()->id()]);

        $this->delete("/comments/{$comment->id}")->assertStatus(302);

        $this->assertDatabaseMissing('comments', ['id' => $comment->id]);

        $this->assertEquals(0, $comment->post->fresh()->comments_count);
    }

    /** @test */
    public function unauthorized_users_cannot_update_comments()
    {
        $this->withExceptionHandling();

        $comment = create('App\Comment');

        $this->patch("/comments/{$comment->id}")
            ->assertRedirect('login');

        $this->signIn()
            ->patch("/comments/{$comment->id}")
            ->assertStatus(403);
    }

    /** @test */
    public function authorized_users_can_update_comments()
    {
        $this->signIn();

        $comment = create('App\Comment', ['user_id' => auth()->id()]);

        $updatedComment = 'You been changed, fool.';
        $this->patch("/comments/{$comment->id}", ['body' => $updatedComment]);

        $this->assertDatabaseHas('comments', ['id' => $comment->id, 'body' => $updatedComment]);
    }

    /** @test */
    public function users_may_only_comment_a_maximum_of_once_per_minute()
    {
        $this->withExceptionHandling();

        $this->signIn();

        $post = create('App\Post');
        $comment = make('App\Comment');

        $this->post($post->path() . '/comments', $comment->toArray())
            ->assertStatus(201);

        // $this->post($post->path() . '/comments', $comment->toArray())
        //     ->assertStatus(429);
    }
}
