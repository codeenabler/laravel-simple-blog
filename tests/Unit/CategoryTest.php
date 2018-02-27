<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class CategoryTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function a_category_consists_of_posts()
    {
        $category = create('App\Category');
        $post = create('App\Post', ['category_id' => $category->id]);

        $this->assertTrue($category->posts->contains($post));
    }
}
