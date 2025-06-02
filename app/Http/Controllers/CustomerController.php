<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CustomerService;
use App\Validators\CustomerValidator;
use Illuminate\Validation\ValidationException;

class CustomerController extends Controller
{
    protected $customerService;
    protected $customerValidator;

    public function __construct(CustomerService $customerService, CustomerValidator $customerValidator)
    {
        $this->customerService = $customerService;
        $this->customerValidator = $customerValidator;
    }

    public function index()
    {
        return response()->json($this->customerService->getAll());
    }

    public function store(Request $request)
    {
        $validated = $this->customerValidator->validateCreate($request->all());

        $customer = $this->customerService->create($validated);

        return response()->json($customer, 201);
    }

    public function show($id)
    {
        $customer = $this->customerService->getById($id);

        if (!$customer) {
            return response()->json(['message' => 'Customer not found'], 404);
        }

        return response()->json($customer);
    }

    public function update(Request $request, $id)
    {
        $validated = $this->customerValidator->validateUpdate($request->all(), $id);

        $customer = $this->customerService->update($id, $validated);

        if (!$customer) {
            return response()->json(['message' => 'Customer not found'], 404);
        }

        return response()->json($customer);
    }

    public function destroy($id)
    {
        $deleted = $this->customerService->delete($id);

        if (!$deleted) {
            return response()->json(['message' => 'Customer not found'], 404);
        }

        return response()->json(['message' => 'Customer deleted']);
    }
}
