<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Business;
use Illuminate\Support\Facades\Log;

class BusinessController extends Controller
{
    //
     // Show all businesses
     public function index()
     {
         $businesses = Business::with(['category', 'feedback'])->get();
         return response()->json($businesses);
     }
 
     // Show a single business
     public function show($id)
     {
         $business = Business::findOrFail($id);
         return response()->json($business);
     }
 
     // Create a new business
     public function store(Request $request)
     {
         // Log the incoming request data
         Log::info('Incoming Request Data:', $request->all());
     
         // Log if an image file is present
         if ($request->hasFile('image')) {
             Log::info('Image File Detected:', [
                 'name' => $request->file('image')->getClientOriginalName(),
                 'mime_type' => $request->file('image')->getMimeType(),
                 'size' => $request->file('image')->getSize(),
             ]);
         } else {
             Log::info('No image file in the request.');
         }
     
         // Validate the incoming request
         $validated = $request->validate([
             'name' => 'required|string|max:255',
             'description' => 'required|string',
             'address' => 'required|string|max:255',
             'contact' => 'required|string|max:255',
             'email' => 'required|email',
             'owner' => 'required|string|max:255',
             'latitude' => 'required|numeric',
             'longitude' => 'required|numeric',
             'category_id' => 'required|exists:categories,id',
            //  'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'image' => 'nullable'
         ]);
     
         // Handle file upload
         if ($request->hasFile('image')) {
             $image = $request->file('image');
             $fileName = time() . '_' . $image->getClientOriginalName(); // Generate a unique filename
             $filePath = $image->storeAs('businesses', $fileName, 'public'); // Save to 'public/businesses'
     
             // Add the storage path to the validated data
             $validated['image'] = '/storage/' . $filePath;
     
             // Log the storage path
             Log::info('Image saved at:', ['path' => $validated['image']]);
         }
     
         // Create the business
         $business = Business::create($validated);
     
         // Log the created business
         Log::info('Business Created:', $business->toArray());
     
         return response()->json($business, 201);
     }
     
 
     // Update an existing business
     public function update(Request $request, Business $business)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'address' => 'required|string|max:255',
            'contact' => 'required|string|max:255',
            'email' => 'required|email',
            'owner' => 'required|string|max:255',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Handle file upload
        if ($request->hasFile('image')) {
            // Delete the old image if exists
            if ($business->image) {
                Storage::disk('public')->delete($business->image);
            }

            $validated['image'] = $request->file('image')->store('businesses', 'public');
        }

        $business->update($validated);

        return response()->json($business);
    }

 
     // Delete a business
     public function destroy($id)
     {
         $business = Business::findOrFail($id);
         $business->delete();
 
         return response()->json(null, 204);
     }
}
