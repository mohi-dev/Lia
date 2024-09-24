<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller {
	/**
	 * Create a new AuthController instance.
	 */
	public function __construct() {
		$this->middleware('auth:api', [
			'except' => ['login', 'register'],
		]);
	}

	/**
	 * Get a JWT via given credentials.
	 */
	public function register(RegisterRequest $request): JsonResponse {

		$user = User::create([
			'name' => $request->name,
			'email' => $request->email,
			'password' => Hash::make($request->password),
		]);

		event(new Registered($user));

		return $this->authenticate(
			$request->validated()
		);
	}

	/**
	 * Get a JWT via given credentials.
	 */
	public function login(LoginRequest $request): JsonResponse {
		return $this->authenticate(
			$request->validated()
		);
	}

	/**
	 * Get the authenticated User.
	 */
	public function me(): JsonResponse {
		return response()->json(
			auth()->user()
		);
	}

	/**
	 * Log the user out (Invalidate the token).
	 */
	public function logout(): JsonResponse {
		auth()->logout();

		return response()->json([
			'message' => 'auth.logged_out',
		]);
	}

	/**
	 * Refresh a token.
	 */
	public function refresh(): JsonResponse {
		return $this->respondWithToken(
			auth()->refresh()
		);
	}

	/**
	 * Handle an authentication attempt.
	 */
	protected function authenticate($request): JsonResponse {
		if (!$token = auth()->attempt($request)) {
			return response()->json(
				['error' => 'auth.failed'],
				Response::HTTP_UNAUTHORIZED
			);
		}

		return $this->respondWithToken($token);
	}

	/**
	 * Get the token array structure.
	 */
	protected function respondWithToken(string $token): JsonResponse {
		return response()->json([
			'access_token' => $token,
			'token_type' => 'bearer',
			'expires_in' => auth()->factory()->getTTL() * 60,
		]);
	}
}