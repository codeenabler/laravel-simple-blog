<?php

namespace App\Http\Controllers;

use App\Category;
use App\Post;
use Illuminate\Http\Request;

class PostsController extends Controller
{
    /**
     * Create a new PostsController instance.
     */
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
    }

    /**
     * Display a listing of the resource.
     *
     * @param Category      $category
     * @return \Illuminate\Http\Response
     */
    public function index(Category $category)
    {
        $posts = $this->getPosts($category);

        if (request()->wantsJson()) {
            return $posts;
        }

        return view('posts.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('posts.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Rules\Recaptcha $recaptcha
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate([
            'title' => 'required',
            'body' => 'required',
            'category_id' => 'required|exists:categories,id',
        ]);

        $post = Post::create([
            'user_id' => auth()->id(),
            'category_id' => request('category_id'),
            'title' => request('title'),
            'body' => request('body')
        ]);

        if (request()->wantsJson()) {
            return response($post, 201);
        }

        return redirect($post->path())
            ->with('flash', 'Your post has been published!');
    }

    /**
     * Display the specified resource.
     *
     * @param  integer      $category
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show($category, Post $post)
    {
        return view('posts.show', compact('post'));
    }

    /**
     * Update the given post.
     *
     * @param string $category
     * @param Post $post
     */
    public function update($category, Post $post)
    {
        $this->authorize('update', $post);

        $post->update(request()->validate([
            'title' => 'required',
            'body' => 'required'
        ]));

        return $post;
    }

    /**
     * Delete the given post.
     *
     * @param        $category
     * @param Post $post
     * @return mixed
     */
    public function destroy($category, Post $post)
    {
        $this->authorize('update', $post);

        $post->delete();

        if (request()->wantsJson()) {
            return response([], 204);
        }

        return redirect('/posts');
    }

    /**
     * Fetch all relevant posts.
     *
     * @param Category    $category
     * @return mixed
     */
    protected function getPosts(Category $category)
    {
        $posts = Post::latest();

        if ($category->exists) {
            $posts->where('category_id', $category->id);
        }

        return $posts->paginate(25);
    }
}
