<?php

namespace App\Controllers;

class BarterProductsController extends Controller
{
    public function index()
    {
        response()->json([
            'message' => 'BarterProductsController@index output'
        ]);
    }
}
