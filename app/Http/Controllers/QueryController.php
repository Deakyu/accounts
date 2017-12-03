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

        $joins = $conditions == '' ? ' WHERE ' . $joins : ' AND ' . $joins;

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
}
