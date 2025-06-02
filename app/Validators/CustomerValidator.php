<?php

namespace App\Validators;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class CustomerValidator
{
    public function validateCreate(array $data): array
    {
        $validator = Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:customers,email',
            'phone' => 'required|string|max:20',
            'birth_date' => 'required|date',
            'address' => 'required|string|max:255',
            'complement' => 'nullable|string|max:255',
            'neighborhood' => 'required|string|max:255',
            'zipcode' => 'required|string|max:10',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $validator->validated();
    }

    public function validateUpdate(array $data, $id): array
    {
        $validator = Validator::make($data, [
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|unique:customers,email,' . $id,
            'phone' => 'sometimes|required|string|max:20',
            'birth_date' => 'sometimes|required|date',
            'address' => 'sometimes|required|string|max:255',
            'complement' => 'nullable|string|max:255',
            'neighborhood' => 'sometimes|required|string|max:255',
            'zipcode' => 'sometimes|required|string|max:10',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $validator->validated();
    }
}
