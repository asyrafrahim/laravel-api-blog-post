<?php

namespace App\Http\Controllers\API\Guest;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    
    
    public function store(Request $request, Post $post)
    {
        $comment = comments()->create($request->all() + ['post_id' => $post->id]);

        return response()->json([
            'success' => true,
            'data' => $comment,
            'message' => 'Comment created successfully'
        ]);
    }

    public function replyComment(Request $request, Post $post)
    {
        // $this->validate($request, [
        //     'description' => 'required|min:3',
        // ]);

        $validator = Validator::make($request->all(), [
            'comment'=>'required',
            'post_id' => 'required|exists:posts,id'
        ]);
        
        $reply = new Comment();
        $reply->comment = $request->get('comment');
        $reply->parent_id = $request->get('comment_id');
        $reply->post_id = $request->post->id;
        
        $post->comments()->save($reply);	
        
 
        // $reply = auth()->user()->comments()->create($request->all() + ['post_id' => $post->id]);

        return response()->json([
            'success' => true,
            'data' => $reply,
            'message' => 'Comment replied successfully'
        ]);
    }
}
