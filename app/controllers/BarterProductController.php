<?php

namespace App\Controllers;

use App\Models\BarterProduct;
use App\Models\BarterProductCategory;
use App\Models\ImageBarterProduct;
use Leaf\FS;


class BarterProductController extends Controller
{

    function getAvailableBarterProducts()
    {

        try {
            $barterProducts = BarterProduct::select('id_producto_trueque', 'titulo', 'equivalente_moneda_local','equivalente_moneda_virtual', 'fecha_publicacion')
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
            return response()->json(['status' => 'error', 'message' => 'Error al obtener los productos disponibles'], 500);
        }
    }

    function getUserBarterProducts($user_id)
    {

        try {
            $barterProducts = BarterProduct::select('id_producto_trueque', 'titulo', 'equivalente_moneda_local', 'equivalente_moneda_virtual' , 'id_estado_producto', 'fecha_publicacion')
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
            return response()->json(['status' => 'error', 'message' => 'Error al obtener los productos disponibles'], 500);
        }
    }


    function getUserAvailableBarterProducts($user_id)
    {
        try {

            $barterProducts = BarterProduct::select('id_producto_trueque', 'titulo', 'precio_moneda_virtual', 'fecha_publicacion')
                ->where('id_estado_producto', 1)
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
            return response()->json(['status' => 'error', 'message' => 'Error al obtener los productos disponibles'], 500);
        }
    }

    function barterProductImage($id)
    {

        $image_url = ImageBarterProduct::select('url_imagen')->where('id_producto_trueque', $id)->first();

        if ($image_url) {

            $pathImage = _env("STORAGE_PRODUCTS_IMAGES") . $image_url->url_imagen;

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
            $pathImage = _env("STORAGE_PRODUCTS_IMAGES") . $url;

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

            if ($active_to_publish == 1) {
                $barterProduct->id_estado = 1; //Disponible
            } else if ($active_to_publish == 0) {
                $barterProduct->id_estado = 3; //Oculto
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

            return response()->json(['status' => 'success', 'message' => 'Publicación de intercambio realizada'], 200);

        } catch (\Exception $e) {
            echo $e;
            return response()->json(['status' => 'error', 'message' => 'Error al crear la publicacion de intercambio'], 500);
        }
    }
    
    public function barterProductsPendingApproval()
    {
        try {
            $barterProducts = BarterProduct::select('id_producto_trueque', 'titulo', 'fecha_publicacion')
                ->where('id_estado_producto', 3)
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
            return response()->json(['status' => 'error', 'message' => 'Error al obtener los productos disponibles'], 500);
        }
    }
}
