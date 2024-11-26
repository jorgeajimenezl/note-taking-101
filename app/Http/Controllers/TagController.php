<?php

namespace App\Http\Controllers;

class TagController extends Controller
{
    public function index()
    {
        return view('tags.index');
    }
}
