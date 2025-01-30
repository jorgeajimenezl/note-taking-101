<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ErrorResponse;
use App\Http\Resources\TagResource;

class TagController extends Controller
{
    public function index()
    {
        return TagResource::collection(auth()->user()->tags);
    }

    public function store()
    {
        $data = request()->validate([
            'name' => ['required', 'string'],
        ]);

        $tag = auth()->user()->tags()->create($data);

        return response()->json(new TagResource($tag), 201);
    }

    public function destroy(int $tag)
    {
        $tag = auth()->user()->tags()->find($tag);

        if ($tag === null) {
            return ErrorResponse::notFound();
        }

        $tag->delete();

        return response()->json(null, 204);
    }
}
