<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;

class OrderController extends Controller
{
    public function index()
    {
        return response()->json(Order::with('products')->get());
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'customer_id' => 'required|exists:customers,id',
            'product_ids' => 'required|array|min:1',
            'product_ids.*' => 'exists:products,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $order = Order::create(['customer_id' => $request->customer_id]);
        $order->products()->attach($request->product_ids);

        $customer = Customer::find($request->customer_id);
        $products = $order->products()->get();

        Mail::raw('Pedido criado com sucesso! Produtos: ' . $products->pluck('name')->join(', '), function($message) use ($customer) {
            $message->to($customer->email)->subject('Confirmação do Pedido');
        });

        return response()->json($order->load('products'), 201);
    }

    public function show($id)
    {
        $order = Order::with('products')->find($id);
        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }
        return response()->json($order);
    }

    public function destroy($id)
    {
        $order = Order::find($id);
        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }
        $order->delete();
        return response()->json(['message' => 'Order deleted']);
    }
}
