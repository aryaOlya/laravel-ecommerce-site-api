<?php

namespace App\Http\Controllers\Api\v1;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;

class OrderController extends ApiController
{
    public static function create($request, $amounts, $token)
    {
        // dd($request->order_items);
        try {
            DB::beginTransaction();

            $order = Order::create([
                'user_id' => $request->user_id,
                'total_amount' => $amounts['totalAmount'],
                'delivery_amount' => $amounts['deliveryAmount'],
                'paying_amount' => $amounts['payingAmount'],
            ]);

            foreach ($request->order_items as $orderItem) {
                $product = Product::findOrFail($orderItem['product_id']);
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'price' => $product->price,
                    'quantity' => $orderItem['quantity'],
                    'subtotal' => ($product->price * $orderItem['quantity'])
                ]);
            }

            Transaction::create([
                'user_id' => $request->user_id,
                'order_id' => $order->id,
                'amount' => $amounts['payingAmount'],
                'token' => $token,
                'request_from' => $request->request_from
            ]);

            DB::commit();
        }catch (\Exception $e){
            DB::rollBack();
            return ApiController::errorResponse(422,$e->getMessage());
        }
    }
}
