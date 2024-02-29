<?php
namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ApiPostController extends Controller
{

    public function index()
    {
        return Post::latest()->paginate(5);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'image' => 'required|image|mimes:jpeg,jpg,png|max:2048',
            'title' => 'required|min:5',
            'content' => 'required|min:10'
        ]);

        $image = $request->file('image');
        $image->storeAs('public/images', $image->getClientOriginalName());

        $post = Post::create([
            'image' => $image->getClientOriginalName(),
            'title' => $request->title,
            'content' => $request->content
        ]);

        return response()->json($post, 201);
    }

    public function show($id)
    {
        $post = Post::find($id);

        if(!$post) {
            return response()->json([
                'message' => 'not found'
            ], 404);
        }

        return $post;
    }

    public function update(Request $request, $id)
    {
        $post = Post::find($id);

        if(!$post) {
            return response()->json([
                'message' => 'not found'
            ], 404);
        }

        $this->validate($request, [
            'image' => 'required|image|mimes:jpeg,jpg,png|max:2048',
            'title' => 'required|min:5',
            'content' => 'required|min:10'
        ]);

        $image = $request->file('image');
        $image->storeAs('public/images', $image->getClientOriginalName());

        $post->update([
            'image' => $image->getClientOriginalName(),
            'title' => $request->title,
            'content' => $request->content
        ]);

        return $post;
    }

    public function destroy($id)
    {
        $post = Post::find($id);

        if(!$post) {
            return response()->json([
                'message' => 'not found'
            ], 404);
        }

        $post->delete();
        
        return response()->json([
            'message' => 'deleted'
        ]);
    }

}