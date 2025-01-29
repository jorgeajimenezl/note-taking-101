<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ApiTokenController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validateWithBag('storeToken', [
            'name' => ['required'],
        ]);

        $token = $request->user()->createToken($validated['name']);

        return back()->with('token', $token->plainTextToken);
    }

    public function destroy(Request $request, $token)
    {
        $request->user()->tokens()->where('id', $token)->delete();

        return back();
    }

    public function index(Request $request)
    {
        return view('tokens.index', [
            'tokens' => $request->user()->tokens,
        ]);
    }
}
