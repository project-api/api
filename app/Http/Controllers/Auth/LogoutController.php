<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Tymon\JWTAuth\Exceptions\JWTException;
use JWTAuth;

class LogoutController extends Controller
{
    public function __invoke(Request $request)
    {
      try {
        $token = JWTAuth::getToken();
        if(!$token) {
          return response()->json(['error' => 'invalid_credentials', 'status' => 401], 401);
        }

      } catch (JWTException $e) {
        // something went wrong whilst attempting to encode the tokken
        return response()->json(['error' => 'could_not_get_token', 'status' => 500], 500);
      }
      // // all good so return message
      JWTAuth::invalidate($token);
      return response()->json([
        'status' => 200,
        'message' => 'Logged out',
      ], 200);

    }
}
