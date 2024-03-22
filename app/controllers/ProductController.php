<?php

namespace App\Controllers;

use App\Models\ImageProduct;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductCategoryType;
use App\Models\ProductConditionType;
use Leaf\FS;

class ProductController extends Controller
{

    // function getUserProducts($user_id){
    // 
    // try {
    // 
    // $product = Product::join('producto_categoria', 'producto.id_producto', '=', 'producto_categoria.id_producto')
    // ->join('tipo_categoria_producto', 'producto_categoria.id_tipo_categoria', '=', 'tipo_categoria_producto.id_tipo_categoria')
    // ->join('estado_producto', 'producto.id_estado_producto', '=', 'estado_producto.id_estado_producto') // Unir la tabla de estados del producto
    // ->join('tipo_condicion', 'producto.tipo_condicion', '=', 'tipo_condicion.id_tipo_condicion') // Unir la tabla de tipos de condición
    // ->where('producto.id_publicador', $user_id) // Filtrar por el ID del usuario
    // ->groupBy('producto.id_producto') // Agrupar por el ID del producto
    // ->selectRaw('producto.*, GROUP_CONCAT(tipo_categoria_producto.nombre) as nombres_categorias, estado_producto.nombre as nombre_estado, tipo_condicion.nombre as nombre_tipo_condicion') // Concatenar los nombres de las categorías y seleccionar los nombres de estado y tipo de condición
    // ->get();
    // 
    // return response()->json(['status' => 'success', 'conditionTypes' => $product], 200);
    // } catch (\Exception $e) {
    // return response()->json(['status' => 'error', 'message' => 'Error al obtener los productos del usuario'], 500);
    // }
    // 
    // }

    function getAvailableProducts()
    {

        try {
            $products = Product::select('id_producto', 'titulo', 'precio_moneda_virtual', 'fecha_publicacion')
                ->where('id_estado_producto', 1)
                ->orderBy('fecha_publicacion', 'asc')
                ->get();

            foreach ($products as $product) {
                $image = $this->productImage($product->id_producto);
                if ($image) {
                    $product['images'] = $image;
                }
            }

            return response()->json(['status' => 'success', 'products' => $products], 200);
        } catch (\Exception $e) {
            echo $e;
            return response()->json(['status' => 'error', 'message' => 'Error al obtener los productos disponibles'], 500);
        }
    }

    function getUserProducts($user_id)
    {

        try {
            $products = Product::select('id_producto', 'titulo', 'precio_moneda_virtual', 'id_estado_producto', 'fecha_publicacion')
                ->where('id_publicador', $user_id)
                ->orderBy('fecha_publicacion', 'desc')
                ->get();

            foreach ($products as $product) {
                $image = $this->productImage($product->id_producto);
                if ($image) {
                    $product['images'] = $image;
                }
            }

            return response()->json(['status' => 'success', 'products' => $products], 200);
        } catch (\Exception $e) {
            echo $e;
            return response()->json(['status' => 'error', 'message' => 'Error al obtener los productos disponibles'], 500);
        }
    }


    function getUserAvailableProducts($user_id)
    {
        try {

            $products = Product::select('id_producto', 'titulo', 'precio_moneda_virtual', 'fecha_publicacion')
                ->where('id_estado_producto', 1)
                ->where('id_publicador', '!=', $user_id)
                ->orderBy('fecha_publicacion', 'asc')
                ->get();

            foreach ($products as $product) {
                $image = $this->productImage($product->id_producto);
                if ($image) {
                    $product['images'] = $image;
                }
            }

            return response()->json(['status' => 'success', 'products' => $products], 200);
        } catch (\Exception $e) {
            echo $e;
            return response()->json(['status' => 'error', 'message' => 'Error al obtener los productos disponibles'], 500);
        }
    }

    function productImage($id)
    {

        $image_url = ImageProduct::select('url_imagen')->where('id_producto', $id)->first();

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

    function productImages($id)
    {

        $image_urls = ImageProduct::select('url_imagen')->where('id_producto', $id)->get();

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
            $product->precio_moneda_virtual = app()->request()->get('virtualCoin');
            $product->descripcion = app()->request()->get('description');

            $product->fecha_publicacion = date("Y-m-d");
            $product->tipo_condicion = app()->request()->get('condition'); //usado, nuevo 
            $product->id_publicador = app()->request()->get('id_user');
            $active_to_publish = app()->request()->get('active_to_publish');

            if ($active_to_publish == 1) {
                $product->id_estado_producto = 1; //Disponible
            } else if ($active_to_publish == 0) {
                $product->id_estado_producto = 3; //Oculto
            }

            $product->save();
            $idProduct = $product->getKey();

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
                    $image->id_producto = $idProduct;
                    $fileDetails['name'] = $idProduct . "_" . $count . "." . $extension[1];
                    $image->url_imagen = $fileDetails['name'];
                    FS::uploadFile($fileDetails, _env("STORAGE_PRODUCTS_IMAGES"));
                    $count++;
                    $image->save();
                }
            } else {
                return response()->json(['status' => 'error', 'message' => 'Error al crear la publicacion'], 500);
            }

            //Save categories
            $categories = app()->request()->get('id_categories');

            foreach ($categories as $categoryId) {
                $category = new ProductCategory;
                $category->id_producto = $idProduct;
                $category->id_tipo_categoria = $categoryId;
                $category->save();
            }

            return response()->json(['status' => 'success', 'message' => 'Publicación realizada'], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Error al crear la publicacion'], 500);
        }
    }
    public function productsPendingApproval()
    {
        try {
            $products = Product::select('id_producto', 'titulo', 'fecha_publicacion')
                ->where('id_estado_producto', 3)
                ->orderBy('fecha_publicacion', 'asc')
                ->get();

            foreach ($products as $product) {
                $image = $this->productImage($product->id_producto);
                if ($image) {
                    $product['images'] = $image;
                }
            }

            return response()->json(['status' => 'success', 'products' => $products], 200);
        } catch (\Exception $e) {
            echo $e;
            return response()->json(['status' => 'error', 'message' => 'Error al obtener los productos disponibles'], 500);
        }
    }
}
