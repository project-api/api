<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\User;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Http\Request;
use JWTAuth;
class LoginController extends Controller
{
  /*
  |--------------------------------------------------------------------------
  | Login Controller
  |--------------------------------------------------------------------------
  |
  | This controller handles authenticating users for the application and
  | redirecting them to your home screen. The controller uses a trait
  | to conveniently provide its functionality to your applications.
  |
  */

  use AuthenticatesUsers;

  /**
  * Where to redirect users after login.
  *
  * @var string
  */
  protected $redirectTo = '/home';

  /**
  * Create a new controller instance.
  *
  * @return void
  */
  public function __construct()
  {
    $this->middleware('guest', ['except' => 'logout']);
  }

  public function authenticate(Request $request)
  {
    // grab credentials from the request
    $credentials = $request->only('email', 'password');

    $email = $request->email;
    $user = User::where('email', $email)->first();
    // validate token
    try {
      $token = JWTAuth::attempt($credentials);
      // attempt to verify the credentials and create a token for user
      if(!$token) {
        return response()->json(['error' => 'invalid_credentials', 'status' => 401], 401);
      }
    } catch (JWTException $e) {
      // something went wrong whilst attempting to encode the tokken
      return response()->json(['error' => 'could_not_create_token', 'status' => 500], 500);
    }
    $status = 200;
    // all good so return the token
    return response()->json(compact('token', 'status', 'user'));
  }
}
