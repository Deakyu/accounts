<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QueryController extends Controller
{
    public function index(Request $request) {
        $tables = $request->tables;
        $conditions = $request->conditions;
        $fields = $request->fields;
        $joins = $request->joins;

        if(count($tables) < 1) {
            return response()
                ->json([
                    'bad_query' => ['No tables Selected']
                ], 422);
        }

        foreach($fields as $field=>$value) {
            $found = false;
            foreach($tables as $table) {
                if($field == $table) $found = true;
            }
            if(!$found) $fields[$field] = [];
        }
        
        $updatedFields = [];
        foreach($fields as $field=>$value) {
            $updatedFields[$field] = implode(', ', $fields[$field]);
            if($updatedFields[$field] == '') unset($updatedFields[$field]);
        }
        $updatedFields = implode(', ', $updatedFields);

        $tables = implode(', ', $tables);

        $binders = [];
        foreach($conditions as &$condition) {
            if($condition["field"] != '' && $condition["operator"] != '' && $condition["value"] != '') {
                $binders[] = $condition["value"];
                $condition = $condition["field"] . ' ' . $condition["operator"] . ' ?';
            } else {
                return response()
                    ->json([
                        'condition' => ["Condition not set up correctly"]
                    ], 422);
            }
        }
        unset($condition);
        $conditions = implode(' AND ', $conditions);
        $conditions = $conditions == '' ? '' : ' WHERE ' . $conditions;

        foreach($joins as &$join) {
            if($join["field1"] != '' && $join["field2"] != '') {
                $join = $join['field1'] .  ' = ' . $join['field2'];
            } else {
                $join = '';
            }
        }
        unset($join);

        if($joins != '') $joins = implode(' AND ', $joins);

        // $joins = $conditions == '' ? ' WHERE ' . $joins : ' AND ' . $joins;
        if($joins != '') {
            $joins = $conditions == '' ? ' WHERE ' . $joins : ' AND ' . $joins;
        }

        $results = DB::select('SELECT ' . $updatedFields . ' FROM ' . $tables . $conditions . $joins, $binders);

        return response()
            ->json([
                'results' => $results,
                'conditions' => $conditions,
                'fields'=>$updatedFields,
                'binders'=>$binders,
                'tables'=>$tables,
            ]);


    }

    public function raw(Request $request) {
        $arr = explode(' ', $request->statement);
        $which = $arr[0];
        $results = [];
        $message = "";
        $noReturnValue = true;
        switch($which) {
            case 'select':
                $results = DB::select($request->statement);
                $message = "Query Executed";
                $noReturnValue = false;
                break;
            case 'insert':
                DB::insert($request->statement);
                $message = "Insertion Complete";
                break;
            case 'update':
                DB::update($request->statement);
                $message = "Update Complete";
                break;
            case 'delete':
                DB::delete($request->statement);
                $message = "Deletion complete";
                break;
            default:
                return response()
                    ->json([
                        'rawError' => ["Something went wrong with the statement"]
                    ], 422);
                break;
        }

        return response()
            ->json([
                'results'=>$results,
                'message'=>$message,
                'noReturnValue'=>$noReturnValue,
            ]);
    }

    public function accounts(Request $request) {
        if($request->orderBy != '') {
            $asc_desc = ' ' . $request->asc_desc;
            $orderBy = ' ORDER BY ' . $request->orderBy;
        } else {
            $orderBy = '';
            $asc_desc = '';
        }
        if($request->agg != '') {
            $agg = $request->agg;
            $fields = " " . $agg . " ";
            $asc_desc = '';
            $orderBy = '';
            $aggregated = true;
        } else {
            $fields = ' * ';
            $aggregated = false;
        }

        $accounts = DB::select('SELECT' . $fields . 'FROM accounts WHERE user_id = ?' . $orderBy . $asc_desc, [$request->user_id]);
        foreach($accounts as $account) {
            unset($account->USER_ID);
        }

        return response()
            ->json([
                'results' => $accounts,
                'aggregated' => $aggregated,
            ]);
    }

    public function transactions(Request $request) {
        if($request->orderBy != '') {
            $asc_desc = ' ' . $request->asc_desc;
            $orderBy = ' ORDER BY ' . $request->orderBy;
        } else {
            $orderBy = '';
            $asc_desc = '';
        }
        if($request->agg != '') {
            $agg = $request->agg;
            $fields = " " . $agg . " ";
            $asc_desc = '';
            $orderBy = '';
            $aggregated = true;
        } else {
            $fields = ' t.* ';
            $aggregated = false;
        }

        // $transactions = DB::select('SELECT' . $fields . 'FROM transactions WHERE source = ? OR destination = ?' . $orderBy . $asc_desc, [$request->user_id, $request->user_id]);
        $transactions = DB::select("
            SELECT {$fields}
            FROM transactions t, users u, accounts a
            WHERE u.user_id = a.user_id
            AND u.user_id = ?
            AND (t.source = a.account_id OR t.destination = a.account_id)
            {$orderBy}{$asc_desc}", [$request->user_id]);

        return response()
            ->json([
                'results' => $transactions,
                'aggregated' => $aggregated,
            ]);
    }
}
