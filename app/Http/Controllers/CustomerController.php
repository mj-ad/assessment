<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;
use Illuminate\Http\Request;
use App\Models\Customer;
use Illuminate\Support\Facades\Storage;

class CustomerController extends Controller
{
    public function index()
    {
        return view('customers.index', ['customers' => Customer::all()]);
    }

    public function create()
    {
        return view('customers.create');
    }

    public function store(StoreCustomerRequest $request)
    {
        $cvPath = $request->file('cv') ? $request->file('cv')->store('cvs', 'public') : null;

        Customer::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'cv_path' => $cvPath,
        ]);

        return redirect()->route('customers.index')->with('success', 'Customer added successfully.');
    }

    public function edit(Customer $customer)
    {
        return view('customers.edit', compact('customer'));
    }

    public function update(UpdateCustomerRequest $request, Customer $customer)
    {
        if ($request->hasFile('cv')) {
            if ($customer->cv_path) {
                Storage::disk('public')->delete($customer->cv_path);
            }
            $cvPath = $request->file('cv')->store('cvs', 'public');
            $customer->cv_path = $cvPath;
        }

        $customer->update($request->only(['name', 'email', 'phone']));

        return redirect()->route('customers.index')->with('success', 'Customer updated successfully.');
    }

    public function destroy(Customer $customer)
    {
        if ($customer->cv_path) {
            Storage::disk('public')->delete($customer->cv_path);
        }
        $customer->delete();
        return redirect()->route('customers.index')->with('success', 'Customer deleted successfully.');
    }
}
