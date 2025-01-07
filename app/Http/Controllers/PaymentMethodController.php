<?php

namespace App\Http\Controllers;

use App\Models\PaymentMethod;
use Illuminate\Http\Request;

class PaymentMethodController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = PaymentMethod::all();
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
            "name" => "required|string",
            "code" => "required|string|unique:payment_methods",
            "website" => "nullable|string|url",
            "service_contact" => "nullable|string",
            "status" => "required|boolean",
        ]);

        // Add a condition before inserting the data
        if (!$validate['status']) {
            return response()->json([
                "message" => "Status must be true to insert the data."
            ])->setStatusCode(400); // 400 Bad Request
        }

        $payment_methods = PaymentMethod::create($validate);

        return response()->json([
            "data" => $payment_methods,
            "message" => "Insert successfully!"
        ])->setStatusCode(200);
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $data = PaymentMethod::find($id);
        return response()->json([
            "data" => $data
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $update = PaymentMethod::find($id);
        if(!$update){
            return response()->json([
                "error"=>[
                    "update"=>"Id not found"
                ]
            ])->setStatusCode(404);
        }else{
            $validation = $request->validate([
                "name" => "required|string",
                "code" => "required|string|unique:payment_methods,code,".$id,
                "website" => "nullable|string|url",
                "service_contact" => "nullable|string",
                "status" => "required|boolean",
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
        $delete = PaymentMethod::find($id);
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
