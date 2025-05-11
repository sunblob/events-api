<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthController extends Controller
{
  public function login(Request $request)
  {
    $request->validate([
      'email' => 'required|email',
      'password' => 'required|string',
    ]);

    $user = User::where('email', $request->email)->first();
    if (!$user || !Hash::check($request->password, $user->password)) {
      return response()->json(['error' => 'Неверные email или пароль'], 401);
    }

    $token = JWTAuth::fromUser($user);
    return response()->json([
      'token' => $token,
      'user' => $user,
    ]);
  }

  public function logout(Request $request)
  {
    // JWT не требует logout на сервере, но можно инвалидировать токен
    try {
      JWTAuth::invalidate(JWTAuth::getToken());
      return response()->json(['message' => 'Вы вышли из системы']);
    } catch (JWTException $e) {
      return response()->json(['error' => 'Не удалось выйти из системы'], 500);
    }
  }
}