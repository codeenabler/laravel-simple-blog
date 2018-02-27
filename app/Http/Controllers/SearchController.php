<?php

namespace App\Http\Controllers;

use App\Post;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    /**
     * Show the search results.
     *
     * @return mixed
     */
    public function show()
    {
        $posts = Post::search(request('q'))->paginate(25);

        return view('posts.index', compact('posts'));
    }
}
