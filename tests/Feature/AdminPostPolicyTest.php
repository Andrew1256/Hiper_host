<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminPostPolicyTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function admin_can_edit_any_post()
    {
        $admin = User::factory()->admin()->create();
        $editor = User::factory()->create();
        $postByEditor = Post::factory()->create(['user_id' => $editor->id]);

        $this->actingAs($admin)
             ->get(route('admin.posts.edit', $postByEditor->id))
             ->assertOk();
    }

    /** @test */
    public function editor_can_only_edit_own_post()
    {
        $editor1 = User::factory()->create();
        $editor2 = User::factory()->create();
        $postByEditor1 = Post::factory()->create(['user_id' => $editor1->id]);

        // Editor 1 can edit own
        $this->actingAs($editor1)
             ->get(route('admin.posts.edit', $postByEditor1->id))
             ->assertOk();

        // Editor 2 cannot edit Editor 1's post
        $this->actingAs($editor2)
             ->get(route('admin.posts.edit', $postByEditor1->id))
             ->assertForbidden();
    }
}
