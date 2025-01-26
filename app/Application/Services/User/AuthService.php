<?php

namespace App\Application\Services\User;

use App\Http\Resources\User\UserResource;
use App\Infrastructure\Repositories\User\UserRepository;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthService
{
    protected UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @throws ValidationException
     */
    public function login(array $data): array
    {
        $user = $this->userRepository->findByAttributes(['email' => $data['email']]);

        if (! $user || ! Hash::check($data['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        return [
            'token' => $user->createToken('authToken')->plainTextToken,
            'user' => new UserResource($user)
        ];

    }


}
