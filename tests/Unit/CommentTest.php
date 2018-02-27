<?php

namespace Tests\Unit;

use App\Comment;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class CommentTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function it_has_an_owner()
    {
        $comment = create('App\Comment');

        $this->assertInstanceOf('App\User', $comment->owner);
    }

    /** @test */
    public function it_knows_if_it_was_just_published()
    {
        $comment = create('App\Comment');

        $this->assertTrue($comment->wasJustPublished());

        $comment->created_at = Carbon::now()->subMonth();

        $this->assertFalse($comment->wasJustPublished());
    }

    /** @test */
    public function a_comment_body_is_sanitized_automatically()
    {
        $comment = make('App\Comment', ['body' => '<script>alert("bad")</script><p>This is okay.</p>']);

        $this->assertEquals('<p>This is okay.</p>', $comment->body);
    }
}
