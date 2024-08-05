<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api')->except('create', 'editProd', 'delProd');
    }


    public function create(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'product_name' => 'required|string',
                'product_discription' => 'string',
                'product_price' => 'required|integer',
                'product_discount' => 'integer'
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'message' => 'Parameters Error',
                'errors' => $validator->errors(),
            ], 400);
        }

        // 建立新使用者
        $prod = Product::create([
            'product_name' => $request->product_name,  
            'product_discription' => $request->product_discription,
            'product_price' => $request->product_price,
            'product_discount' => $request->product_discount
        ]);

        $prod->save();

        return response()->json([
            'status' => 200,
            'token_type' => 'Bearer'
        ]);
    }

    public function editProd(Request $request, $id) {
        $validator = Validator::make(
            $request->all(),
            [
                'product_name' => 'required|string',
                'product_discription' => 'string',
                'product_price' => 'required|integer',
                'product_discount' => 'integer'
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'message' => 'Parameters Error',
                'errors' => $validator->errors(),
            ], 400);
        }

        $prod = Product::find($id);
        $prod->update($request->all());

        return response()->json([
            'status' => 200,
            'token_type' => 'Bearer',
        ]);
    }

    public function delProd($id) {
        $prod = Product::find($id);

        if (!$prod) {
            return response()->json([
                'status' => 400,
                'message' => 'User does not exist',
            ], 400);
        }

        $prod->delete();

        return response()->json([
            'status' => 200,
            'token_type' => 'Bearer',
        ]);
    }

}
