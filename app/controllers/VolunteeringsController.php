<?php

namespace App\Controllers;

use App\Models\VolunteeringCategoryType;

class VolunteeringsController extends Controller
{
    public function getAllVolunteeringCategories()
    {
        try {

            $volunteeringCategoryType = VolunteeringCategoryType::all();
            return response()->json(['status' => 'success', 'categories' => $volunteeringCategoryType], 200);
        } catch (\Exception $e) {
            echo $e;
            return response()->json(['status' => 'error', 'message' => 'Error al obtener las categorias de los voluntariados'], 500);
        }
    }

}
