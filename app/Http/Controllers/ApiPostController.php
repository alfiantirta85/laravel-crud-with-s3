<?php
namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ApiPostController extends Controller
{

    public function index()
    {
        return Post::latest()->paginate(5);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'foto'		=> 'required|image|mimes:jpeg,jpg,png|max:2048',
			'nama' 		=> 'required|min:4',
			'nik'		=> 'required|digits:16',
			'nisn'		=> 'required|digits:10',
			'alamat'	=> 'required|min:5'
        ]);

        $foto = $request->file('foto');
        $foto->storeAs($foto->getClientOriginalName());

        $post = Post::create([
            'foto'		=> $foto->getClientOriginalName(),
			'nama'		=> $request->nama,
			'nik'		=> $request->nik,
			'nisn'		=> $request->nisn,
			'alamat'	=> $request->alamat
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
            'foto'		=> 'required|image|mimes:jpeg,jpg,png|max:2048',
			'nama' 		=> 'required|min:4',
			'nik'		=> 'required|digits:16',
			'nisn'		=> 'required|digits:10',
			'alamat'	=> 'required|min:5'
        ]);

        if ($request->hasFile('foto')) {
			
			$foto = $request->file('foto');
			$foto->storeAs($foto->getClientOriginalName());
			
			Storage::delete($post->foto);
			
			$post->update([
				'foto'		=> $foto->getClientOriginalName(),
				'nama'		=> $request->nama,
				'nik'		=> $request->nik,
				'nisn'		=> $request->nisn,
				'alamat'	=> $request->alamat
			]);
		} else {
			$post->update([
				'nama'		=> $request->nama,
				'nik'		=> $request->nik,
				'nisn'		=> $request->nisn,
				'alamat'	=> $request->alamat
			]);
		}

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

        Storage::delete($post->foto);
        $post->delete();
        
        return response()->json([
            'message' => 'deleted'
        ]);
    }

}