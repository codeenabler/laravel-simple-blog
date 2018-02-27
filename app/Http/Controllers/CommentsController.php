<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Post;
use App\Http\Requests\CreatePostRequest;
use Illuminate\Http\Request;

class CommentsController extends Controller
{
    /**
     * Create a new CommentsController instance.
     */
    public function __construct()
    {
        $this->middleware('auth', ['except' => 'index']);
    }

    /**
     * Fetch all relevant comments.
     *
     * @param int    $categoryId
     * @param Post $post
     */
    public function index($categoryId, Post $post)
    {
        return $post->comments()->paginate(20);
    }

    /**
     * Persist a new comment.
     *
     * @param  integer           $categoryId
     * @param  Post            $post
     * @param  CreatePostRequest $form
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function store($categoryId, Post $post, CreatePostRequest $form)
    {
        return $post->addComment([
            'body' => request('body'),
            'user_id' => auth()->id()
        ])->load('owner');
    }

    /**
     * Update an existing comment.
     *
     * @param Comment $comment
     */
    public function update(Comment $comment)
    {
        $this->authorize('update', $comment);

        $comment->update(request()->validate(['body' => 'required']));
    }

    /**
     * Delete the given comment.
     *
     * @param  Comment $comment
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Comment $comment)
    {
        $this->authorize('update', $comment);

        $comment->delete();

        if (request()->expectsJson()) {
            return response(['status' => 'Comment deleted']);
        }

        return back();
    }
}
