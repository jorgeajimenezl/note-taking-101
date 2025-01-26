<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        ray($this->contributors);

        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'completed_at' => $this->completed_at,
            'author' => new UserResource($this->author),
            'contributors' => ContributorResource::collection($this->contributors),
            'tags' => $this->tags->pluck('name')->toArray(),
        ];
    }
}
