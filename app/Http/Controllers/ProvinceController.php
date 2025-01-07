<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Province;

class ProvinceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $province = Province::all();
        return response()->json([
            "list"=>[
                "data"=>$province
            ]
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $province = new Province();
        $province->name = $request->input("name");
        $province->code = $request->input("code");
        $province->distand_from_city = $request->input("distand_from_city");
        $province->status = $request->input("status");
        $province->save();
        return response()->json([
            "data"=>$province,
            "message"=>"Insert successfully!"
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $province = Province::find($id);
        return response()->json([
            "data"=>$province
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $province = Province::find($id);
        if(!$province){
            return response()->json([
                "error"=>[
                    "update"=>"Error 404"
                ]
            ]);
        }else{
            $province->name = $request->input("name");
            $province->code = $request->input("code");
            $province->distand_from_city = $request->input("distand_from_city");
            $province->status = $request->input("status");
            $province->update();
            return response()->json([
                "data"=>$province,
                "message"=>"update successfully!"
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy( $id)
    {
        $province = Province::find($id);
        if(!$province){
            return response()->json([
                "error"=>[
                    "delete"=>"Error 404"
                ]
            ]);
        }else{
            $province->delete();
            return response()->json([
                "message"=>"delete successfully!"
            ]);
        }
    }
}
