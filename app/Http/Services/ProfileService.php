<?php

namespace App\Http\Services;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileService
{

    public function getProfile(Request $request)
    {
        return new UserResource(User::find($request->user()->id));
    }

    public function updateProfile(Request $request)
    {
        $user = User::where('id', $request->user()->id)->first();

        $imagePath = $user->image;
        if ($request->hasFile('image')) {
            // Check if an old image exists before deleting
            if ($imagePath && Storage::disk('public')->exists($imagePath)) {
                Storage::disk('public')->delete($imagePath);
            }
            $imagePath = $request->file('image')->store('profileImages', 'public');
        }

        $user->update([
            'name' => $request->name,
            'status' => $request->status,
            'image' => $imagePath,
            'password' => $request->password ? Hash::make($request->password) : $user->password
        ]);
    }

}
