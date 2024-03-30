<?php

namespace App\Http\services;

use Illuminate\Support\Facades\Hash;
use App\Models\User;

class RegisterService
{

    /** User Register:
     * @param $validated
     * @return void
     */
    static public function register($validated) {
        return User::create([
                    'name' => $validated['name'],
                    'email' => $validated['email'],
                    'password' => Hash::make($validated['password']),
                ]);
    }
}
