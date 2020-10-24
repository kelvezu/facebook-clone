<?php

namespace Tests\Feature;

use App\Post;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\User;

class RetrievePostsTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_user_can_retrieve_post(){
        $this->withoutExceptionHandling();
        $this->actingAs($user = factory(User::class)->create(), 'api');
        $post = factory(Post::class, 2)->create(['user_id' => $user->id]);

        $response = $this->get('/api/posts');

        $response->assertOk()
            ->assertJson([
                'data' => [
                    [
                        'data' => [
                            'type' => 'posts',
                            'post_id' => $post->last()->id,
                            'attributes' => [
                                'body' => $post->last()->body,
                                'image' => $post->last()->image,
                                'posted_at'=> $post->last()->created_at->diffForHumans(),
                            ]
                        ],
                        'links' => [
                            'self' => url('/posts/'.$post->last()->id)
                        ],
                    ],
                    [
                        'data' => [
                            'type' => 'posts',
                            'post_id' => $post->first()->id,
                            'attributes' => [
                                'body' => $post->first()->body,
                                'image' => $post->first()->image,
                                'posted_at'=> $post->first()->created_at->diffForHumans(),
                            ]
                        ],
                        'links' => [
                            'self' => url('/posts/'.$post->first()->id)
                        ],
                    ]
                ],
                'links' => [
                    'self' =>url('/posts')
                ]
            ]);

    }

    public function test_a_user_can_only_retrieve_their_post()
    {
        $this->withoutExceptionHandling();
        $this->actingAs($user = factory(User::class)->create(), 'api');
        $post = factory(Post::class)->create();

        $response = $this->get('/api/posts');

        $response->assertOk()
            ->assertExactJson([
                'data' => [],
                'links' => [
                    'self' => url('/posts')
                ]
            ]);
    }
}
