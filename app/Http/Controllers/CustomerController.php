<?php

namespace App\Http\Controllers;

use App\Models\CustomerModel;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    use HttpResponses;

    // get all customer with pagination with try catch block
    function getCustomer()
    {
        try {
            $customer = CustomerModel::paginate();
            return $this->success([
                'customer' => $customer
            ]);
        } catch (\Exception) {
            return $this->error(
                'null',
                'Fail To Load Data....',
                400
            );
        }
    }
    function getCustomerNames()
    {
        try {
            $customer = DB::table('customers')
                ->select('customers.id as value', 'customers.full_name as label')
                ->get();

            return $this->success([
                'customer' => $customer
            ]);
        } catch (\Exception) {
            return $this->error(
                'null',
                'Fail To Load Data....',
                400
            );
        }
    }

    // get customer by id
    function getCustomerByID($id)
    {
        $customer = CustomerModel::find($id);
        return $this->success([
            'customer' => $customer
        ]);
    }

    // add customer
    function addCustomer(Request $request)
    {
        $customer = CustomerModel::create([

            'full_name' => $request->full_name,
            'email' => strtolower($request->email),
            'phone' => $request->phone,
            'address' => $request->address,

        ]);
        return $this->success([
            'customer' => $customer,
        ], 'Customer Added Successful.');
    }

    // update customer
    function updateCustomer(Request $request)
    {
        $customer = CustomerModel::find($request->id);
        if (!$customer) {
            return $this->error(
                'null',
                'Customer Not Found.',
                400
            );
        }
        $customer->full_name = $request->full_name;
        $customer->email = strtolower($request->email);
        $customer->phone = $request->phone;
        $customer->address = $request->address;

        $customer->save();

        return $this->success([
            'customer' => $customer,
        ], 'Customer Updated Successful.');
    }

    // delete customer
    function deleteCustomer($id)
    {
        $customer = CustomerModel::find($id);
        if (!$customer) {
            return $this->error(
                'null',
                'Customer Not Found.',
                400
            );
        }
        $customer->delete();
        return $this->success([
            'customer' => $customer,
        ], 'Customer Deleted Successful.');
    }


    // search customer by fullname, email, phone
    function searchCustomerByAll($search)
    {
        $customer = CustomerModel::where('full_name', 'like', '%' . $search . '%')
            ->orWhere('email', 'like', '%' . $search . '%')
            ->orWhere('phone', 'like', '%' . $search . '%')
            ->get();
        return $this->success([
            'customer' => $customer
        ]);
    }
}
