<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Customer::all();
        return response()->json([
            "list" => [
                "data" => $data
            ]
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = $request->validate([
            "firstname" => "required|string",
            "lastname" => "required|string",
            "gender" => "required|boolean",
            "tel" => "required|string|nullable|unique:customers",
            "email" => "email|nullable|regex:/^[\w\.-]+@[\w\.-]+\.\w{2,4}$/|unique:customers",
            "dob" => "required|date",
        ]);

        $customer = Customer::create($validate);
        return response()->json([
            "data" => $customer,
            "message" => "Insert successfully!"
        ])->setStatusCode(200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $data = Customer::find($id);
        return response()->json([
            "data" => $data
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $update = Customer::find($id);
        if(!$update){
            return response()->json([
                "error"=>[
                    "update"=>"Id not found"
                ]
            ])->setStatusCode(404);
        }else{
            $validation = $request->validate([
                "firstname" => "required|string",
                "lastname" => "required|string",
                "gender" => "required|boolean",
                "tel" => "required|string|nullable|unique:customers,tel,".$id,
                "email" => "email|nullable|regex:/^[\w\.-]+@[\w\.-]+\.\w{2,4}$/|unique:customers,email,".$id,
                "dob" => "required|date",
            ]);
                
            $update->update($validation); //insert new record
            return response()->json([
                "data"=>$update,
                "message"=>"Update successfully!"
            ])->setStatusCode(200);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $delete = Customer::find($id);
        if (!$delete) {
            return response()->json([
                "error" => [
                    "delete" => "Error 404"
                ]
            ]);
        } else {
            $delete->delete();
            return response()->json([
                "message" => "delete successfully!"
            ]);
        }
    }
}
