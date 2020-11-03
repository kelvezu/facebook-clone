<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GetAuthUserTest extends TestCase
{
    use RefreshDatabase;
   public function test_auth_user_can_be_fetched()
   {
       $this->withoutExceptionHandling();
       $this->actingAs($user = factory(User::class)->create(), 'api');
       $response = $this->get('/api/auth-user')
                ->assertOk()
                ->assertJson([
                    'data' => [
                    'type' => 'users',
                    'user_id' => $user->id,
                      'attributes' => [
                          'name' => $user->name,
                      ]
                    ],
                    'links' => [
                        'self' => url('/users/'. $user->id)
                    ]
                ]);

   }
}
