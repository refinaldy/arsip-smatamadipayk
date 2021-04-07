<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    public function register()
    {
        $validator = Validator::make(request()->all(), [
            'username' => 'required|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages());
        }

        $user = User::create(
            [
                'username' => request('username'),
                'email' => request('email'),
                'password' => Hash::make(request('password'))
            ]

        );

        return response()->json(['messages' => 'Sucessfully Register']);

        //generate token, auto login, or just a success message
    }
}
