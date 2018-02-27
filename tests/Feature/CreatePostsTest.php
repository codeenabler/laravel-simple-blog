<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Tests\TestCase;

class CreatePostsTest extends TestCase
{
    use DatabaseMigrations, MockeryPHPUnitIntegration;

    /** @test */
    public function guests_may_not_create_posts()
    {
        $this->withExceptionHandling();

        $this->get('/posts/create')
            ->assertRedirect(route('login'));

        $this->post(route('posts'))
            ->assertRedirect(route('login'));
    }

    /** @test */
    public function a_user_can_create_new_posts()
    {
        $response = $this->publishPost(['title' => 'Some Title', 'body' => 'Some body.']);

        $this->get($response->headers->get('Location'))
            ->assertSee('Some Title')
            ->assertSee('Some body.');
    }

    /** @test */
    public function a_post_requires_a_title()
    {
        $this->publishPost(['title' => null])
            ->assertSessionHasErrors('title');
    }

    /** @test */
    public function a_post_requires_a_body()
    {
        $this->publishPost(['body' => null])
            ->assertSessionHasErrors('body');
    }

    /** @test */
    public function a_post_requires_a_valid_category()
    {
        factory('App\Category', 2)->create();

        $this->publishPost(['category_id' => null])
            ->assertSessionHasErrors('category_id');

        $this->publishPost(['category_id' => 999])
            ->assertSessionHasErrors('category_id');
    }

    /** @test */
    public function a_post_requires_a_unique_slug()
    {
        $this->signIn();

        $post = create('App\Post', ['title' => 'Foo Title']);

        $this->assertEquals($post->slug, 'foo-title');

        $post = $this->postJson(route('posts'), $post->toArray())->json();

        $this->assertEquals("foo-title-{$post['id']}", $post['slug']);
    }

    /** @test */
    public function a_post_with_a_title_that_ends_in_a_number_should_generate_the_proper_slug()
    {
        $this->signIn();

        $post = create('App\Post', ['title' => 'Some Title 24']);

        $post = $this->postJson(route('posts'), $post->toArray())->json();

        $this->assertEquals("some-title-24-{$post['id']}", $post['slug']);
    }

    /** @test */
    public function unauthorized_users_may_not_delete_posts()
    {
        $this->withExceptionHandling();

        $post = create('App\Post');

        $this->delete($post->path())->assertRedirect('/login');

        $this->signIn();
        $this->delete($post->path())->assertStatus(403);
    }

    /** @test */
    public function authorized_users_can_delete_posts()
    {
        $this->signIn();

        $post = create('App\Post', ['user_id' => auth()->id()]);
        $comment = create('App\Comment', ['post_id' => $post->id]);

        $response = $this->json('DELETE', $post->path());

        $response->assertStatus(204);

        $this->assertDatabaseMissing('posts', ['id' => $post->id]);
        $this->assertDatabaseMissing('comments', ['id' => $comment->id]);
    }

    protected function publishPost($overrides = [])
    {
        $this->withExceptionHandling()->signIn();

        $post = make('App\Post', $overrides);

        return $this->post(route('posts'), $post->toArray());
    }
}
