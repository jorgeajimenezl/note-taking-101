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
}
