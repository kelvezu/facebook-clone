<?php

namespace Tests\Feature;

use App\Post;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LikesTest extends TestCase
{
    use RefreshDatabase;


    /**
     * 
     */
   public function test_a_user_can_like_post()
   {
    $this->withoutExceptionHandling();
    $this->actingAs($user = factory(User::class)->create(), 'api');
    $post = factory(Post::class)->create(['id' => 123]);

    $response = $this->post('/api/posts/'.$post->id.'/like')
        ->assertOk();
    
    $this->assertCount(1, $user->likedPosts);
    $response->assertJson([
        'data' => [
            [
                'data' => [
                    'type' => 'likes',
                    'like_id' => 1,
                    'attributes' => [],
                ],
                'links' => [
                    'self' => url('/posts/123')
                ]
            ]
        ],
        'links' => [
            'self' => url('/posts')
        ] 
    ]);

   }

   public function test_posts_are_returned_with_likes()
   {
    $this->withoutExceptionHandling();
    $this->actingAs($user = factory(User::class)->create(), 'api');
    $post = factory(Post::class)->create(['id' => 123, 'user_id' => $user->id]);

    $this->post('/api/posts/'.$post->id.'/like');

    $response = $this->get('/api/posts/')
        ->assertOk()
        ->assertJson([
            'data' => [
                [
                    'data' => [
                        'type' => 'posts',
                        'attributes' => [
                            'likes' => [
                                'data' => [
                                    [
                                        'data' => [
                                            'type' => 'likes',
                                            'like_id' => 1,
                                            'attributes' => [],
                                        ]   
                                    ],
                                ],
                                'like_count' => 1,
                                'user_likes_post' => true,
                            ], 
                            
                        ]
                    ]
                ]
            ]
        ]);

   }
}
