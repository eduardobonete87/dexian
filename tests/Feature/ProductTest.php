<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use App\Models\Product;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_creates_a_product_successfully()
    {
        Storage::fake('public');

        $data = [
            'name' => 'Pastel de Queijo',
            'price' => 9.99,
            'photo' => UploadedFile::fake()->image('pastel.jpg'),
        ];

        $response = $this->postJson('/api/products', $data);

        $response->assertStatus(201)
                 ->assertJsonFragment([
                     'name' => 'Pastel de Queijo',
                     'price' => 9.99,
                 ]);

        $this->assertDatabaseHas('products', ['name' => 'Pastel de Queijo']);
    }

    /** @test */
    public function it_fails_to_create_product_with_missing_fields()
    {
        $response = $this->postJson('/api/products', []);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['name', 'price', 'photo']);
    }

    /** @test */
    public function it_fails_to_create_product_without_photo()
    {
        $data = [
            'name' => 'Pastel de Carne',
            'price' => 10.5,
            // Foto ausente
        ];

        $response = $this->postJson('/api/products', $data);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['photo']);
    }
}
