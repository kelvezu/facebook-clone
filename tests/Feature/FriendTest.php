<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FriendTest extends TestCase
{
    use RefreshDatabase;

  public function test_a_user_can_send_a_friend_request()
  {
    $this->withoutExceptionHandling();

    $this->actingAs($user = factory(User::class)->create(), 'api');
    $another_user = factory(User::class)->create();
    $res = $this->post('/api/friend-request', [
        'friend_id' => $another_user->id
    ])
    ->assertOk();

    $friendRequest = \App\Friend::first();

    $this->assertNotNull($friendRequest);
    $this->assertEquals($user->id, $friendRequest->user_id);
    $res->assertJson([
        'data' => [
            'type' => 'friends',
            'friend_request_id' => $friendRequest->id,
            'attributes' => [
                'confirmed_at' => null
            ]
        ],
        'links' => ['self' => url('/users/'.$another_user->id)]
    ]);

  }

  public function test_only_valid_users_can_be_added()
  {
    // $this->withoutExceptionHandling();

    $this->actingAs($user = factory(User::class)->create(), 'api');

    $res = $this->post('/api/friend-request', [
        'friend_id' => 123
    ])
    ->assertStatus(404);

    $friendRequest = \App\Friend::first();
    $res->assertJson([
        'errors' => [
            'code' => '404' ,
            'title' => 'User not found!',
            'detail' => 'Unable to locate the user with the given information.'
        ]
    ]);

  }
}
