<?php

namespace App\Http\Controllers\Api\Authentication;

use App\Http\Controllers\Controller;
use App\Http\Requests\Authentication\CreateAgentRequest;
use App\Http\Requests\Authentication\LoginRequest;
use App\Http\Requests\Authentication\RegisterRequest;
use App\Http\Requests\Authentication\ResendSetupLinkRequest;
use App\Http\Requests\Authentication\SetPasswordRequest;
use App\Http\Resources\Authentication\UserResource;
use App\Services\Authentication\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct(private AuthService $auth) {}

    public function register(RegisterRequest $request): JsonResponse
    {
        $result = $this->auth->register($request->validated());

        return response()->json([
            'message' => 'Registration successful.',
            'data'    => [
                'user'  => new UserResource($result['user']),
                'token' => $result['token'],
            ],
        ], 201);
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $result = $this->auth->login($request->validated());

        return response()->json([
            'message' => 'Login successful.',
            'data'    => [
                'user'  => new UserResource($result['user']),
                'token' => $result['token'],
            ],
        ]);
    }

    public function me(Request $request): JsonResponse
    {
        return response()->json([
            'data' => new UserResource($this->auth->me($request->user())),
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $this->auth->logout($request->user());

        return response()->json(['message' => 'Logged out.']);
    }

    public function refresh(Request $request): JsonResponse
    {
        $result = $this->auth->refresh($request->user());

        return response()->json([
            'message' => 'Token refreshed.',
            'data'    => [
                'user'  => new UserResource($result['user']),
                'token' => $result['token'],
            ],
        ]);
    }

    public function createAgent(CreateAgentRequest $request): JsonResponse
    {
        $user = $this->auth->createAgent($request->validated());

        return response()->json([
            'message' => 'Agent created. Set-password email sent.',
            'data'    => new UserResource($user),
        ], 201);
    }

    public function setPassword(SetPasswordRequest $request): JsonResponse
    {
        $user = $this->auth->setPassword(
            $request->input('token'),
            $request->input('password')
        );

        return response()->json([
            'message' => 'Password set successfully. You can now log in.',
            'data'    => new UserResource($user),
        ]);
    }

    public function resendSetupLink(ResendSetupLinkRequest $request): JsonResponse
    {
        $this->auth->resendSetupLink($request->input('email'));

        return response()->json(['message' => 'Setup link sent.']);
    }
}