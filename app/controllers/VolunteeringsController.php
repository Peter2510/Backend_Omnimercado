<?php

namespace App\Controllers;

use App\Models\ImageVolunteering;
use App\Models\Volunteering;
use App\Models\VolunteeringCategory;
use App\Models\VolunteeringCategoryType;
use Leaf\FS;

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


    public function createVolunteering(){

        try {
            $volunteering = new Volunteering;
            $volunteering->codigo_pago = app()->request()->get('codigo_pago');            
            $volunteering->titulo = app()->request()->get('titulo');
            $volunteering->retribucion_moneda_virtual = app()->request()->get('retribucion_moneda_virtual');
            $volunteering->descripcion = app()->request()->get('descripcion');
            $volunteering->lugar = app()->request()->get('lugar');
            $volunteering->fecha = app()->request()->get('fecha');
            $volunteering->hora = app()->request()->get('hora');
            $volunteering->maximo_voluntariados = app()->request()->get('maximo_voluntariados');
            $volunteering->minimo_edad = app()->request()->get('minimo_edad');
            $volunteering->maximo_edad = app()->request()->get('maximo_edad');
            $volunteering->id_publicador = app()->request()->get('id_user');
            $volunteering->descripcion_retribucion = app()->request()->get('descripcion_retribucion');
            $active_to_publish = app()->request()->get('active_to_publish');
            $volunteering->fecha_publicacion = date("Y-m-d");

            if ($active_to_publish == 1) {
                $volunteering->id_estado = 2; //Disponible
            } else if ($active_to_publish == 0) {
                $volunteering->id_estado = 1; //Pendiente
            }

            $volunteering->save();
            $idVolunteering = $volunteering->getKey();

            //Save images
            $file = app()->request()->files("photo");
            

            if ($file != null) {
                $count = 1;
                foreach ($file['name'] as $index => $fileName) {
                    $fileDetails = [
                        'name' => $file['name'][$index],
                        'full_path' => $file['full_path'][$index],
                        'type' => $file['type'][$index],
                        'tmp_name' => $file['tmp_name'][$index],
                        'error' => $file['error'][$index],
                        'size' => $file['size'][$index]
                    ];
                    $extension = explode("/", $fileDetails['type']);
                    $image = new ImageVolunteering;
                    $image->id_voluntariado = $idVolunteering;
                    $fileDetails['name'] = $idVolunteering . "_" . $count . "." . $extension[1];
                    $image->url_imagen = $fileDetails['name'];
                    FS::uploadFile($fileDetails, _env("STORAGE_VOLUNTEERINGS_IMAGES"));
                    $count++;
                    $image->save();
                }
            } else {
                return response()->json(['status' => 'error', 'message' => 'Error al crear la publicacion de intercambio'], 500);
            }

            //Save categories
            $categories = app()->request()->get('id_categories');
            

            foreach ($categories as $categoryId) {
                $category = new VolunteeringCategory;
                $category->id_voluntariado = $idVolunteering;
                $category->id_tipo_categoria = $categoryId;
                $category->save();
            }

            return response()->json(['status' => 'success', 'message' => 'Voluntariado realizado'], 200);

        } catch (\Exception $e) {
            echo $e;
            return response()->json(['status' => 'error', 'message' => $e], 500);
        }
    }

}
