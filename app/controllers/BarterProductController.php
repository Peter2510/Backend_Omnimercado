<?php

namespace App\Controllers;

use App\Models\BarterProduct;
use App\Models\Restriction;
use App\Models\BarterProductCategory;
use App\Models\ImageBarterProduct;
use App\Models\User;
use Leaf\FS;


class BarterProductController extends Controller
{

    function getAvailableBarterProducts()
    {

        try {
            $barterProducts = BarterProduct::select('id_producto_trueque', 'titulo', 'equivalente_moneda_local','equivalente_moneda_virtual', 'fecha_publicacion')
                ->where('id_estado', 2)
                ->orderBy('fecha_publicacion', 'asc')
                ->get();

            foreach ($barterProducts as $barterProduct) {
                $image = $this->barterProductImage($barterProduct->id_producto_trueque);
                if ($image) {
                    $barterProduct['images'] = $image;
                }
            }

            return response()->json(['status' => 'success', 'barterProducts' => $barterProducts], 200);
        } catch (\Exception $e) {
            echo $e;
            return response()->json(['status' => 'error', 'message' => 'Error al obtener los intercambios disponibles'], 500);
        }
    }

    function getUserBarterProducts($user_id)
    {

        try {
            $barterProducts = BarterProduct::select('id_producto_trueque', 'titulo', 'equivalente_moneda_local', 'equivalente_moneda_virtual' , 'id_estado', 'fecha_publicacion')
                ->where('id_publicador', $user_id)
                ->orderBy('fecha_publicacion', 'desc')
                ->get();

            foreach ($barterProducts as $barterProduct) {
                $image = $this->barterProductImage($barterProduct->id_producto_trueque);
                if ($image) {
                    $barterProduct['images'] = $image;
                }
            }

            return response()->json(['status' => 'success', 'barterProducts' => $barterProducts], 200);
        } catch (\Exception $e) {
            echo $e;
            return response()->json(['status' => 'error', 'message' => 'Error al obtener los intercambios disponibles'], 500);
        }
    }


    function getUserAvailableBarterProducts($user_id)
    {
        try {

            $barterProducts = BarterProduct::select('id_producto_trueque', 'titulo', 'precio_moneda_virtual', 'fecha_publicacion')
                ->where('id_estado_producto', 3)
                ->where('id_publicador', '!=', $user_id)
                ->orderBy('fecha_publicacion', 'asc')
                ->get();

            foreach ($barterProducts as $barterProduct) {
                $image = $this->barterProductImage($barterProduct->id_producto_trueque);
                if ($image) {
                    $barterProduct['images'] = $image;
                }
            }

            return response()->json(['status' => 'success', 'barterProducts' => $barterProducts], 200);
        } catch (\Exception $e) {
            echo $e;
            return response()->json(['status' => 'error', 'message' => 'Error al obtener los intercambios disponibles'], 500);
        }
    }

    function barterProductImage($id)
    {

        $image_url = ImageBarterProduct::select('url_imagen')->where('id_producto_trueque', $id)->first();

        if ($image_url) {

            $pathImage = _env("STORAGE_BARTER_PRODUCTS_IMAGES") . $image_url->url_imagen;

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

    function barterProductImages($id)
    {

        $image_urls = ImageBarterProduct::select('url_imagen')->where('id_producto_trueque', $id)->get();

        $url_list = $image_urls->pluck('url_imagen')->toArray();
        $image_list = [];

        foreach ($url_list as $url) {
            $pathImage = _env("STORAGE_BARTER_PRODUCTS_IMAGES") . $url;

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

    public function createBarterProduct()
    {

        try {
            $barterProduct = new BarterProduct;
            $barterProduct->titulo = app()->request()->get('title');
            $barterProduct->equivalente_moneda_local = app()->request()->get('virtual_coin_equivalent');
            $barterProduct->equivalente_moneda_virtual = app()->request()->get('local_currency_equivalent');
            $barterProduct->descripcion_producto = app()->request()->get('description');
            $barterProduct->descripcion_solicitud = app()->request()->get('request_description');
            $barterProduct->fecha_publicacion = date("Y-m-d");
            $barterProduct->id_condicion = app()->request()->get('condition'); //usado, nuevo 
            $barterProduct->id_publicador = app()->request()->get('id_user');
            $active_to_publish = app()->request()->get('active_to_publish');

            $numberPublications = BarterProduct::where('id_publicador', app()->request()->get('id_user'))->where('id_estado',2)->count();
            $minimumPublications = Restriction::where('id_restriccion', 1)->first()->cantidad;

            if ($numberPublications >= $minimumPublications) {
                $barterProduct->id_estado = 2; //Disponible
                //update state user
                $user = User::findOrFail(app()->request()->get('id_user'));
                $user->activo_publicar = 1;
                $user->save();
                $message = 'Publicación realizada';
            } else{
                $barterProduct->id_estado = 1; //Oculto
                $message = 'Publicacion pendiente de aprobacion';
            }

            $barterProduct->save();
            $idBarterProduct = $barterProduct->getKey();

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
                    $image = new ImageBarterProduct;
                    $image->id_producto_trueque = $idBarterProduct;
                    $fileDetails['name'] = $idBarterProduct . "_" . $count . "." . $extension[1];
                    $image->url_imagen = $fileDetails['name'];
                    FS::uploadFile($fileDetails, _env("STORAGE_BARTER_PRODUCTS_IMAGES"));
                    $count++;
                    $image->save();
                }
            } else {
                return response()->json(['status' => 'error', 'message' => 'Error al crear la publicacion de intercambio'], 500);
            }

            //Save categories
            $categories = app()->request()->get('id_categories');

            foreach ($categories as $categoryId) {
                $category = new BarterProductCategory;
                $category->id_producto_trueque = $idBarterProduct;
                $category->id_tipo_categoria = $categoryId;
                $category->save();
            }

            return response()->json(['status' => 'success', 'message' => $message], 200);

        } catch (\Exception $e) {
            echo $e;
            return response()->json(['status' => 'error', 'message' => 'Error al crear la publicacion de intercambio'], 500);
        }
    }
    
    public function barterProductsPendingApproval()
    {
        try {
            $barterProducts = BarterProduct::select('id_producto_trueque', 'titulo', 'fecha_publicacion')
                ->where('id_estado', 1)
                ->orderBy('fecha_publicacion', 'asc')
                ->get();

            foreach ($barterProducts as $barterProduct) {
                $image = $this->barterProductImage($barterProduct->id_producto_trueque);
                if ($image) {
                    $barterProduct['images'] = $image;
                }
            }

            return response()->json(['status' => 'success', 'barterProducts' => $barterProducts], 200);
        } catch (\Exception $e) {
            echo $e;
            return response()->json(['status' => 'error', 'message' => 'Error al obtener los intercambios pendientes de aprobacion'], 500);
        }
    }

    public function getBarterProductById($id)
    {
        try {

            $barterProduct = BarterProduct::join('tipo_condicion as tc', 'tc.id_tipo_condicion', '=', 'producto_trueque.id_condicion')
            ->select('producto_trueque.*', 'tc.nombre as condicion')
            ->findOrFail($id);

            $barterProduct->User;

            $barterProduct->User->makeHidden([
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

            $images = $this->barterProductImages($barterProduct->id_producto_trueque);
            $barterProduct->images = $images;

            return response()->json(['status' => 'success', 'barterProduct' => $barterProduct], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['status' => 'error', 'message' => 'Publicación no encontrada'], 404);
        } catch (\Exception $e) {
            echo $e;
            return response()->json(['status' => 'error', 'message' => 'Error al obtener el publicación'], 500);
        }
    }

    function setBarterProductToPending($id)
    {
        try {
            $barterProduct = BarterProduct::findOrFail($id);
            $barterProduct->id_estado = 1;
            $barterProduct->save();
            return response()->json(['status' => 'success', 'message' => 'Publicación actualizada a pendiente'], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['status' => 'error', 'message' => 'Publicación no encontrado'], 404);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Error al cambiar el estado de la publicación'], 500);
        }
    }

    function setBarterProductToAvailable($id)
    {
        try {
            $barterProduct = BarterProduct::findOrFail($id);
            $barterProduct->id_estado = 2;
            $barterProduct->save();
            return response()->json(['status' => 'success', 'message' => 'Publicación actualizada a disponible'], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['status' => 'error', 'message' => 'Publicación no encontrado'], 404);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Error al cambiar el estado de la publicacion'], 500);
        }
    }

    
    function setBarterProductToRealized($id)
    {
        try {
            $barterProduct = BarterProduct::findOrFail($id);
            $barterProduct->id_estado = 3;
            $barterProduct->save();
            return response()->json(['status' => 'success', 'message' => 'Publicación actualizada a realizado'], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['status' => 'error', 'message' => 'Publicación no encontrada'], 404);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Error al cambiar el estado de la publicación'], 500);
        }
    }

    function setBarterProductToRejected($id)
    {
        try {
            $barterProduct = BarterProduct::findOrFail($id);
            $barterProduct->id_estado = 4;
            $barterProduct->save();
            return response()->json(['status' => 'success', 'message' => 'Publicación actualizada a rechazado'], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['status' => 'error', 'message' => 'Publicación no encontrada'], 404);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Error al cambiar el estado de la publicación'], 500);
        }
    }

    function setBarterProductToDeleted($id)
    {
        try {
            $barterProduct = BarterProduct::findOrFail($id);
            $barterProduct->id_estado = 5;
            $barterProduct->save();
            return response()->json(['status' => 'success', 'message' => 'Publicación actualizada a eliminado'], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['status' => 'error', 'message' => 'Publicación no encontrada'], 404);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Error al cambiar el estado de la publicación'], 500);
        }
    }

}
