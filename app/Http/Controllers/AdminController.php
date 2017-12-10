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
    public function accountIndex(Request $request) {
        $accounts = DB::select('SELECT * FROM accounts');
        return response()
            ->json([
                'results'=>$accounts,
                'aggregated'=>false,
            ]);
    }

    public function accountCreate(Request $request) {
        $userId = $request->user_id;
        $balance = $request->balance;

        $isInputFull = true;
        foreach($request->all() as $input) {
            if($input == '' || $input == NULL) $isInputFull = false;
        }
        if($balance < 0) {
            return response()
                ->json([
                    'message'=>'Balance cannot be set below 0 by default'
                ], 422);
        }
        if(!$isInputFull) {
            return response()
                ->json([
                    'message' => 'Some input missing'
                ], 422);
        }
        $created = DB::insert('INSERT INTO accounts (user_id, balance, date_created) VALUES (?, ?, ?)', [
            (int)$userId,
            (float)$balance,
            date('Y-m-d')
        ]);
        return response()
            ->json([
                'created' => true
            ]);
    }
    public function editAccountInfo(Request $request) {
        $accounts = DB::select('SELECT * FROM accounts WHERE account_id=?', [$request->account_id]);

        return response()
            ->json([
                'results' => $accounts
            ]);
    }

    public function updateAccount(Request $request) {
        $isInputFull = true;
        foreach($request->all() as $input) {
            if($input == '' || $input == NULL) $isInputFull = false;
        }

        if($isInputFull) {
            $updated = DB::update('UPDATE accounts SET user_id = ?, balance = ? WHERE account_id = ?', [
                (int)$request->user_id,
                $request->balance,
                (int)$request->account_id,
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

    public function deleteAccount(Request $request) {
        $deleted = DB::delete('DELETE FROM accounts WHERE account_id=?', [$request->id]);
        $accounts = DB::select('SELECT * FROM accounts');
        return response()
            ->json([
                'message'=>'Account Deleted',
                'account'=>$deleted,
                'results'=>$accounts,
            ]);
    }

    // TRANSACTIONS
    public function transactionIndex(Request $request) {
        $transactions = DB::select('SELECT * FROM transactions');
        foreach($transactions as &$transaction) {
            switch($transaction->TYPE) {
                case 'W':
                    $transaction->TYPE = 'Withdrawal';
                    break;
                case 'D':
                    $transaction->TYPE = 'Deposit';
                    break;
                case 'T':
                    $transaction->TYPE = 'Transfer';
                    break;
            }
        }
        unset($transaction);
        return response()
            ->json([
                'results'=>$transactions,
                'aggregated'=>false,
            ]);
    }
    public function transactionCreate(Request $request) {
        $isInputFull = true;
        foreach($request->all() as $input) {
            if($input == '' || $input == NULL) $isInputFull = false;
        }
        if(!$isInputFull) {
            return response()
                ->json([
                    'message' => 'Some input missing'
                ], 422);
        }
        $from = $request->source;
        $to = $request->destination;
        $amount = $request->amount;
        $type = $request->type;
        $originAccountBalance = DB::select('SELECT balance FROM accounts WHERE account_id=?', [$from]);
        $destinationAccountBalance = DB::select('SELECT balance FROM accounts WHERE account_id=?', [$to]);

        // Check for negative balances
        if($type == 'W' || $type == 'T') {
            if($originAccountBalance[0]->balance < $amount) {
                return response()
                    ->json([
                        'message'=>'Insufficient balance to make the transaction'
                    ], 422);
            }
        }
        // Balance Sufficient so go ahead and update the balances
        if($type == 'T') {
            $success = DB::update('UPDATE accounts SET balance = ? WHERE account_id = ?', [$originAccountBalance[0]->balance - $amount, $from]);
            $success = DB::update('UPDATE accounts SET balance = ? WHERE account_id = ?', [$destinationAccountBalance[0]->balance + $amount, $to]);
        }else if($type == 'W') {
            $success = DB::update('UPDATE accounts SET balance=? WHERE account_id=?', [$originAccountBalance[0]->balance - $amount, $from]);
        }else if($type == 'D') {
            $success = DB::update('UPDATE accounts SET balance=? WHERE account_id=?', [$originAccountBalance[0]->balance + $amount, $from]);
        }
        else {
            $success = 0;
        }


        $created = DB::insert('INSERT INTO transactions(type, amount, date, source, destination) VALUES (?, ?, ?, ?, ?)', [
            $type,
            (float)$amount,
            date('Y-m-d'),
            (int)$from,
            (int)$to,
        ]);
        return response()
            ->json([
                'created' => true
            ]);
    }

    public function accountsWithUsers(Request $request) {
        $results = DB::select('
            SELECT a.account_id, u.email, u.first_name
            FROM accounts a, users u
            WHERE a.user_id = u.user_id
        ');
        return response()
            ->json([
                'results'=>$results,
                'aggregated'=>false,
            ]);
    }
}
