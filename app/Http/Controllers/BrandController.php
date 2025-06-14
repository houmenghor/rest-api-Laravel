<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            // Retrieve all brands
            $data = Brand::all();

            // Return success response
            return response()->json([
                "list" => $data
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                "message" => "An error occurred!",
                "error" => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // Validate the request
            $request->validate([
                "name" => "required|string|max:255",
                "code" => "required|string|unique:brands,code,",
                "from_country" => "required|string|max:255",
                "status" => "required|in:active,inactive",
                "image" => "nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048"
            ]);

            // Handle the image upload
            $imgpath = null;
            if ($request->hasFile('image')) {
                $imgpath = $request->file('image')->store('brands', 'public');
            }

            // Create the brand
            $brand = Brand::create([
                'name' => $request->name,
                'code' => $request->code,
                'from_country' => $request->from_country,
                'status' => $request->status,
                'image' => $imgpath
            ]);

            // Return a success response
            return response()->json([
                "data" => $brand,
                "message" => "Insert successfully!"
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {

            // Handle validation errors
            return response()->json([
                "errors" => $e->errors(),
                "message" => "Validation failed!"
            ], 422);
        } catch (\Exception $e) {

            // Handle other errors
            return response()->json([
                "message" => "An error occurred!",
                "error" => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $data = Brand::find($id);
            if (!$data) {
                return response()->json([
                    "message" => "Brand not found!"
                ], 404);
            } else {
                return response()->json([
                    "data" => $data
                ], 200);
            }
        } catch (\Exception $e) {
            return response()->json([
                "message" => "An error occurred!",
                "error" => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */

    public function update(Request $request, string $id)
    {
        $brand = Brand::find($id);
        $request->validate([
            "name" => "required|string|max:255",
            "code" => "required|string|unique:brands,code," . $id,
            "from_country" => "required|string|max:255",
            "status" => "required|in:active,inactive",
            "image" => "nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048"
        ]);

        // Handle the image upload if a new one is provided
        $imgpath = $brand->image;  // Keep the old image initially
        if ($request->hasFile('image')) {

            if ($brand->image) {
                Storage::disk('public')->delete($brand->image);
            }

            // Store the new image
            $imgpath = $request->file('image')->store('brands', 'public');
        }

        if ($request->image_remove) {
            Storage::disk('public')->delete($imgpath);
            $imgpath = null;
        }

        // Update the brand record
        $brand->update([
            'name' => $request->name,
            'code' => $request->code,
            'from_country' => $request->from_country,
            'status' => $request->status,
            'image' => $imgpath
        ]);

        // Return success response
        return response()->json([
            'data' => $brand,
            'message' => 'Update successfully!'
        ], 200);
    }





    public function destroy(string $id)
    {
        try {
            $brand = Brand::find($id);
            if (!$brand) {
                return response()->json([
                    "message" => "Brand not found!"
                ], 404);
            } else {
                //delete the image if it exit
                $image = $brand->image;
                if ($image) {
                    Storage::disk('public')->delete($image);
                }
                $brand->delete();
                return response()->json([
                    "message" => "Delete successfully!"
                ], 200);
            }
        } catch (\Exception $e) {

            // Handle other errors
            return response()->json([
                "message" => "An error occurred!",
                "error" => $e->getMessage()
            ], 500);
        }
    }
}
