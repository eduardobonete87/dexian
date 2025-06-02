<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Customer;
use Illuminate\Support\Facades\Mail;

class OrderService
{
    public function createOrder($customerId, array $productIds)
    {
        $order = Order::create(['customer_id' => $customerId]);
        $order->products()->attach($productIds);

        $customer = Customer::find($customerId);
        $products = $order->products()->get();

        Mail::raw('Pedido criado com sucesso! Produtos: ' . $products->pluck('name')->join(', '), function ($message) use ($customer) {
            $message->to($customer->email)->subject('Confirmação do Pedido');
        });

        return $order->load('products');
    }
}
