<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
            $categories = Category::with(['products'])->get();
        // $categories = Category::all(); // Fetches all categories
        return response()->json([
            "list" => $categories
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            "name" => "required|string",
            "code" => "required|string|unique:categories",
            "parent_id" => "nullable|exists:categories,id",
            "image" => "nullable|image|mimes:png,jpg,jpeg,gif|max:2048",
            "status" => "required|boolean"
        ]);
        $imgPath = null;
        if ($request->hasFile('image')) {
            $imgPath = $request->file('image')->store('categories', 'public');
        }

        $category = Category::create([
            "name" => $request->name,
            "code" => $request->code,
            "parent_id" => $request->parent_id,
            "image" => $imgPath,
            "status" => $request->status
        ]);

        return response()->json([
            "data" => $category,
            "message" => "Insert successfully!"
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $categories = Category::find($id); // Fetches all categories
        return response()->json([
            "list" => $categories
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $category = Category::find($id);
        $request->validate([
            "name" => "required|string",
            "code" => "required|string|unique:categories,code," . $id,
            "parent_id" => "nullable|exists:categories,id",
            "image" => "nullable|image|mimes:png,jpg,jpeg,gif|max:2048",
            "status" => "required|boolean"
        ]);

        $imgPath = $category->image; 
        if ($request->hasFile('image')) {
            if($imgPath){
                Storage::disk('public')->delete($imgPath);
            }
            $imgPath = $request->file('image')->store('categories','public');
        }

        $category->update([
            "name" => $request->name,
            "code" => $request->code,
            "parent_id" => $request->parent_id,
            "image" => $imgPath,
            "status" => $request->status
        ]);

        return response()->json([
            "data" => $category,
            "message" => "Update successfully!"
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $category = Category::find($id);
        $imgPath = $category->image;
        if($imgPath){
            Storage::disk('public')->delete($imgPath);
        }
        $category->delete();
        return response()->json([
            "message"=>"delete successfully!"
        ],200);
    }
}
