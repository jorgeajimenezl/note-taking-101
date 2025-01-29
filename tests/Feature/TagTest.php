<?php

namespace Tests\Feature;

use App\Models\Tag;
use App\Models\User;
use Tests\TestCase;

class TagTest extends TestCase
{
    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_index_displays_user_tags()
    {
        $tags = Tag::factory(3)->create(['user_id' => $this->user->id]);
        $otherUserTags = Tag::factory(2)->create(['user_id' => User::factory()->create()->id]);

        $response = $this->actingAs($this->user)
            ->get(route('tags.index'));

        $response->assertStatus(200);
        $response->assertViewIs('tags.index');
        $response->assertViewHas('tags');
        $response->assertSee($tags[0]->name);
        $response->assertDontSee($otherUserTags[0]->name);
    }

    public function test_create_returns_view()
    {
        $response = $this->actingAs($this->user)
            ->get(route('tags.create'));

        $response->assertStatus(200);
        $response->assertViewIs('tags.create');
    }

    public function test_store_creates_new_tag()
    {
        $tagData = ['name' => 'New Test Tag'];

        $response = $this->actingAs($this->user)
            ->post(route('tags.store'), $tagData);

        $response->assertRedirect(route('tags.index'));
        $this->assertDatabaseHas('tags', [
            'name' => 'New Test Tag',
            'user_id' => $this->user->id,
        ]);
    }

    public function test_store_validates_input()
    {
        $response = $this->actingAs($this->user)
            ->post(route('tags.store'), [
                'name' => 'ab', // Too short
            ]);

        $response->assertSessionHasErrors('name');
    }

    public function test_destroy_deletes_tag()
    {
        $tag = Tag::factory()->create(['user_id' => $this->user->id]);

        $response = $this->actingAs($this->user)
            ->delete(route('tags.destroy', $tag));

        $response->assertRedirect(route('tags.index'));
        $this->assertDatabaseMissing('tags', ['id' => $tag->id]);
    }

    public function test_destroy_requires_authorization()
    {
        $otherUser = User::factory()->create();
        $tag = Tag::factory()->create(['user_id' => $otherUser->id]);

        $response = $this->actingAs($this->user)
            ->delete(route('tags.destroy', $tag));

        $response->assertStatus(403);
        $this->assertDatabaseHas('tags', ['id' => $tag->id]);
    }

    public function test_guest_cannot_access_tags()
    {
        $response = $this->get(route('tags.index'));
        $response->assertRedirect(route('login'));

        $response = $this->get(route('tags.create'));
        $response->assertRedirect(route('login'));

        $response = $this->post(route('tags.store'), ['name' => 'Test Tag']);
        $response->assertRedirect(route('login'));

        $user = User::factory()->create();
        $tag = Tag::factory()->create(['user_id' => $user->id]);
        $response = $this->delete(route('tags.destroy', $tag));
        $response->assertRedirect(route('login'));
    }
}
