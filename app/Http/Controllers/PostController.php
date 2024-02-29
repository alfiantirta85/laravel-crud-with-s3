<?php

namespace App\Http\Controllers;

//import Model Post
use App\Models\Post;

//return type View
use Illuminate\View\View;

//return type redirectResponse
use Illuminate\Http\RedirectResponse;

//return Facade "Storage"
use Illuminate\Support\Facades\Storage;

use Illuminate\Http\Request;

class PostController extends Controller
{
	/**
	 * method index
	 * @return View
	 */
	public function index(): View
	{
		//get posts
		$posts = Post::latest()->paginate(5);
		//render view with posts
		return view('posts.index', compact('posts'));
	}

	/**
	 * method create
	 * @return View
	 */
	public function create(): View
	{
		return view('posts.create');
	}

	/**
	 * method store
	 * @param mixed $request
	 * @return RedirectResponse
	 */
	public function store(Request $request): RedirectResponse
	{
		//validate form
		$this->validate($request, [
			'foto'		=> 'required|image|mimes:jpeg,jpg,png|max:2048',
			'nama' 		=> 'required|min:5',
			'nik'		=> 'required|min:16',
			'nisn'		=> 'required|min:5',
			'alamat'	=> 'required|min:5'
		]);
		//upload image
		$foto = $request->file('foto');
		$foto->storeAs($foto->getClientOriginalName());
		//create post
		Post::create([
			'foto'		=> $foto->getClientOriginalName(),
			'nama'		=> $request->nama,
			'nik'		=> $request->nik,
			'nisn'		=> $request->nisn,
			'alamat'	=> $request->alamat
		]);
		//redirect to index
		return redirect()->route('posts.index')->with(['success' => 'Data Berhasil Disimpan!']);
	}

	/**
	 * method show
	 * @param mixed $id
	 * @return View
	 */
	public function show(string $id): View
	{
		//get post by ID
		$post = Post::findOrFail($id);
		//render view with post
		return view('posts.show', compact('post'));
	}

	/**
	 * method edit
	 * @param mixed $id
	 * @return view
	 */
	public function edit(string $id): View
	{
		//get post by ID
		$post = Post::findOrFail($id);
		//render view with post
		return view('posts.edit', compact('post'));
	}

	/**
	 * method update
	 * @param mixed $request
	 * @param mixed $id
	 * @return RedirectResponse
	 */
	public function update(Request $request, $id): RedirectResponse
	{
		//validate form
		$this->validate($request, [
			'foto'		=> 'required|image|mimes:jpeg,jpg,png|max:2048',
			'nama' 		=> 'required|min:5',
			'nik'		=> 'required|min:16',
			'nisn'		=> 'required|min:5',
			'alamat'	=> 'required|min:5'
		]);
		//get post by ID
		$post = Post::findOrFail($id);
		//check if image is uploaded
		if ($request->hasFile('foto')) {
			//upload new image
			$foto = $request->file('foto');
			$foto->storeAs($foto->getClientOriginalName());
			//delete old image
			Storage::delete($post->foto);
			//update post with new image
			$post->update([
				'foto'		=> $foto->getClientOriginalName(),
				'nama'		=> $request->nama,
				'nik'		=> $request->nik,
				'nisn'		=> $request->nisn,
				'alamat'	=> $request->alamat
			]);
		} else {
			//update post without image
			$post->update([
				'nama'		=> $request->nama,
				'nik'		=> $request->nik,
				'nisn'		=> $request->nisn,
				'alamat'	=> $request->alamat
			]);
		}
		//redirect to index
		return redirect()->route('posts.index')->with(['success' => 'Data Berhasil Diubah!']);
	}

	/**
	 * method destroy
	 * @param mixed $Post
	 * @return void
	 */
	public function destroy($id): RedirectResponse
	{
		//get post by ID
		$post = Post::findOrFail($id);
		//delete image
		Storage::delete($post->foto);
		//delete post
		$post->delete();
		//redirect to index
		return redirect()->route('posts.index')->with(['success' => 'Data Berhasil Dihapus!']);
	}
}