<?php

namespace Tests\Feature;

use App\Models\Tag;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class TaskTest extends TestCase
{
    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_index_returns_correct_view()
    {
        $response = $this->actingAs($this->user)
            ->get(route('tasks.index'));

        $response->assertStatus(200);
        $response->assertViewIs('tasks.index');
    }

    public function test_create_returns_view_with_tags()
    {
        $tags = Tag::factory(3)->create(['user_id' => $this->user->id]);

        $response = $this->actingAs($this->user)
            ->get(route('tasks.create'));

        $response->assertStatus(200);
        $response->assertViewIs('tasks.create');
        $response->assertViewHas('allTags');
    }

    public function test_store_creates_new_task()
    {
        Storage::fake('local');
        $file = UploadedFile::fake()->create('document.pdf', 1024);

        $tags = Tag::factory(2)->create(['user_id' => $this->user->id]);

        $response = $this->actingAs($this->user)
            ->post(route('tasks.store'), [
                'title' => 'Test Task Title',
                'description' => 'Test description',
                'tags' => $tags->pluck('id')->toArray(),
                'attachments' => [$file],
            ]);

        $response->assertRedirect(route('tasks.index'));
        $this->assertDatabaseHas('tasks', [
            'title' => 'Test Task Title',
            'description' => 'Test description',
            'author_id' => $this->user->id,
        ]);
        $this->assertDatabaseHas('tag_task', [
            'task_id' => Task::first()->id,
            'tag_id' => $tags->first()->id,
        ]);
        Storage::disk('local')->assertExists(Task::first()->attachments()->first()->path);
    }

    public function test_store_validates_input()
    {
        $response = $this->actingAs($this->user)
            ->post(route('tasks.store'), [
                'title' => 'Test', // Too short
                'attachments' => ['not-a-file'],
            ]);

        $response->assertSessionHasErrors(['title', 'attachments.0']);
    }

    public function test_show_displays_task_to_authorized_user()
    {
        $task = Task::factory()->create(['author_id' => $this->user->id]);

        $response = $this->actingAs($this->user)
            ->get(route('tasks.show', $task->id));

        $response->assertStatus(200);
        $response->assertViewIs('tasks.show');
    }

    public function test_show_denies_access_to_unauthorized_user()
    {
        $otherUser = User::factory()->create();
        $task = Task::factory()->create(['author_id' => $otherUser->id]);

        $response = $this->actingAs($this->user)
            ->get(route('tasks.show', $task->id));

        $response->assertStatus(403);
    }

    public function test_toggle_complete_updates_task()
    {
        $task = Task::factory()->create(['author_id' => $this->user->id]);

        $response = $this->actingAs($this->user)
            ->post(route('tasks.toggle-complete', $task), [
                'completed' => true,
            ]);

        $response->assertStatus(204);
        $this->assertNotNull($task->fresh()->completed_at);
    }

    public function test_toggle_complete_requires_authorization()
    {
        $otherUser = User::factory()->create();
        $task = Task::factory()->create(['author_id' => $otherUser->id]);

        $response = $this->actingAs($this->user)
            ->post(route('tasks.toggle-complete', $task), [
                'completed' => true,
            ]);

        $response->assertStatus(403);
    }

    public function test_destroy_deletes_task()
    {
        $task = Task::factory()->create(['author_id' => $this->user->id]);

        $response = $this->actingAs($this->user)
            ->delete(route('tasks.destroy', $task->id));

        $response->assertRedirect(route('tasks.index'));
        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }

    public function test_destroy_requires_authorization()
    {
        $otherUser = User::factory()->create();
        $task = Task::factory()->create(['author_id' => $otherUser->id]);

        $response = $this->actingAs($this->user)
            ->delete(route('tasks.destroy', $task->id));

        $response->assertStatus(403);
        $this->assertDatabaseHas('tasks', ['id' => $task->id]);
    }
}
