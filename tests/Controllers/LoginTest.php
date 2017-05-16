<?php
namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class LoginTest extends TestCase
{
  use DatabaseTransactions;
  public function testLoginSuccess()
  {
    $data = [
      'email' => 'admin@example.com',
      'password' => '123456',
    ];
    $response = $this->post('/api/login', $data);
    $response->assertStatus(200);
    $response->assertJsonFragment([
      'name' => 'admin',
      'email' => 'admin@example.com',
    ]);
  }
}
