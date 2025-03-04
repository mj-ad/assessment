<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;
use Illuminate\Http\Request;
use App\Models\Customer;
use Illuminate\Support\Facades\Storage;

class CustomerAPIController extends Controller
{
    public function all()
    {
        $customers = Customer::all();
        return response()->json($customers);
    }
    public function show($customer_no)
    {
        $customer = Customer::where('id', $customer_no)->first();

        if (!$customer) {
            return response()->json(['message' => 'Customer not found'], 404);
        }

        return response()->json($customer);
    }

    public function update(UpdateCustomerRequest $request, $customer_no)
    {
        \Log::info('Request Data:', $request->all());

        $customer = Customer::where('id', $customer_no)->first();

        if (!$customer) {
            return response()->json(['message' => 'Customer not found'], 404);
        }

        if ($request->hasFile('cv_path')) {
            // Delete the old CV file if it exists
            if ($customer->cv_path) {
                Storage::disk('public')->delete($customer->cv_path);
            }

            // Store the new CV file
            $cvPath = $request->file('cv_path')->store('cvs', 'public');
            $customer->cv_path = $cvPath;
        }

        $customer->update($request->only('name', 'email', 'phone'));

        return response()->json([
            'message' => 'Customer updated successfully',
            'customer' => $customer
        ]);
    }

    public function store(StoreCustomerRequest $request)
    {
        // Handle file upload
        $cvPath = null;
        if ($request->hasFile('cv_path')) {
            $cvPath = $request->file('cv_path')->store('cvs', 'public'); // Store in 'storage/app/public/cvs'
        }

        $customer = Customer::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'cv_path' => $cvPath
        ]);

        return response()->json([
            'message' => 'Customer created successfully',
            'customer' => $customer
        ], 201);
    }
}
