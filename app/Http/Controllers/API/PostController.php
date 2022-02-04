<?php

namespace App\Http\Controllers\API;

use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::orderBy('created_at', 'desc')->get();
        // $posts = posts()->orderBy('created_at', 'desc')->get();

        return response()->json([
            'success' => true,
            'data' => $posts,
            'message' => 'Fetching posts successfully'
        ]);
    }
    
    public function store(Request $request)
    {
        $post = Post::create($request->all());
        // $post = posts()->create($request->all());

        return response()->json([
            'success' => true,
            'data' => $post,
            'message' => 'Post created successfully'
        ]);
    }

    public function show(Post $post){
        return response()->json([
            'success' => true,
            'data' => $post,
            'message' => 'Fetching specific post successfully'
        ]);
    }
}
