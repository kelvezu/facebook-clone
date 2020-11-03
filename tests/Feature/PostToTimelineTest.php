<?php

namespace Tests\Feature;

use App\Post;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class PostToTimelineTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp() :void
    {
        parent::setUp();

        Storage::fake('public');
    }

    public function test_a_user_can_post_a_text_post()
    {
        $this->withoutExceptionHandling();
        $this->actingAs($user = factory(User::class)->create(), 'api');
        $response = $this->post('/api/posts',[
            'body' => 'Testing Body',
        ]);

        $post = Post::first();
        $this->assertCount(1, Post::all());
        $this->assertEquals($user->id, $post->user_id);
        $this->assertEquals('Testing Body', $post->body);
        $response->assertStatus(201);
        $response->assertJson([
            'data' => [
                'type' => 'posts',
                'post_id' => $post->id,
                'attributes' => [
                    'posted_by' => [   
                        'data' => [
                            'type' => 'users',
                            'user_id' => $user->id,
                            'attributes' => [
                                'name' => $user->name,
                            ]
                        ],
                        'links' => [
                            'self' => url('/users/'.$user->id)
                        ]
                        
                    ],
                    'body' => $post->body,
                ]
            ],
            'links' => [
                'self' => url('/posts/'.$post->id)
            ]
        ]);
    }

    public function test_a_user_can_post_a_text_post_with_an_image()
    {
        $this->withoutExceptionHandling();
        $this->actingAs($user = factory(User::class)->create(), 'api');
        $file = UploadedFile::fake()->image('user-post.jpg');
        $response = $this->post('/api/posts',[
            'body' => 'Testing Body',
            'image' => $file,
            'width' => 100,
            'height' => 100
        ]);

        Storage::disk('public')->assertExists('post-images/'.$file->hashName());

        $post = Post::first();
        $this->assertCount(1, Post::all());
        $this->assertEquals($user->id, $post->user_id);
        $this->assertEquals('Testing Body', $post->body);
        $response->assertStatus(201);
        $response->assertJson([
            'data' => [
                'attributes' => [
                  
                    'body' => $post->body,
                    'image' => url('post-images/'.$file->hashName())
                ]
            ],
            'links' => [
                'self' => url('/posts/'.$post->id)
            ]
        ]);
    }
}
