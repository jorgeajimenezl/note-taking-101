<?php

namespace App\Http\Controllers;

use App\Models\Tag;

class TagController extends Controller
{
    public function index()
    {
        $tags = Tag::where('user_id', auth()->id())->get();

        return view('tags.index', compact('tags'));
    }

    public function create()
    {
        return view('tags.create');
    }

    public function store()
    {
        $data = request()->validate([
            'name' => ['required', 'string', 'max:255', 'min:3'],
        ]);

        Tag::create([
            'name' => $data['name'],
            'user_id' => auth()->id(),
        ]);

        return redirect()->route('tags.index');
    }

    public function destroy(Tag $tag)
    {
        $tag->delete();

        return redirect()->route('tags.index');
    }
}
