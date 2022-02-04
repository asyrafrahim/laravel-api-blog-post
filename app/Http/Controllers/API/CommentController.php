<?php

namespace App\Http\Controllers\API;

use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CommentController extends Controller
{
    public function index(Post $post)
    {
        $comments = $post->comments;

        return response()->json([
            'success' => true,
            'data' => $comments,
            'message' => 'Fetching comments successfully'
        ]);
    }
    
    public function store(Request $request, Post $post){
        $comment = comments()->create($request->all());

        return response()->json([
            'success' => true,
            'data' => $comment,
            'message' => 'Comment created successfully'
        ]);
    }

    public function replyComment(Request $request, Post $post)
    {
        $this->validate($request, [
            'comment' => 'required|min:3',
        ]);
        
        // $reply = comments()->create([
        //     'comment' => $request->comment,
        //     'user_id' => auth()->user()->id,
        //     'post_id' => $post->id,
        //     'parent_id' => $request->parent_id
        // ]);

        $reply = new Comment();
        $reply->comment = $request->get('comment');
        $reply->user()->associate($request->user());
        $reply->parent_id = $request->get('comment_id');
        $post = Post::find($request->get('post_id'));
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
