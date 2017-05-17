<?php

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\JsonResponse;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
class LogoutTest extends TestCase
{
    public function testLogoutFailure()
    {
      $uri = '/api/logout/';
      // no send credentials
      $res = $this->post($uri);
      $res->assertStatus(401);
      $res->assertExactJson([
        'error' => 'invalid_credentials',
        'status' => 401,
      ]);
      // wrong token
      $token = 'wrong_token';

      $res = $this->call('POST', $uri,[], [], [], [
        'HTTP_Authorization' => 'Bearer ' . $token
      ]);
      $res->assertStatus(401);
      $res->assertExactJson([
        'error' => 'invalid_credentials',
        'status' => 401,
      ]);
    }
    public function testLogoutSuccess()
    {
      $uri = '/api/logout/';
      $credentials = ['email' => 'user@example.com', 'password' => '123456'];
      $token = JWTAuth::attempt($credentials);

      // check token is not null
      $this->assertNotNull($token);

      //Refreshing The Application
      $this->refreshApplication();

      $res = $this->call('POST', $uri, [], [], [], [
          'HTTP_Authorization' => 'Bearer ' . $token
      ]);

      $res->assertStatus(200)
          ->assertExactJson([
            'status' => 200,
            'message' => 'Logged out',
          ]);

      // re -log out
      $res = $this->call('POST', $uri,[], [], [], [
        'HTTP_Authorization' => 'Bearer ' . $token
      ]);

      $res->assertStatus(401)
          ->assertJsonFragment([
            "error" => "Unauthorized",
            "detail" => "The token has been blacklisted",
            "status" => 401,
          ]);
    }
}
