<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class ApiTokenTest extends TestCase
{
    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_index_displays_user_tokens()
    {
        $token = $this->user->createToken('Test Token');

        $response = $this->actingAs($this->user)
            ->get(route('tokens.index'));

        $response->assertStatus(200);
        $response->assertViewIs('tokens.index');
        $response->assertViewHas('tokens');
        $response->assertSee('Test Token');
    }

    public function test_guest_cannot_view_tokens()
    {
        $response = $this->get(route('tokens.index'));
        $response->assertRedirect(route('login'));
    }

    public function test_store_creates_new_token()
    {
        $response = $this->actingAs($this->user)
            ->post(route('tokens.store'), [
                'name' => 'New API Token',
            ]);

        $response->assertSessionHas('token');
        $response->assertRedirect();
        $this->assertDatabaseHas('personal_access_tokens', [
            'name' => 'New API Token',
            'tokenable_id' => $this->user->id,
        ]);
    }

    public function test_store_requires_name()
    {
        $response = $this->actingAs($this->user)
            ->post(route('tokens.store'), [
                'name' => '',
            ]);

        $response->assertSessionHasErrors(['name'], null, 'storeToken');
    }

    public function test_guest_cannot_create_token()
    {
        $response = $this->post(route('tokens.store'), [
            'name' => 'Test Token',
        ]);

        $response->assertRedirect(route('login'));
    }

    public function test_destroy_deletes_token()
    {
        $token = $this->user->createToken('Test Token');
        $tokenId = $token->accessToken->id;

        $response = $this->actingAs($this->user)
            ->delete(route('tokens.destroy', $tokenId));

        $response->assertRedirect();
        $this->assertDatabaseMissing('personal_access_tokens', [
            'id' => $tokenId,
        ]);
    }

    public function test_cannot_delete_other_users_token()
    {
        $otherUser = User::factory()->create();
        $token = $otherUser->createToken('Other Token');
        $tokenId = $token->accessToken->id;

        $response = $this->actingAs($this->user)
            ->delete(route('tokens.destroy', $tokenId));

        $this->assertDatabaseHas('personal_access_tokens', [
            'id' => $tokenId,
        ]);
    }

    public function test_guest_cannot_delete_token()
    {
        $token = $this->user->createToken('Test Token');
        $tokenId = $token->accessToken->id;

        $response = $this->delete(route('tokens.destroy', $tokenId));

        $response->assertRedirect(route('login'));
        $this->assertDatabaseHas('personal_access_tokens', [
            'id' => $tokenId,
        ]);
    }

    public function test_destroy_handles_invalid_token()
    {
        $response = $this->actingAs($this->user)
            ->delete(route('tokens.destroy', 999));

        $response->assertRedirect();
    }
}
