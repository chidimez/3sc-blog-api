<?php

namespace Tests\Feature;


use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostApiTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a user for authenticated routes
        $this->user = User::factory()->create();

        // Seed some posts
        Post::factory()->count(5)->create(['published_at' => now()->subDay()]);
    }

    /**
     * Test that anyone (unauthenticated) can retrieve a paginated list of published posts.
     *
     * Checks that the response status is 200 OK and
     * the JSON structure contains pagination fields with an array of posts.
     *
     * @return void
     */
    public function anyone_can_view_paginated_posts()
    {
        $response = $this->getJson('/api/posts?per_page=2&page=1');

        $response->assertStatus(200)
             ->assertJsonStructure([
                 'data' => [
                     '*' => [
                         'id', 'title', 'content', 'author', 'published_at',
                         'scheduled_at', 'created_at', 'updated_at'
                     ]
                 ],
                 'meta' => [
                     'current_page',
                     'last_page',
                     'per_page',
                     'total',
                 ],
                 'links' => [
                     'first',
                     'last',
                     'prev',
                     'next',
                 ]
             ]);
    }

    /**
     * Test that anyone can view a single published post by its ID.
     *
     * Validates response status 200 and checks the JSON response
     * includes the post's ID and title.
     *
     * @return void
     */
    public function anyone_can_view_a_single_post()
    {
        $post = Post::first();

        $response = $this->getJson("/api/posts/{$post->id}");

        $response->assertStatus(200)
                 ->assertJsonFragment([
                     'id' => $post->id,
                     'title' => $post->title,
                 ]);
    }

    /**
     * Test that unauthenticated users cannot create a new post.
     *
     * Expects a 401 Unauthorized response when posting without auth.
     *
     * @return void
     */
    public function unauthenticated_user_cannot_create_post()
    {
        $response = $this->postJson('/api/posts', [
            'title' => 'Test Post',
            'content' => 'Some content',
            'author' => 'John Doe',
        ]);

        $response->assertStatus(401);
    }

    /**
     * Test that an authenticated user can create a new post.
     *
     * Sends valid post data with authentication and expects
     * a 201 Created response with the new post data in JSON.
     * Also verifies the database contains the new post.
     *
     * @return void
     */
    public function authenticated_user_can_create_post()
    {
        $postData = [
            'title' => 'New Post',
            'content' => 'Post content here',
            'author' => 'John Doe',
            'scheduled_at' => now()->addDay()->toDateTimeString(),
        ];

        $response = $this->actingAs($this->user, 'sanctum')
                         ->postJson('/api/posts', $postData);

        $response->assertStatus(201)
                 ->assertJsonFragment(['title' => 'New Post']);
        
        $this->assertDatabaseHas('posts', ['title' => 'New Post']);
    }

    /**
     * Test that an authenticated user can update an existing post.
     *
     * Updates the post's title and expects a 200 OK response
     * with the updated title in JSON.
     * Verifies the database record is updated accordingly.
     *
     * @return void
     */
    public function authenticated_user_can_update_post()
    {
        $post = Post::factory()->create(['published_at' => now()->subDay()]);

        $updateData = ['title' => 'Updated Title'];

        $response = $this->actingAs($this->user, 'sanctum')
                         ->putJson("/api/posts/{$post->id}", $updateData);

        $response->assertStatus(200)
                 ->assertJsonFragment(['title' => 'Updated Title']);

        $this->assertDatabaseHas('posts', ['id' => $post->id, 'title' => 'Updated Title']);
    }

     /**
     * Test that an authenticated user can delete a post.
     *
     * Sends a delete request for an existing post and expects
     * a 204 No Content response.
     * Confirms the post no longer exists in the database.
     *
     * @return void
     */
    public function authenticated_user_can_delete_post()
    {
        $post = Post::factory()->create(['published_at' => now()->subDay()]);

        $response = $this->actingAs($this->user, 'sanctum')
                         ->deleteJson("/api/posts/{$post->id}");

        $response->assertStatus(204);

        $this->assertDatabaseMissing('posts', ['id' => $post->id]);
    }
}
