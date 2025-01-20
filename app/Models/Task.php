<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Task extends Model implements HasMedia
{
    /** @use HasFactory<\Database\Factories\TaskFactory> */
    use HasFactory;

    use InteractsWithMedia;

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

    public function getUserRole(User $user): ?string
    {
        if ($this->author_id === $user->id) {
            $role = 'owner';
        } else {
            $role = Contributor::firstWhere(['task_id' => $this->id, 'user_id' => $user->id])?->role;
        }

        return $role;
    }
}
