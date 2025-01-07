<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // $product = Product::with('category','brand')->get();
        $query = Product::query();
        if($request->has('product_name')){
            $query->where("product_name","LIKE","%".$request->input('product_name')."%");
        }
        if($request->has('status')){
            $query->where("status","=",$request->input('status'));
        }

        $product = $query->with(['category','brand'])->get();
        return response()->json([
            "data" => $product,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            "category_id" => "required|exists:categories,id", //jab id pi table categories
            "brand_id" => "required|exists:brands,id", //jab id pi table brands
            "product_name" => "required|string",
            "description" => "nullable|string",
            "quantity" => "required|integer",
            "price" => "required|numeric",
            "image" => "nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048",
            "status" => "required|boolean",
        ]);
        $imgPath = null;
        if($request->hasFile('image')){
            $imgPath = $request->file('image')->store('products','public');
        }
        $product = Product::create([
            "category_id" => $request->category_id,
            "brand_id" => $request->brand_id,
            "product_name" => $request->product_name,
            "description" => $request->description,
            "quantity" => $request->quantity,
            "price" => $request->price,
            "image" => $imgPath,
            "status" => $request->status
        ]);

        return response()->json([
            "data" => $product,
            "message" => "Create successfully!"
        ],201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $product = Product::with(['category','brand'])->findOrFail($id);
        return response()->json([
            "data" => $product
        ],200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $product = Product::findOrFail($id);
        $request->validate([
            "category_id" => "required|exists:categories,id", //jab id pi table categories
            "brand_id" => "required|exists:brands,id", //jab id pi table brands
            "product_name" => "required|string",
            "description" => "nullable|string",
            "quantity" => "required|integer",
            "price" => "required|numeric",
            "image" => "nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048",
            "status" => "required|boolean",
        ]);
        // $imgPath = null;
        if($request->hasFile('image')){
            if($product->image){
                Storage::disk('public')->delete($product->image);
            }
            $imgPath = $request->file('image')->store('products','public');
            $product->image = $imgPath;
        }
        $product = $product->update([
            "category_id" => $request->category_id,
            "brand_id" => $request->brand_id,
            "product_name" => $request->product_name,
            "description" => $request->description,
            "quantity" => $request->quantity,
            "price" => $request->price,
            "image" => $imgPath,
            "status" => $request->status
        ]);

        return response()->json([
            "data" => $product,
            "message" => "Update successfully!"
        ],200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = Product::findOrFail($id);
        if($product->image){
            Storage::disk('public')->delete($product->image);
        }
        $product->delete();
        return response()->json([
            "message" => "Delete successfully!"
        ],200);
    }
}
