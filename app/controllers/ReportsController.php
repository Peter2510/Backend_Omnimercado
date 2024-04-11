<?php

namespace App\Controllers;

use App\Models\Product;
use App\Models\Volunteering;
use App\Models\BarterProduct;

class ReportsController extends Controller
{

    function countPostPendingApproval()
    {
        try {
            $countProducts = Product::where('id_estado_producto', 1)->count();
            $countVolunteerings = Volunteering::where('id_estado', 1)->count();
            $countBarterProducts = BarterProduct::where('id_estado', 1)->count();

            return response()->json(['status' => 'success', 
                    'countProducts' => $countProducts, 
                    'countVolunteerings' => $countVolunteerings,
                    'countBarterProducts' => $countBarterProducts], 200);

        } catch (\Exception $w) {
            return response()->json(['status' => 'success', 'message' => 'Error al obtener cantidad de publicaciones pendientes'], 500);
        }
    }
}
