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
        $post = factory(Post::class, 2)->create();

        $response = $this->get('/api/posts');

        $response->assertOk()
            ->assertJson([
                'data' => [
                    [
                        'data' => [
                            'type' => 'posts',
                            'post_id' => $post->first()->id,
                            'attributes' => [
                                'body' => $post->first()->body,
                            ]
                        ],
                        'links' => [
                            'self' => url('/posts/'.$post->first()->id)
                        ],
                    ],
                    [
                        'data' => [
                            'type' => 'posts',
                            'post_id' => $post->last()->id,
                            'attributes' => [
                                'body' => $post->last()->body,
                            ]
                        ],
                        'links' => [
                            'self' => url('/posts/'.$post->last()->id)
                        ],
                    ]
                ],
                'links' => [
                    'self' =>url('/posts')
                ]
            ]);

    }
}
