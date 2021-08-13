<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Product;
use App\ProductRequest;
use App\User;
use Illuminate\Http\Request;
use Throwable;

class ProductController extends Controller
{
    public function index($id)
    {
        try {
            if(User::findOrFail($id)->role == "supplier") {
                $product = Product::select('id', 'name', 'stock')->where('user_id', $id)->get();
                return response(
                    [
                        'data' => [
                            'product' => $product
                        ],
                        'message' => 'success'
                    ],
                    200);
            } else {
                return response(['message' => 'Wrong ID'], 404);
            }
        } catch(Throwable $e) {
            return response(['message' => 'Wrong ID'], 404);
        }

    }

    public function storeRequest(Request $request, $id) {
     
        try {
            $product_request = new ProductRequest();
            $product_request->supplier_id = $id;
            $product_request->user_id = $request->user()->id;
            $product_request->save();
    
            $products =  json_decode($request->input('products'), true);
            for($i = 0; $i < count($products); $i++) {
                if(Product::findOrFail($products[$i]['id'])->user_id == $id) {
                    $product_request->product()->attach($products[$i]['id'], ['amount' => $products[$i]['amount']]);
                }
            }

            return response([
                'data' => [
                    'request_id' => $product_request->id
                ],
                'message' => 'success'
            ], 200);
        } catch(Throwable $e) {
            return response(['message' => 'something wrong'], 401);
        }

    }
    
    public function myProduct(Request $request) {
        $products = $request->user()->store()->select('id', 'name')->get();
        return response([
            'data' => $products,
            'message' => 'success'
        ], 200);
    }
}
