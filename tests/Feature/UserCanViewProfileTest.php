<?php

namespace Tests\Feature;

use App\Post;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserCanViewProfileTest extends TestCase
{
    use RefreshDatabase;
    
    public function test_a_user_can_view_user_profiles()
    {
        $this->withoutExceptionHandling();
        $this->actingAs($user = factory(User::class)->create(), 'api');
        $post = factory(Post::class)->create();

        $response = $this->get('/api/users/'.$user->id);

        $response->assertOk()
            ->assertJson([
                'data' => [
                    'type' => 'users',
                    'user_id' => $user->id,
                    'attributes' => [
                        'name' => $user->name,
                    ]
                ],
                'links'=> [ 'self' => url('/users/'.$user->id) ]
            ]);
    }

    public function test_a_user_can_fetch_posts_for_a_profile()
    {
        $this->withoutExceptionHandling();
        $this->actingAs($user = factory(User::class)->create(), 'api');
        $post = factory(Post::class)->create(['user_id' => $user->id]);

        $response = $this->get('/api/users/'.$user->id.'/posts');

        $response->assertOk()
            ->assertJson([
                'data' => [
                    [
                        'data' => [
                            'type' => 'posts',
                            'post_id' => $post->id,
                            'attributes' => [
                                'body' => $post->body,
                                'image' => $post->image,
                                'posted_at' => $post->created_at->diffForHumans(),
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
                            ]
                        ],
                        'links'=> [ 'self' => url('/posts/'.$post->id) ]
                    ]
                ],
                'links' => ['self'=> url('/posts')]

            ]);

    }
}
