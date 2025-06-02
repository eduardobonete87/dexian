<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Order;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderCreated;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_creates_an_order_successfully_with_multiple_products()
    {
        Mail::fake();

        $customer = Customer::factory()->create();
        $products = Product::factory()->count(3)->create();

        $productIds = $products->pluck('id')->toArray();

        $response = $this->postJson('/api/orders', [
            'customer_id' => $customer->id,
            'product_ids' => $productIds,
        ]);

        $response->assertStatus(201)
                 ->assertJsonFragment([
                     'customer_id' => $customer->id,
                 ]);

        $this->assertDatabaseHas('orders', ['customer_id' => $customer->id]);

        Mail::assertSent(OrderCreated::class, function ($mail) use ($customer) {
            return $mail->hasTo($customer->email);
        });
    }

    /** @test */
    public function it_fails_to_create_order_with_invalid_customer()
    {
        $product = Product::factory()->create();

        $response = $this->postJson('/api/orders', [
            'customer_id' => 9999,
            'product_ids' => [$product->id],
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['customer_id']);
    }

    /** @test */
    public function it_fails_to_create_order_with_invalid_products()
    {
        $customer = Customer::factory()->create();

        $response = $this->postJson('/api/orders', [
            'customer_id' => $customer->id,
            'product_ids' => [9999],
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['product_ids.0']);
    }
}
