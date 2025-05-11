<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\RefreshToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
  public function login(Request $request)
  {
    $credentials = $request->only('email', 'password');

    if (!$token = Auth::attempt($credentials)) {
      return response()->json(['error' => 'Unauthorized'], 401);
    }

    $user = Auth::user();

    // Генерируем refresh токен
    $refreshToken = Str::uuid();
    RefreshToken::create([
      'user_id' => $user->id,
      'token' => $refreshToken,
      'expires_at' => Carbon::now()->addDays(30),
    ]);

    return response()->json([
      'access_token' => $token,
      'refresh_token' => $refreshToken,
      'token_type' => 'bearer',
      'expires_in' => Auth::factory()->getTTL() * 60,
    ]);
  }

  public function refresh(Request $request)
  {
    $refreshToken = $request->input('refresh_token');

    $tokenRecord = RefreshToken::where('token', $refreshToken)
      ->where('expires_at', '>', now())
      ->first();

    if (!$tokenRecord) {
      return response()->json(['error' => 'Invalid or expired refresh token'], 401);
    }

    $user = $tokenRecord->user;

    // Удаляем старый refresh токен
    $tokenRecord->delete();

    // Выдаём новый access и refresh токен
    $newAccessToken = Auth::login($user);
    $newRefreshToken = Str::uuid();

    RefreshToken::create([
      'user_id' => $user->id,
      'token' => $newRefreshToken,
      'expires_at' => Carbon::now()->addDays(30),
    ]);

    return response()->json([
      'access_token' => $newAccessToken,
      'refresh_token' => $newRefreshToken,
      'token_type' => 'bearer',
      'expires_in' => Auth::factory()->getTTL() * 60,
    ]);
  }
}
