<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ReadPostsTest extends TestCase
{
    use DatabaseMigrations;

    protected $post;

    public function setUp()
    {
        parent::setUp();

        $this->post = create('App\Post');
    }

    /** @test */
    public function a_user_can_view_all_posts()
    {
        $this->get('/posts')
            ->assertSee($this->post->title);
    }

    /** @test */
    public function a_user_can_read_a_single_post()
    {
        $this->get($this->post->path())
            ->assertSee($this->post->title);
    }

    /** @test */
    public function a_user_can_request_all_comments_for_a_given_post()
    {
        $post = create('App\Post');
        create('App\Comment', ['post_id' => $post->id], 2);

        $response = $this->getJson($post->path() . '/comments')->json();

        $this->assertCount(2, $response['data']);
        $this->assertEquals(2, $response['total']);
    }
}
