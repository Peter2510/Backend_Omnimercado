<?php

namespace App\Controllers;

use App\Models\Product;
use App\Models\Volunteering;
use App\Models\BarterProduct;
use App\Models\ReportBarter;
use App\Models\ReportCategory;

class ReportsController extends Controller
{

    function countPostPendingApproval()
    {
        try {
            $countProducts = Product::where('id_estado_producto', 1)->count();
            $countVolunteerings = Volunteering::where('id_estado', 1)->count();
            $countBarterProducts = BarterProduct::where('id_estado', 1)->count();

            return response()->json([
                'status' => 'success',
                'countProducts' => $countProducts,
                'countVolunteerings' => $countVolunteerings,
                'countBarterProducts' => $countBarterProducts
            ], 200);
        } catch (\Exception $w) {
            return response()->json(['status' => 'success', 'message' => 'Error al obtener cantidad de publicaciones pendientes'], 500);
        }
    }


    //get categories for reports
    function getReportCategories()
    {
        try {
            $categories = ReportCategory::all();
            return response()->json(['status' => 'success', 'categories' => $categories], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Error al obtener categorias de reportes'], 500);
        }
    }

    function createReport()
    {
        try {


            $idProduct = app()->request()->get('id_product');

            //Save categories
            $categories = app()->request()->get('id_categories');

             foreach ($categories as $categoryId) {
                 $report = new ReportBarter;
                 $report->id_categoria_reporte = $categoryId;
                 $report->id_producto_trueque = $idProduct;
                $report->save();
             }
            

            return response()->json(['status' => 'success', 'message' => 'Reporte creado correctamente'], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Error al crear el reporte'], 500);
        }
    }
}
