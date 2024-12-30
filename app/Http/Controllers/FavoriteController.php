<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Favorite;

class FavoriteController extends Controller
{
    //
    public function getFavorites()
    {
        $user = auth()->user();

        // Retrieve the favorite businesses with eager loading
        $favorites = Favorite::with(['business', 'business.category'])
            ->where('user_id', $user->id)
            ->get();

        return response()->json($favorites, 200);
    }

    public function storeFavorite(Request $request)
    {
        $request->validate([
            'business_id' => 'required|exists:businesses,id',
        ]);

        $user = auth()->user();

        // Check if the business is already favorited
        $existingFavorite = Favorite::where('user_id', $user->id)
            ->where('business_id', $request->business_id)
            ->first();

        if ($existingFavorite) {
            return response()->json(['message' => 'Business is already in favorites.'], 409);
        }

        // Create a new favorite
        $favorite = Favorite::create([
            'user_id' => $user->id,
            'business_id' => $request->business_id,
        ]);

        return response()->json(['message' => 'Business added to favorites.', 'favorite' => $favorite], 201);
    }

    public function removeFavorite($businessId)
    {
        $user = auth()->user();

        // Find the favorite record
        $favorite = Favorite::where('user_id', $user->id)
            ->where('business_id', $businessId)
            ->first();

        if (!$favorite) {
            return response()->json(['message' => 'Favorite not found.'], 404);
        }

        // Delete the favorite
        $favorite->delete();

        return response()->json(['message' => 'Business removed from favorites.'], 200);
    }
}
