<?php

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
class LoginTest extends TestCase
{
  /**
   * [testLoginFailure case: Login fail]
   * @return [boolean] [check status code and JSON APIs]
   */
  public function testLoginFailure()
  {
    $uri = "/api/login";
    // no send credentials
    $res = $this->post($uri);
    $res->assertStatus(401);
    $res->assertExactJson([
      'error' => 'invalid_credentials',
      'status' => 401,
    ]);

    // user not found
    $res = $this->post($uri, [
      'email' => 'nobody@example.com',
      'password' => '123456',
    ]);
    $res->assertStatus(401);
    $res->assertExactJson([
      'error' => 'invalid_credentials',
      'status' => 401,
    ]);

    // wrong password
    $res = $this->post($uri,[
      'email' => 'admin@example.com',
      'password' => 'wrong',
    ]);
    $res->assertStatus(401);
    $res->assertExactJson([
      'error' => 'invalid_credentials',
      'status' => 401,
    ]);

    // can't create token
    JWTAuth::shouldReceive('attempt')->once()->andThrow(new Tymon\JWTAuth\Exceptions\JWTException('could_not_create_token.', 500));
    $res = $this->post($uri);
    $res->assertStatus(500);
    $res->assertExactJson([
      'error' => 'could_not_create_token',
      'status' => 500,
    ]);
  }

  /**
   * [testLoginSuccess case: login success]
   * @return [boolean] [check status code and JSON APIs]
   */
  public function testLoginSuccess()
  {
    $data = [
      'email' => 'admin@example.com',
      'password' => '123456',
    ];
    $res = $this->post('/api/login', $data);
    $res->assertStatus(200);
    $res->assertJsonFragment([
      'name' => 'admin',
      'email' => 'admin@example.com',
    ]);
  }
}
