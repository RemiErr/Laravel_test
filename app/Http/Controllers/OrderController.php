<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api')->except('create', 'editOrder', 'delOrder', 'getAllOrders' ,'getOrder');
    }

    public function create(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'item_quantity' => 'required|integer',
                'product_id' => 'required|integer',
                'user_id' => 'required|integer'
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'message' => 'Parameters Error',
                'errors' => $validator->errors(),
            ], 400);
        }

        $order = Order::create([
            'user_id' => $request->user_id
        ]);

        $order->products()->attach($request->product_id, ['item_quantity' => $request->item_quantity]);

        $order->save();

        return response()->json([
            'status' => 200,
            'token_type' => 'Bearer'
        ]);
    }

    public function editOrder(Request $request, $id) {
        $validator = Validator::make(
            $request->all(),
            [
                'item_quantity' => 'required|integer'
            ]
        );

        if ($validator->fails() || $request->item_quantity <= 0) {
            return response()->json([
                'status' => 400,
                'message' => 'Parameters Error',
                'errors' => $validator->errors(),
            ], 400);
        }

        $order = Order::findOrFail($id);
        $order->products()->update($request->all());

        return response()->json([
            'status' => 200,
            'token_type' => 'Bearer',
        ]);
    }

    public function delOrder($id) {
        $order = Order::find($id);

        if (!$order) {
            return response()->json([
                'status' => 400,
                'message' => 'User does not exist',
            ], 400);
        }

        $order->delete();

        return response()->json([
            'status' => 200,
            'token_type' => 'Bearer',
        ]);
    }

    function getAllOrders(Request $request) {

        $query = Order::with(['products' => function ($query) {
            $query->select('products.product_id', 'products.product_name', 'oi.item_quantity')
                ->join('order_items as oi', 'products.product_id', '=', 'oi.product_id');
        }]);

        // 過濾條件
        if ($request->has('id')) {
            $query->where('user_id', $request->input('id'));
        }

        if ($request->has('name')) {
            $query->whereHas('user',
                function($q) use ($request) {
                    $q->where('user_name', 'like', '%' . $request->input('name') . '%');
                }
            );
        }

        $orders = $query->get();

        return response()->json([
            'status' => 200,
            'data' => $orders,
            'token_type' => 'Bearer',
        ]);
        
    }

    function getOrder($id) {

        $order = Order::with(['products' =>
            function ($query) {
                $query->select('products.product_id', 'products.product_name', 'oi.item_quantity')
                    ->join('order_items as oi', 'products.product_id', '=', 'oi.product_id');
            }
        ])->findOrFail($id);
        
        return response()->json([
            'status' => 200,
            'data' => $order,
            'token_type' => 'Bearer',
        ]);
    }

}
