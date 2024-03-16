<?php

namespace App\Controllers;

use App\Models\ProductCategoryType;
use App\Models\ProductConditionType;

class ProductController extends Controller
{

    public function getAllProductConditionType(){
        try {

        $productConditionType = ProductConditionType::all();        
        return response()->json(['status' => 'success', 'conditionTypes' => $productConditionType], 200);

        }catch(\Exception $e){
            return response()->json(['status' => 'error', 'message' => 'Error al obtener los tipos de condicion del producto'], 500);
        }
    }

    
    public function getAllProductCategories(){
        try {

        $productCategoryType = ProductCategoryType::all();
        return response()->json(['status' => 'success', 'categories' => $productCategoryType], 200);

        }catch(\Exception $e){
            return response()->json(['status' => 'error', 'message' => 'Error al obtener las categorias de los productos'], 500);
        }
    }

}
