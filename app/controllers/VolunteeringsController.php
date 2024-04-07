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


    

    function getAvailableVolunteerings()
    {

        try {
            $volunteerings = Volunteering::select('id_voluntariado', 'titulo', 'fecha_publicacion')
                ->where('id_estado', 2)
                ->orderBy('fecha_publicacion', 'asc')
                ->get();

            foreach ($volunteerings as $volunteering) {
                $image = $this->volunteeringImage($volunteering->id_voluntariado);
                if ($image) {
                    $volunteering['images'] = $image;
                }
            }

            return response()->json(['status' => 'success', 'volunteerings' => $volunteerings], 200);
        } catch (\Exception $e) {
            echo $e;
            return response()->json(['status' => 'error', 'message' => 'Error al obtener los voluntariados disponibles'], 500);
        }
    }

    function getUserVolnteerings($user_id)
    {

        try {
            $volunteerings = Volunteering::select('id_voluntariado', 'titulo', 'precio_moneda_virtual', 'id_estado_voluntariado', 'fecha_publicacion')
                ->where('id_publicador', $user_id)
                ->orderBy('fecha_publicacion', 'desc')
                ->get();

            foreach ($volunteerings as $volunteering) {
                $image = $this->volunteeringImage($volunteering->id_voluntariado);
                if ($image) {
                    $volunteering['images'] = $image;
                }
            }

            return response()->json(['status' => 'success', 'volunteerings' => $volunteerings], 200);
        } catch (\Exception $e) {
            echo $e;
            return response()->json(['status' => 'error', 'message' => 'Error al obtener los voluntariados disponibles'], 500);
        }
    }


    function getUserAvailableVolunteering($user_id)
    {
        try {

            $volunteerings = Volunteering::select('id_voluntariado', 'titulo', 'fecha_publicacion')
                ->where('id_estado', 2)
                ->where('id_publicador', '!=', $user_id)
                ->orderBy('fecha_publicacion', 'asc')
                ->get();

            foreach ($volunteerings as $volunteering) {
                $image = $this->volunteeringImage($volunteering->id_voluntariado);
                if ($image) {
                    $volunteering['images'] = $image;
                }
            }

            return response()->json(['status' => 'success', 'volunteerings' => $volunteerings], 200);
        } catch (\Exception $e) {
            echo $e;
            return response()->json(['status' => 'error', 'message' => 'Error al obtener los voluntariados disponibles'], 500);
        }
    }

    function volunteeringImage($id)
    {

        $image_url = ImageVolunteering::select('url_imagen')->where('id_voluntariado', $id)->first();

        if ($image_url) {

            $pathImage = _env("STORAGE_VOLUNTEERINGS_IMAGES") . $image_url->url_imagen;

            if (file_exists($pathImage)) {

                $imageData = file_get_contents($pathImage);

                $image_data_json = [
                    'type' => FS::extension($pathImage),
                    'image' => base64_encode($imageData)
                ];

                return $image_data_json;
            }
        }

        return null;
    }

    function volunteeringImages($id)
    {

        $image_urls = ImageVolunteering::select('url_imagen')->where('id_voluntariado', $id)->get();

        $url_list = $image_urls->pluck('url_imagen')->toArray();
        $image_list = [];

        foreach ($url_list as $url) {
            $pathImage = _env("STORAGE_VOLUNTEERINGS_IMAGES") . $url;

            if (file_exists($pathImage)) {

                $imageData = file_get_contents($pathImage);

                $image_data_json = [
                    'type' => FS::extension($pathImage),
                    'image' => base64_encode($imageData)
                ];

                $image_list[] = $image_data_json;
            }
        }

        return $image_list;
    }

    public function volunteeringPendingApproval()
    {
        try {
            $volunteerings = Volunteering::select('id_voluntariado', 'titulo', 'fecha_publicacion')
                ->where('id_estado', 1)
                ->orderBy('fecha_publicacion', 'asc')
                ->get();

            foreach ($volunteerings as $volunteering) {
                $image = $this->volunteeringImage($volunteering->id_voluntariado);
                if ($image) {
                    $volunteering['images'] = $image;
                }
            }

            return response()->json(['status' => 'success', 'volunteerings' => $volunteerings], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Error al obtener los voluntariados disponibles'], 500);
        }
    }

    public function getVolunteeringById($id)
    {
        try {

            $volunteering = Volunteering::findOrFail($id);

            $volunteering->User;

            $volunteering->User->makeHidden([
                'fecha_nacimiento',
                'moneda_local_gastada',
                'moneda_local_ganada',
                'cantidad_moneda_virtual',
                'moneda_virtual_ganada',
                'moneda_virtual_gastada',
                'promedio_valoracion',
                'activo_publicar',
                'activo_plataforma',
                'genero',
                'url_imagen'
            ]);

            $images = $this->volunteeringImages($volunteering->id_voluntariado);
            $volunteering->images = $images;

            return response()->json(['status' => 'success', 'volunteering' => $volunteering], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['status' => 'error', 'message' => 'Voluntariado no encontrado'], 404);
        } catch (\Exception $e) {
            echo $e;
            return response()->json(['status' => 'error', 'message' => 'Error al obtener el voluntariado'], 500);
        }
    }

    function setVolunteeringToPending($id)
    {
        try {
            $volunteering = Volunteering::findOrFail($id);
            $volunteering->id_estado = 1;
            $volunteering->save();
            return response()->json(['status' => 'success', 'message' => 'Voluntariado actualizado a pendiente'], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['status' => 'error', 'message' => 'Voluntariado no encontrado'], 404);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Error al cambiar el estado del voluntariado'], 500);
        }
    }

    function setVolunteeringToAvailable($id)
    {
        try {
            $volunteering = volunteering::findOrFail($id);
            $volunteering->id_estado = 2;
            $volunteering->save();
            return response()->json(['status' => 'success', 'message' => 'voluntariado actualizado a disponible'], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['status' => 'error', 'message' => 'voluntariado no encontrado'], 404);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Error al cambiar el estado del voluntariado'], 500);
        }
    }

    
    function setVolunteeringToRealized($id)
    {
        try {
            $volunteering = volunteering::findOrFail($id);
            $volunteering->id_estado = 3;
            $volunteering->save();
            return response()->json(['status' => 'success', 'message' => 'voluntariado actualizado a vendido'], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['status' => 'error', 'message' => 'voluntariado no encontrado'], 404);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Error al cambiar el estado del voluntariado'], 500);
        }
    }

    function setVolunteeringToRejected($id)
    {
        try {
            $volunteering = volunteering::findOrFail($id);
            $volunteering->id_estado = 4;
            $volunteering->save();
            return response()->json(['status' => 'success', 'message' => 'voluntariado actualizado a rechazado'], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['status' => 'error', 'message' => 'voluntariado no encontrado'], 404);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Error al cambiar el estado del voluntariado'], 500);
        }
    }


    function getUserVolunteerings($user_id)
    {
        try {
            $volunteerings = Volunteering::select('id_voluntariado', 'titulo', 'fecha_publicacion','id_estado')
                ->where('id_publicador', $user_id)
                ->orderBy('fecha_publicacion', 'desc')
                ->get();

            foreach ($volunteerings as $volunteering) {
                $image = $this->volunteeringImage($volunteering->id_voluntariado);
                if ($image) {
                    $volunteering['images'] = $image;
                }
            }
            
            return response()->json(['status' => 'success', 'volunteerings' => $volunteerings], 200);
        } catch (\Exception $e) {
            echo $e;
            return response()->json(['status' => 'error', 'message' => 'Error al obtener los voluntariados disponibles'], 500);
        }
    }


}
