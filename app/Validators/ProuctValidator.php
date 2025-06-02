<?php

namespace App\Validators;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class ProductValidator
{
    public static function validateStore($data)
    {
        $validator = Validator::make($data, [
            'name'  => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'photo' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'type'  => 'required|string',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $validator->validated();
    }

    public static function validateUpdate($data)
    {
        $validator = Validator::make($data, [
            'name'  => 'sometimes|required|string|max:255',
            'price' => 'sometimes|required|numeric|min:0',
            'photo' => 'sometimes|required|string',
            'type'  => 'sometimes|required|string',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $validator->validated();
    }
}
