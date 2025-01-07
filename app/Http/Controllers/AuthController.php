<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function login(Request $request) 
     {
        $request->validate([
            'email' => 'required|string|email|regex:/^[\w\.-]+@[\w\.-]+\.\w{2,4}$/',
            'password' => 'required|string'
        ]);

        if(!$token = JWTAuth::attempt($request->only('email','password'))){
            return response()->json([
                "error" => 'Unauthorized'
            ],401);
        }
        return response()->json([
            'access_token' => $token,
            'user' => JWTAuth::user()->load('profile'),
        ]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = User::with('profile')->get();
        return response()->json([
            "list" => $user
        ], 200);

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            "name"      => "required",
            "email"     => "required|email|regex:/^[\w\.-]+@[\w\.-]+\.\w{2,4}$/|unique:users",
            "password"  => "required|min:8",
            "phone"     => "nullable",
            "address"   => "nullable",
            "type"      => "nullable",
            "image"     => "nullable|image|mimes:jpg,jpeg,png,gif|max:2048"
        ]);

        // Create the User
        $user = User::create([
            'name'      => $request->name,
            'email'     => $request->email,
            'password'  => Hash::make($request->password),
        ]);

        // Handle the image upload
        $imgPath = null;
        if ($request->hasFile('image')) {
            $imgPath = $request->file("image")->store("profiles", "public");
        }

        // Create the Profile
        $user->profile()->create([
            'phone' => $request->phone,
            'address' => $request->address,
            'image' => $imgPath,
            'type' => $request->type,
        ]);

        return response()->json([
            "data" => $user->load('profile')
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // $user = User::findOrFail($id);
        $user = User::with('profile')->find($id);
        return response()->json([
            "data" => $user
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            "name"      => "required",
            "email"     => "required|email|regex:/^[\w\.-]+@[\w\.-]+\.\w{2,4}$/|unique:users,email," . $id,
            "password"  => "required|min:8",
            "phone"     => "nullable",
            "address"   => "nullable",
            "type"      => "nullable",
            "image"     => "nullable|image|mimes:jpg,jpeg,png,gif|max:2048"
        ]);

        // Update the User
        $user = User::findOrFail($id);
        $user->update([
            'name' => $request->name,
            'email' => $request->email
        ]);

        // Handle the image upload if it exits
        $imgPath = $user->profile->image;
        if ($request->hasFile('image')) {
            //if old image have it will delete old image from file
            if ($imgPath) {
                Storage::disk('public')->delete($imgPath);
            }
            //upload the new image
            $imgPath = $request->file('image')->store('profiles', 'public');
        }

        // update the Profile
        $user->profile()->update([
            'phone' => $request->phone,
            'address' => $request->address,
            'image' => $imgPath,
            'type' => $request->type,
        ]);

        return response()->json([
            "data" => $user->load('profile'),
            "message" => "Update successfully!"
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $user = User::find($id);
            if (!$user) {
                return response()->json([
                    "message" => "User not found!"
                ], 404);
            }
    
            // Delete the profile image if it exists
            if ($user->profile && $user->profile->image) {
                Storage::disk('public')->delete($user->profile->image);
                $user->profile->delete();
            }
            $user->delete();

            return response()->json([
                // 'data' => $user,
                "message" => "User Delete successfully!"
            ],200);
        } catch (\Exception $e) {
            return response()->json([
                "message" => "An error occurred while deleting the user.",
                "error" => $e->getMessage()
            ], 500);
        }
    }
}
