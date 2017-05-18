<?php

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

use Illuminate\Database\Query\Builder;
use App\Category;

class CategoryTest extends TestCase
{
  use DatabaseTransactions;

  protected $tokenExpired = "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOjEsImlzcyI6Imh0dHA6XC9cL3dlYi1hcGkuZGV2XC9hcGlcL2xvZ2luIiwiaWF0IjoxNDk1MDg5NDg2LCJleHAiOjE0OTUwODk1NDYsIm5iZiI6MTQ5NTA4OTQ4NiwianRpIjoiS0kwV29wUFllS1lsV3ozdSJ9.eDl41r6g53daSq8Gme2PWcEz_Xbui945_Hb6h0kSy-M";

  protected $tokenInvalid = "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOjEsImlzcyI6Imh0dHA6XC9cL3dlYi1hcGkuZGV2XC9hcGlcL2xvZ2luIiwiaWF0IjoxNDk1MDg4NTg5LCJleHAiOjE0OTUwOTIxODksIm5iZiI6MTQ5NTA4ODU4OSwianRpIjoiQURMcDczODZEdXRDNUlQbyJ9.38_-xsIfvVZwLqlKIUIRez5fG56jxkv98-VaHbY9QYE1";

  public function getToken()
  {
    $credentials = ['email' => 'admin@example.com', 'password' => '123456'];
    $token = JWTAuth::attempt($credentials);
    return $token;
  }

  public function testIndexSuccess()
  {
    $token = $this->getToken();
    $this->assertNotNull($token);

    $res = $this->get('/api/categories', ['HTTP_Authorization' => 'Bearer '.$token]);
    $res->assertStatus(200);
    $res->assertJsonStructure([
      'meta' => [
        'status',
        'total',
        'total-pages',
        'per-page',
        'count',
      ],
      'categories' => [
        '*' => [
        'id',
        'name',
        'description',
        'created_at',
        'updated_at',
        ]
      ],
      'links' => [
        'self',
        'first',
        'prev',
        'next',
        'last',
      ]
    ]);
  }

  public function testIndexFailure()
  {
    $uri = '/api/categories';
    // invalid token

    $res = $this->get($uri, ['HTTP_Authorization' => 'Bearer '.$this->tokenInvalid]);
    $res->assertStatus(400);
    $res->assertExactJson([
      'error' => 'token_invalid'
    ]);

    // refreshing application
    $this->refreshApplication();

    // token not provided
    $res = $this->get($uri, ['HTTP_Authorization' => 'Bearer ']);
    $res->assertStatus(400);
    $res->assertExactJson([
      'error' => 'token_not_provided'
    ]);

    // refreshing application
    $this->refreshApplication();

    // token expired

    $res = $this->get($uri, ['HTTP_Authorization' => 'Bearer '.$this->tokenExpired]);
    $res->assertStatus(401);
    $res->assertExactJson([
      'error' => 'token_expired'
    ]);
  }

  public function testStoreSuccess()
  {
    $token = $this->getToken();
    $this->assertNotNull($token);

    $credentials = [
      'name' => 'Category Test',
      'description' => 'Category Test',
      'updated_at' => null,
    ];
    $res = $this->post('/api/categories', $credentials, ['HTTP_Authorization' => 'Bearer '.$token]);
    $res->assertStatus(201);
    $res->assertExactJson([
      'status' => 201,
      'message' => 'Created',
    ]);
  }
  public function testShowByIDSuccess()
  {
    $token = $this->getToken();
    $this->assertNotNull($token);

    // initial data
    $catg = factory(Category::class)->create([
      'name' => 'Drinks',
      'created_at' => \Carbon\Carbon::now()->subMonth(),
    ]);

    $this->assertEquals(1,count($catg));

    //get ID lastest
    $cateLastest = Category::where('name', 'Drinks')->firstOrFail();

    // test show() method
    $res = $this->get('/api/categories/'.$cateLastest->id,[
        'HTTP_Authorization' => 'Bearer '.$token
      ]);

    $res->assertStatus(200);
    $res->assertJsonFragment([
      'id' => $cateLastest->id,
      'name' => $cateLastest->name,
      'description' => $cateLastest->description,
    ]);
  }

  public function testUpdateSuccess()
  {
    $token = $this->getToken();

    $this->assertNotNull($token);

    // initial data
    $catg = factory(Category::class)->create([
      'name' => 'Foods',
      'created_at' => \Carbon\Carbon::now()->subMonth(),
    ]);

    $this->assertEquals(1,count($catg));

    $cateLastest = Category::where('name', 'Foods')->firstOrFail();

    // test update method
    $data = [
      'name' => 'Hats',
      'description' => 'Modify Category'
    ];
    $res = $this->put('/api/categories/'.$cateLastest->id, $data, [
      'HTTP_Authorization' => 'Bearer '. $token
    ]);

    $res->assertStatus(200);
    $res->assertJsonFragment([
      'status' => 200,
      'message' => 'OK',
    ]);
  }

  public function testDeleteSuccess()
  {
    $token = $this->getToken();

    $this->assertNotNull($token);

    // initial data
    $catg = factory(Category::class)->create([
      'name' => 'Hats',
      'created_at' => \Carbon\Carbon::now()->subMonth(),
    ]);

    $this->assertEquals(1,count($catg));

    $cateLastest = Category::where('name', 'Hats')->firstOrFail();

    // test destroy() method
    $res = $this->delete('/api/categories/'.$cateLastest->id, [], [
      'HTTP_Authorization' => 'Bearer '. $token
    ]);

    $res->assertStatus(200);
    $res->assertExactJson([
      'status' => 200,
      'message' => 'Deleted'
    ]);
  }
}
