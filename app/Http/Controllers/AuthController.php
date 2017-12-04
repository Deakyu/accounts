<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Hash;

class AuthController extends Controller
{

    public function register(Request $request) {
        // Create user
            // Check if the user already exists
            $user = DB::select('SELECT * FROM users WHERE email = ?', [$request->email]);
                // if exists, send error
                if($user) {
                    return response()
                        ->json([
                            'The email is already taken'
                        ], 422);
                }

                // if not, create a user
                else {
                    DB::insert('INSERT INTO users (email, first_name, last_name, `address`, `password`) VALUES (?, ?, ?, ?, ?)', [
                        $request->email,
                        $request->first_name,
                        $request->last_name,
                        $request->address,
                        bcrypt($request->password)
                    ]);
                    return response()
                        ->json([
                            'registered'=>true
                        ]);
                }
    }

    public function login(Request $request) {
        $user = DB::select('SELECT * FROM users WHERE email=?', [$request->email]);
        if($user && Hash::check($request->password, $user[0]->PASSWORD)) {
            $isAdmin = $user[0]->EMAIL == 'lee5250@fredonia.edu' ? 1 : null;
            return response()
                ->json([
                    'authenticated' => true,
                    'api_token' => str_random(60),
                    'user_id' => $user[0]->USER_ID,
                    'is_admin' => $isAdmin,
                ]);
        }

        return response()
            ->json([
                'email' => ['Credentials did not match']
            ], 422);
    }

    public function logout(Request $request) {
        return response()
            ->json([
                'logged_out'=>true
            ]);
    }
}
