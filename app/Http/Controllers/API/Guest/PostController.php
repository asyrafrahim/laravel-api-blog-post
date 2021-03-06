<?php

namespace App\Http\Controllers\API\Guest;

use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function store(Request $request)
    {
        $post = Post::create($request->all());

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
}
