<?php

namespace Tests\Feature;

use App\User;
use App\Friend;
use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

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

    $friendRequest = Friend::first();
    $res->assertJson([
        'errors' => [
            'code' => '404' ,
            'title' => 'User not found!',
            'detail' => 'Unable to locate the user with the given information.'
        ]
    ]);

  }
  
  /**
   * This test will test if the user can accept the friend request.
   *    - created a post request for friend request. Send friend request to another_user.
   *    - then as another_user, accept the pending friend request. 
   *    - pass the status = 1
   *    - give the proper response after accepting the request.
   */

  public function test_friend_request_can_be_accepted()
  {
    $this->withoutExceptionHandling();

    $this->actingAs($user = factory(User::class)->create(), 'api');
    $another_user = factory(User::class)->create();

    $this->post('/api/friend-request', [
        'friend_id' => $another_user->id
    ])
    ->assertOk();

    $response = $this->actingAs($another_user,'api')
        ->post('/api/friend-request-response',[
            'user_id' => $user->id,
            'status' => 1,
        ])
        ->assertOk();

    $friendRequest = Friend::first();
    // dd($friendRequest);
    $this->assertNotNull($friendRequest->confirmed_at);
    $this->assertInstanceOf(Carbon::class, $friendRequest->confirmed_at);
    $this->assertEquals(now()->startOfSecond(), $friendRequest->confirmed_at);
    $this->assertEquals(1, $friendRequest->status);
    $response->assertJson([
        'data' => [
            'type' => 'friends',
            'friend_request_id' => $friendRequest->id,
            'attributes' => [
                'confirmed_at' => $friendRequest->confirmed_at->diffForHumans()
            ]
        ],
        'links' => ['self' => url('/users/'.$another_user->id)]
    ]);
  }
}
