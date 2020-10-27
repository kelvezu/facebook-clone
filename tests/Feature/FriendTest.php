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

  /**
   * Note: Dont add the $this->withoutExceptionHandling(); if you are asserting an error.
   */
  public function test_only_valid_friend_request_can_be_accepted()
  {
    // $this->withoutExceptionHandling();

    $another_user = factory(User::class)->create();

    $response = $this->actingAs($another_user,'api')
        ->post('/api/friend-request-response',[
            'user_id' => 112,
            'status' => 1,
        ])
        ->assertStatus(404);

    $friendRequest = Friend::first();
    $this->assertNull($friendRequest);

    $response->assertJson([
        'errors' => [
            'code' => '404' ,
            'title' => 'Friend request not found!',
            'detail' => 'Unable to request the friend request with the given information.'
        ]   
    ]);
  }

  public function test_only_the_recipient_can_accept_the_friend_request()
  {
    // $this->withoutExceptionHandling();

    $this->actingAs($user = factory(User::class)->create(), 'api');
    $another_user = factory(User::class)->create();

    $this->post('/api/friend-request', [
        'friend_id' => $another_user->id
    ])
    ->assertOk();
    
    $friendRequest = Friend::first();

    $this->assertNotNull($friendRequest);

    $response = $this->actingAs(factory(User::class)->create(),'api')
    ->post('/api/friend-request-response',[
        'user_id' => $user->id,
        'status' => 1,
    ])
    ->assertNotFound();

    $this->assertNull($friendRequest->confirmed_at);
    $this->assertNull($friendRequest->status);
    $response->assertJson([
        'errors' => [
            'code' => '404' ,
            'title' => 'Friend request not found!',
            'detail' => 'Unable to request the friend request with the given information.'
        ]   
    ]);


  }
  /**
   *  Created test for validation.
   *    - created a response
   *    - fetched the content of that response.
   *    - Created ValidationErrorException.
   *    - added the customize error response and added the meta and the correct error message by laravel.
   *    - then create a render in Handler.php to automatically throw the customize ValidationErrorException.
   */

  public function test_friend_id_is_required_for_a_friend_request()
  { 
    // $this->withoutExceptionHandling();
    $response = $this->actingAs($user = factory(User::class)->create(), 'api')
                ->post('/api/friend-request', [
                    'friend_id' => ''
                ]);
    
    $responseString = json_decode($response->getContent(), true);

    $this->assertArrayHasKey('friend_id', $responseString['errors']['meta']);
  }

  public function test_a_friend_request_reponse_must_have_an_id_and_status()
  {
    $user = factory(User::class)->create();

    $response = $this->actingAs($user,'api')
        ->post('/api/friend-request-response',[
            'user_id' => '',
            'status' => '',
        ])
        ->assertStatus(422);
    
    $responseString = json_decode($response->getContent(), true);   
    $this->assertArrayHasKey('user_id', $responseString['errors']['meta']);
    $this->assertArrayHasKey('status', $responseString['errors']['meta']);
  }

  public function test_a_friendship_is_retrieved_when_fetching_the_profile()
  {
      $this->withoutExceptionHandling();
      $this->actingAs($user = factory(User::class)->create(), 'api');
      $another_user = factory(User::class)->create();
      $friendRequest = Friend::create([
          'user_id' => $user->id,
          'friend_id' =>  $another_user->id,
          'confirmed_at' => now()->subDay(),
          'status' => 1,
      ]);

    //   $this->assertNotNull($friendRequest);

      $this->get('/api/users/'.$another_user->id)
        ->assertStatus(200)
        ->assertJson([
            'data' => [
                'attributes' => [
                    'friendship' => [
                        'data' => [
                            'friend_request_id' => $friendRequest->id,
                            'attributes' => [
                                'confirmed_at' => '1 day ago',
                            ]
                        ]

                    ],
                ]
            ],
            
        ]);
  }

  public function test_an_inverse_friendship_is_retrieved_when_fetching_the_profile()
  {
      $this->withoutExceptionHandling();
      $this->actingAs($user = factory(User::class)->create(), 'api');
      $another_user = factory(User::class)->create();
      $friendRequest = Friend::create([
          'friend_id' => $user->id,
          'user_id' =>  $another_user->id,
          'confirmed_at' => now()->subDay(),
          'status' => 1,
      ]);

      $this->get('/api/users/'.$another_user->id)
        ->assertStatus(200)
        ->assertJson([
            'data' => [
                'attributes' => [
                    'friendship' => [
                        'data' => [
                            'friend_request_id' => $friendRequest->id,
                            'attributes' => [
                                'confirmed_at' => '1 day ago',
                            ]
                        ]

                    ],
                ]
            ],
            
        ]);


  }

}
