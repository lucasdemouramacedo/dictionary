<?php

namespace App\Services;

use App\Exceptions\UserCreationException;
use App\Exceptions\UserNotFoundException;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Hash;

class UserService
{

    /**
     * Create a new User
     */
    public function createUser(array $data): User
    {
        try {
            return User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password'])
            ]);
        } catch (Exception $e) {
            throw new UserCreationException();
        }
    }

    /**
     * Read a User
     */
    public function readUser(string $id): User
    {
        $user = User::find($id);

        if (!$user) {
            throw new UserNotFoundException();
        }

        return $user;
    }
}
