<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class PostTest extends TestCase
{
    use DatabaseMigrations;

    protected $post;

    public function setUp()
    {
        parent::setUp();

        $this->post = create('App\Post');
    }

    /** @test */
    public function a_post_has_a_path()
    {
        $post = create('App\Post');

        $this->assertEquals(
            "/posts/{$post->category->slug}/{$post->slug}",
            $post->path()
        );
    }

    /** @test */
    public function a_post_has_a_author()
    {
        $this->assertInstanceOf('App\User', $this->post->author);
    }

    /** @test */
    public function a_post_has_comments()
    {
        $this->assertInstanceOf(
            'Illuminate\Database\Eloquent\Collection',
            $this->post->comments
        );
    }

    /** @test */
    public function a_post_can_add_a_comment()
    {
        $this->post->addComment([
            'body' => 'Foobar',
            'user_id' => 1
        ]);

        $this->assertCount(1, $this->post->comments);
    }

    /** @test */
    public function a_post_belongs_to_a_category()
    {
        $post = create('App\Post');

        $this->assertInstanceOf('App\Category', $post->category);
    }

    /** @test */
    public function a_posts_body_is_sanitized_automatically()
    {
        $post = make('App\Post', ['body' => '<script>alert("bad")</script><p>This is okay.</p>']);

        $this->assertEquals('<p>This is okay.</p>', $post->body);
    }
}
