<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Supplier::query();
        if($request->has('id')){
            $query->where("id",$request->input('id'));
        }
        if($request->has('name')){
            $query->where("name",$request->input('name'));
        }
        if($request->has('code')){
            $query->where("code",$request->input('code'));
        }
        if($request->has('email')){
            $query->where("email",$request->input('email'));
        }
        if($request->has('status')){
            $query->where("status",$request->input('status'));
        }
        $supplier = $query->paginate();
        return response()->json([
            "list"=>[
                "data"=>$supplier
            ]
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validation = $request->validate([
            "name" => "required|string",
            "code" => "required|string|unique:suppliers",
            "tel_contact" => "required|string|unique:suppliers",
            "email" => "required|email|unique:suppliers|regex:/^[\w\.-]+@[\w\.-]+\.\w{2,4}$/",
            "address" => "nullable|string",
            "web_site" => "nullable|string|url",
            "status" => "required|boolean"
        ]);
   
        $supplier = Supplier::create($validation); //insert new record
        return response()->json([
            "data"=>$supplier,
            "message"=>"Insert successfully!"
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $supplier = Supplier::find($id);
        return response()->json([
            "data"=>$supplier
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,$id)
    {
        $supplier = Supplier::find($id);
        if(!$supplier){
            return response()->json([
                "error"=>[
                    "update"=>"Id not found"
                ]
            ])->setStatusCode(404);
        }else{
            $validation = $request->validate([
                "name" => "required|string",
                "code" => "required|string|unique:suppliers,code,".$id,
                "tel_contact" => "required|string|unique:suppliers,tel_contact,".$id,
                "email" => "required|email|regex:/^[\w\.-]+@[\w\.-]+\.\w{2,4}$/|unique:suppliers,email,".$id,
                "address" => "nullable|string",
                "web_site" => "nullable|string|url",
                "status" => "required|boolean"
            ]);
                
            $supplier->update($validation); //insert new record
            return response()->json([
                "data"=>$supplier,
                "message"=>"Update successfully!"
            ])->setStatusCode(200);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy( $id)
    {
        $supplier = Supplier::find($id);
        if(!$supplier){
            return response()->json([
                "error"=>[
                    "delete"=>"Error 404"
                ]
            ])->setStatusCode(404);
        }else{
            $supplier->delete();
            return response()->json([
                "message"=>"delete successfully!"
            ])->setStatusCode(200);
        }
    }
}
