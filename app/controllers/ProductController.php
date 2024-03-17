<?php

namespace App\Controllers;

use App\Models\ImageProduct;
use App\Models\Product;
use App\Models\ProductCategoryType;
use App\Models\ProductConditionType;
use Leaf\FS;

class ProductController extends Controller
{

    public function getAllProductConditionType()
    {
        try {

            $productConditionType = ProductConditionType::all();
            return response()->json(['status' => 'success', 'conditionTypes' => $productConditionType], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Error al obtener los tipos de condicion del producto'], 500);
        }
    }


    public function getAllProductCategories()
    {
        try {

            $productCategoryType = ProductCategoryType::all();
            return response()->json(['status' => 'success', 'categories' => $productCategoryType], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Error al obtener las categorias de los productos'], 500);
        }
    }

    public function createProductPost()
    {

        try {
            $product = new Product;
            $product->titulo = app()->request()->get('title');
            $product->precio_moneda_local = app()->request()->get('localCurrency');
            $product->precio_moneda_virtual = app()->request()->get('virtualCoin');
            $product->descripcion = app()->request()->get('description');
            $product->id_estado_producto = 2; //Disponible
            $product->fecha_publicacion = date("Y-m-d");
            $product->tipo_condicion = app()->request()->get('condition'); //usado, nuevo 
            $product->id_publicador = app()->request()->get('id_user');
            $product->save();
            $idProducto = $product->getKey();
            
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
                    $image = new ImageProduct;
                    $image->id_producto = $idProducto;
                    $fileDetails['name'] = $idProducto."_".$count.".".$extension[1];
                    $image->url_imagen= $fileDetails['name'];
                    FS::uploadFile($fileDetails, "./images/");
                    $count++;
                    $image->save();
                }
            }else{
                return response()->json(['status' => 'error', 'message' => 'Error al crear la publicacion'], 500);
            }

            //Save categories
            


            return response()->json(['status' => 'success', 'message' => 'Se ha publicación exitosamente'], 200);
        } catch (\Exception $e) {
            echo $e;
            return response()->json(['status' => 'error', 'message' => 'Error al crear la publicacion'], 500);
        }
    }
}
