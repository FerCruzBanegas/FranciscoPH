<?php

namespace App\Http\Controllers\Customers;

use App\Customer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;

class CustomerController extends Controller
{
    public function filter (Request $request)
    {
        $query = Customer::query();

        if($request->search) {
            $query->where('name', 'LIKE', '%'.$request->search.'%');
        }

        $customers = $query->orderBy($request->input('orderBy.column'), $request->input('orderBy.direction'))
                    ->paginate($request->input('pagination.per_page'));

        // $users->load('roles');

        return $customers;
    }

    public function show ($user)
    {
        return Customer::findOrFail($user);
    }

    public function store (Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:64',
            'phone' => 'required|string|max:32',
            'ci' => 'required|string|max:16',
            'email' => 'nullable|max:128|email|unique:customers',
            'address' => 'nullable|string|max:128'
        ]);

        $customer = Customer::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'ci' => $request->ci,
            'email' => $request->email,
            'address' => $request->address,
        ]);

        return $customer;
    }

    public function update (Request $request, Customer $customer)
    {
        $this->validate($request, [
            'name' => 'required|string|max:64',
            'phone' => 'required|string|max:32',
            'ci' => 'required|string|max:16',
            'email' => 'nullable|max:128|email',Rule::unique('customers')->ignore($customer->id),
            'address' => 'nullable|string|max:128'
        ]);

        $customer->update([
            'name' => $request->name,
            'phone' => $request->phone,
            'ci' => $request->ci,
            'email' => $request->email,
            'address' => $request->address,
        ]);

        return $customer;
    }

    public function destroy ($customer)
    {
        return Customer::destroy($customer);
    }

    public function count ()
    {
        return Customer::count();
    }

    public function all()
    {
        return Customer::all();
    }
}
