<?php

namespace App\Http\Controllers;

use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    use HttpResponses;
    // get all orders
    function getAllOrders()
    {
        try {
            $orders = DB::table('orders')
                ->join('customers', 'orders.customer_id', '=', 'customers.id')
                ->select('orders.*', 'customers.full_name')
                ->paginate(12);
            return $this->success([
                'orders' => $orders
            ]);
        } catch (\Exception) {
            return $this->error(
                'null',
                'Fail To Load Data....',
                400
            );
        }
    }

    // get order by id
    function getOrderByID($id)
    {
        try {
            $order = DB::table('orders')
                ->join('customers', 'orders.customer_id', '=', 'customers.id')
                ->select('orders.*', 'customers.full_name')
                ->where('orders.id', '=', $id)
                ->first();
            return $this->success([
                'order' => $order
            ]);
        } catch (\Exception) {
            return $this->error(
                'null',
                'Fail To Load Data....',
                400
            );
        }
    }

    // get orders by customer id
    function getOrdersByCustomerID($id)
    {
        try {
            $orders = DB::table('orders')
                ->join('customers', 'orders.customer_id', '=', 'customers.id')
                ->select('orders.*', 'customers.full_name')
                ->where('orders.customer_id', '=', $id)
                ->get();
            return $this->success([
                'orders' => $orders
            ]);
        } catch (\Exception) {
            return $this->error(
                'null',
                'Fail To Load Data....',
                400
            );
        }
    }

    // add order with order items
    function addOrder(Request $request)
    {
        try {
            $order = DB::table('orders')
                ->insertGetId([
                    'customer_id' => $request->customer_id,
                    'order_date' => $request->order_date,
                    'order_status' => $request->order_status,
                    'total_amount' => $request->total_amount,

                ]);
            $order_items = $request->order_items;
            foreach ($order_items as $order_item) {
                DB::table('order_items')
                    ->insert([
                        'order_id' => $order,
                        'product_id' => $order_item['product_id'],
                        'quantity' => $order_item['quantity'],
                        'price' => $order_item['price'],
                    ]);
            }
            // update total_amount on orders table according to order items (quantity * price)
            $total_amount = DB::table('order_items')
                ->where('order_id', '=', $order)
                ->sum(DB::raw('quantity * price'));
            DB::table('orders')
                ->where('id', '=', $order)
                ->update([
                    'total_amount' => $total_amount,
                ]);

            return $this->success([
                'order' => $order,
                'order_items' => $order_items,
            ], 'Order Added Successful.');
        } catch (\Exception) {
            return $this->error(
                'null',
                'Fail To Load Data....',
                400
            );
        }
    }

    // update order with order items
    function updateOrder(Request $request)
    {
        try {
            $order = DB::table('orders')
                ->where('id', '=', $request->id)
                ->update([
                    'customer_id' => $request->customer_id,
                    'order_date' => $request->order_date,
                    'order_status' => $request->order_status,
                    'total_amount' => $request->total_amount,

                ]);
            $order_items = $request->order_items;
            foreach ($order_items as $order_item) {
                DB::table('order_items')
                    ->where('id', '=', $order_item['id'])
                    ->update([
                        'order_id' => $request->id,
                        'product_id' => $order_item['product_id'],
                        'quantity' => $order_item['quantity'],
                        'price' => $order_item['price'],
                    ]);
            }
            // update total_amount on orders table according to order items (quantity * price)
            $total_amount = DB::table('order_items')
                ->where('order_id', '=', $request->id)
                ->sum(DB::raw('quantity * price'));
            DB::table('orders')
                ->where('id', '=', $request->id)
                ->update([
                    'total_amount' => $total_amount,
                ]);

            return $this->success([
                'order' => $order,
                'order_items' => $order_items,
            ], 'Order Updated Successful.');
        } catch (\Exception) {
            return $this->error(
                'null',
                'Fail To Load Data....',
                400
            );
        }
    }

    // delete order by id
    function deleteOrder($id)
    {
        try {
            DB::table('orders')
                ->where('id', '=', $id)
                ->delete();
            DB::table('order_items')
                ->where('order_id', '=', $id)
                ->delete();
            return $this->success([
                'order' => $id,
            ], 'Order Deleted Successful.');
        } catch (\Exception) {
            return $this->error(
                'null',
                'Fail To Load Data....',
                400
            );
        }
    }

    // change order status to completed
    function completeOrder($id)
    {
        try {
            DB::table('orders')
                ->where('id', '=', $id)
                ->update([
                    'order_status' => 'delivered',
                ]);
            return $this->success([
                'order' => $id,
            ], 'Order Completed Successful.');
        } catch (\Exception) {
            return $this->error(
                'null',
                'Fail To Load Data....',
                400
            );
        }
    }
}
