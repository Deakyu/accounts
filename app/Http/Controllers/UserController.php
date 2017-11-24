<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function index(Request $req) {
        $user_id = $req->user_id != '' ? 'user_id = ?' : '';
        $a = $req->user_id;
        $first_name = $req->first_name != '' ? 'first_name LIKE ?' : '';
        $b = $req->first_name;
        $last_name = $req->last_name != '' ? 'last_name LIKE ?' : '';
        $c = $req->last_name;
        $address = $req->address != '' ? 'address LIKE ?' : '';
        $d = $req->address;
        $sort_by = $req->sort_by != '' ? 'ORDER BY user_id ' . $req->sort_by : '';
        $e = $req->sort_by;
        $paramArr = compact('a', 'b', 'c', 'd', 'e');
        $arr = compact("user_id", "first_name", "last_name", "address", "sort_by");
        $params = [];
        $where = '';
        $anyWhere = false;
        
        foreach($arr as $item => $value) {
            if($item != 'sort_by' && $value !='') {
                $anyWhere = true;
                $where .= ' AND ' . $value;
            }
            if($item == 'sort_by' && $value !='') {
                $where .= ' ' . $value;
            }
        }
        $where = ltrim($where, ' AND ');

        foreach($paramArr as $item => $value) {
            if($value != '') {
                if($item == "a") {
                    array_push($params, $value);
                } else {
                    if($item != 'e') {
                        array_push($params, '%' . $value . '%');
                    }
                }
            }
        }

        if($anyWhere) {
            $users = DB::select('SELECT * FROM users WHERE ' . $where, $params);
        } else {
            $users = DB::select('SELECT * FROM users ' . $sort_by);
        }

        return response()
            ->json(['users' => $users]);
    }
}
