<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    // USERS
    public function userIndex(Request $request) {
        $users = DB::select('SELECT * FROM users');
        foreach($users as $user) {
            unset($user->PASSWORD);
        }
        return response()
            ->json([
                'results'=>$users,
                'aggregated'=>false,
            ]);
    }

    public function editUserInfo(Request $request) {
        $users = DB::select('SELECT * FROM users WHERE user_id=?', [$request->user_id]);
        foreach($users as $user) {
            unset($user->PASSWORD);
        }
        return response()
            ->json([
                'results' => $users
            ]);
    }

    public function updateUser(Request $request) {
        $isInputFull = true;
        foreach($request->all() as $input) {
            if($input == '' || $input == NULL) $isInputFull = false;
        }

        // Update if input is full
        if($isInputFull) {
            $updated = DB::update('UPDATE users SET email=?, first_name=?, last_name=?, address=? WHERE user_id=?', [
                $request->email,
                $request->first_name,
                $request->last_name,
                $request->address,
                (int)$request->user_id
            ]);
            return response()
                ->json([
                    'updated'=>true
                ]);
        }
        return response()
            ->json([
                'message'=>'Inputs are not valid'
            ], 422);
    }

    public function deleteUser(Request $request) {
        $deleted = DB::delete('DELETE FROM users WHERE user_id=?', [$request->id]);
        $users = DB::select('SELECT * FROM users');
        foreach($users as $user) {
            unset($user->PASSWORD);
        }
        return response()
            ->json([
                'message'=>'User Deleted',
                'user'=>$deleted,
                'results'=>$users,
            ]);
    }

    // ACCOUNTS

    // TRANSACTIONS
}
