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
        $comments = Comment::where('post_id', $post->id)->get();

        return response()->json([
            'success' => true,
            'data' => $comments,
            'message' => 'Fetching comments successfully'
        ]);
    }
    
    public function store(Request $request, Post $post)
    {
        $comment = auth()->user()->comments()->create($request->all() + ['post_id' => $post->id]);

        return response()->json([
            'success' => true,
            'data' => $comment,
            'message' => 'Comment created successfully'
        ]);
    }

    public function replyComment(Request $request, Post $post)
    {
        $validator = Validator::make($request->all(), [
            'comment'=>'required',
            'post_id' => 'required|exists:posts,id'
        ]);
        
        $reply = new Comment();
        $reply->comment = $request->get('comment');
        $reply->user()->associate($request->user());
        $reply->parent_id = $request->get('comment_id');
        $reply->post_id = $request->post->id;
        
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
