<?php

namespace App\Http\Controllers\API;

use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::orderBy('created_at', 'desc')->get();

        return response()->json([
            'success' => true,
            'data' => $posts,
            'message' => 'Fetching posts successfully'
        ]);
    }
    
    public function store(Request $request)
    {
        $post = auth()->user()->posts()->create($request->all());

        if($request->hasFile('attachment')){
            $filename = $post->id.'-'.date("Y-m-d").'.'.$request->attachment->getClientOriginalExtension();
            Storage::disk('public')->put($filename, File::get($request->attachment));
            
            $post->attachment = $filename;
            $post->save();
        }

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
