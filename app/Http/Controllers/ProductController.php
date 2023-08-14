<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\ProductModel;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    use HttpResponses;

    // get all product, category name with  try catch block
    function getProducts()
    {
        try {
            $products = ProductModel::paginate(12);
            return $this->success([
                'products' => $products
            ]);
        } catch (\Exception) {
            return $this->error(
                'null',
                'Fail To Load Data....',
                400
            );
        }
    }


    function getProductNames()
    {
        try {
            $product = DB::table('products')
                ->select('products.id as value', 'products.product_name as label', 'products.price as price')
                ->where('products.is_discontinued', '=', 0)
                ->get();
            return $this->success([
                'product' => $product
            ]);
        } catch (\Exception) {
            return $this->error(
                'null',
                'Fail To Load Data....',
                400
            );
        }
    }
    // get product by id, category name with  try catch block
    function getProductByID($id)
    {
        try {
            $product = ProductModel::find($id);
            return $this->success([
                'product' => $product
            ]);
        } catch (\Exception) {
            return $this->error(
                'null',
                'Fail To Load Data....',
                400
            );
        }
    }
    // add product 
    function addProduct(StoreProductRequest $request)
    {
        $request->validated($request->all());
        $product = ProductModel::create([
            'product_name' => $request->product_name,
            'product_image' => $request->product_image,
            'price' => $request->price,
            'description' => $request->description,
            'is_discontinued' => false,
        ]);
        return $this->success([
            'product' => $product,
            'Product Added Successful.'
        ]);
    }
    // update product
    function updateProduct(UpdateProductRequest $request)
    {
        $request->validated($request->all());
        $product = ProductModel::find($request->id);
        if (!$product) {
            return $this->error(
                'null',
                'Product Not Found.',
                400
            );
        }
        $product->product_name = $request->product_name;
        $product->product_image = $request->product_image;
        $product->price = $request->price;
        $product->description = $request->description;
        $product->save();

        return $this->success([
            'product' => $product,
            ''
        ]);
    }

    function deleteProduct($id)
    {
        try {
            $product = ProductModel::find($id);
            if ($product) {
                $product->delete();
                return $this->success(
                    $product,
                    'Product deleted successfully'
                );
            } else {
                return $this->error(
                    'null',
                    'Product not found',
                    404
                );
            }
        } catch (\Exception) {
            return $this->error(
                'null',
                'Fail To Delete Data....',
                400
            );
        }
    }

    public function discontinueProduct($id)
    {
        $product = ProductModel::find($id);
        if ($product) {
            $product->is_discontinued = true;
            $product->save();
        }
        return $this->success(null, 'Product Discontinued Successful.');
    }

    public function continueProduct($id)
    {
        $product = ProductModel::find($id);
        if ($product) {
            $product->is_discontinued = false;
            $product->save();
        }
        return $this->success(null, 'Product Continued Successful.');
    }
}
