<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    /** @use HasFactory<\Database\Factories\TaskFactory> */
    use HasFactory;

    protected $fillable = [
        'author_id',
        'title',
        'description',
        'completed_at',
    ];

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'tag_task');
    }

    public function contributors()
    {
        return $this->belongsToMany(User::class, 'contributors');
    }

    public function isCompleted(): bool
    {
        return $this->completed_at !== null;
    }

    public function authorized(): void
    {
        $user = auth()->user();
        if ($user->id !== $this->author_id &&
            ! $this->contributors->contains($user)) {
            abort(403);
        }
    }
}
