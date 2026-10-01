<?php

namespace App\Services\Authentication;

use App\Mail\Authentication\SetPasswordMail;
use App\Models\Authentication\User;
use App\Repositories\Contracts\Authentication\UserRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AuthService
{
    public function __construct(
        private UserRepositoryInterface $users,
    ) {}

    public function register(array $data): array
    {
        return DB::transaction(function () use ($data) {
            $user = $this->users->create([
                'name'      => $data['name'],
                'email'     => $data['email'],
                'password'  => Hash::make($data['password']),
                'phone'     => $data['phone'] ?? null,
                'user_type' => 'customer',
                'status'    => 'active',
            ]);

            $user->assignRole('customer');

            $token = $user->createToken('auth_token')->plainTextToken;

            return ['user' => $user->fresh('roles.permissions'), 'token' => $token];
        });
    }

    public function login(array $credentials): array
    {
        $user = $this->users->findByEmail($credentials['email']);

        if (!$user || !Hash::check($credentials['password'], (string) $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        if ($user->status === 'suspended') {
            throw ValidationException::withMessages(['email' => ['Your account is suspended.']]);
        }
        if ($user->status === 'pending') {
            throw ValidationException::withMessages(['email' => ['Please set your password first.']]);
        }
        if ($user->status === 'inactive') {
            throw ValidationException::withMessages(['email' => ['Your account is inactive.']]);
        }

        // Single-device login — remove this line if you want multi-device
        $user->tokens()->delete();

        $token = $user->createToken('auth_token')->plainTextToken;

        return ['user' => $user->fresh('roles.permissions'), 'token' => $token];
    }

    public function logout(User $user): void
    {
        $user->currentAccessToken()?->delete();
    }

    public function me(User $user): User
    {
        return $user->fresh('roles.permissions');
    }

    public function refresh(User $user): array
    {
        $user->currentAccessToken()?->delete();
        $token = $user->createToken('auth_token')->plainTextToken;

        return ['user' => $user->fresh('roles.permissions'), 'token' => $token];
    }

    public function createAgent(array $data): User
    {
        return DB::transaction(function () use ($data) {
            $token = Str::random(64);

            $user = $this->users->create([
                'name'                      => $data['name'],
                'email'                     => $data['email'],
                'password'                  => null,
                'phone'                     => $data['phone'] ?? null,
                'user_type'                 => 'agent',
                'status'                    => 'pending',
                'password_setup_token'      => $token,
                'password_setup_expires_at' => now()->addHours(48),
            ]);

            $user->assignRole('agent');

            Mail::to($user->email)->send(new SetPasswordMail($user, $token));

            return $user->fresh('roles.permissions');
        });
    }

    public function setPassword(string $token, string $password): User
    {
        return DB::transaction(function () use ($token, $password) {
            $user = $this->users->findBySetupToken($token);

            if (!$user) {
                throw ValidationException::withMessages([
                    'token' => ['This password setup link is invalid or has expired.'],
                ]);
            }

            return $this->users->update($user, [
                'password'                  => Hash::make($password),
                'password_setup_token'      => null,
                'password_setup_expires_at' => null,
                'status'                    => 'active',
                'email_verified_at'         => now(),
            ]);
        });
    }

    public function resendSetupLink(string $email): void
    {
        $user = $this->users->findByEmail($email);

        if (!$user || $user->user_type !== 'agent' || $user->password !== null) {
            throw ValidationException::withMessages([
                'email' => ['No pending agent account found for this email.'],
            ]);
        }

        $token = Str::random(64);

        $this->users->update($user, [
            'password_setup_token'      => $token,
            'password_setup_expires_at' => now()->addHours(48),
        ]);

        Mail::to($user->email)->send(new SetPasswordMail($user, $token));
    }
}