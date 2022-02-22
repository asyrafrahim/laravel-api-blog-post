<?php

namespace App\Http\Controllers\API;

use App\Models\Post;
use App\Models\Comment;
use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CommentController extends Controller
{
    public function index(Post $post)
    {
        $comments = Comment::where('commentable_id', $post->id)->get();

        return response()->json([
            'success' => true,
            'data' => $comments,
            'message' => 'Fetching comments successfully'
        ]);
    }
    
    public function store(Request $request, Post $post)
    {
        $this->validate($request, [
            'comment' => 'required',
            ]);
        
        $comment = new Comment;
        $comment->comment = $request->comment;
        $comment->user()->associate($request->user());

        $comment->commentable_id = $post;
        // Alternatively, use this for testing. Enter a post id inside find()
        // $post = Post::find();

        $post->comments()->save($comment);

        return response()->json([
            'success' => true,
            'data' => $comment,
            'message' => 'Comment created successfully'
        ]);
    }

    public function replyComment(Request $request, Post $post)
    {
        $this->validate($request, [
            'comment' => 'required',
            ]);
        
        $reply = new Comment();
        $reply->comment = $request->get('comment');
        $reply->user()->associate($request->user());
        
        // Enter parent_id = {comment id} for testing
        $reply->parent_id = $request->get('comment_id');
        
        // Alternatively, use this for testing. Enter a post id inside find()
        // $post = Post::find($request->get('post_id'));
        $reply->commentable_id = $post;

        $post->comments()->save($reply);

        return response()->json([
            'success' => true,
            'data' => $reply,
            'message' => 'Comment replied successfully'
        ]);
    }
    
    public function update(Request $request, Comment $comment){
        $comment->update($request->all());

        return response()->json([
            'success' => true,
            'data' => $comment,
            'message' => 'Comment updated successfully'
        ]);
    }
}
