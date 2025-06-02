<?php

namespace App\Services;

use App\Models\Product;

class ProductService
{
    public function createProduct($validatedData, $photoFile)
    {
        $path = $photoFile->store('products', 'public');

        return Product::create([
            'name'  => $validatedData['name'],
            'price' => $validatedData['price'],
            'photo' => $path,
            'type'  => $validatedData['type'],
        ]);
    }

    public function updateProduct(Product $product, array $validatedData)
    {
        return tap($product)->update($validatedData);
    }
}
