<?php

namespace App\Controllers;

class SalesController extends Controller
{
    public function index()
    {
        response()->json([
            'message' => 'SalesController@index output'
        ]);
    }
}
