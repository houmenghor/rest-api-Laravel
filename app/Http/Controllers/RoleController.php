<?php



namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;
use Illuminate\Http\Client\Response;

class RoleController extends Controller
{
    // Controller methods here

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $data = Role::all();
        return response()->json([

            "list" => $data

        ],200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //body json
        $add_data = new Role();
        $add_data->name = $request->input('name'); //name = $_POST["name"];
        $add_data->description = $request->input('description');
        $add_data->status = $request->input('status');
        $add_data->save(); //insert into table add_datas
        return response()->json([
            "data" => $add_data,
            "message" => "Insert successfully!"
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        return response()->json([
            "list" => Role::find($id)
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $role = Role::find($id);
        if (!$role) {
            return response()->json([
                "error" => [
                    "update" => "Error 404"
                ]
            ]);
        } else {
            $role->name = $request->input('name'); //name = $_POST["name"];
            $role->description = $request->input('description');
            $role->status = $request->input('status');
            $role->update();
            return response()->json([
                "data" => $role,
                "message" => "Update successfully!"
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
        $role = Role::find($id);
        if (!$role) {
            return response()->json([
                "error" => [
                    "delete" => "Error 404"
                ]
            ]);
        } else {
            $role->delete();
            return response()->json([
                "message" => "delete successfully!"
            ]);
        }
    }
}
