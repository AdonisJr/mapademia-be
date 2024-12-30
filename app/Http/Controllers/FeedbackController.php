<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use App\Models\Business;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class FeedbackController extends Controller
{
    // Store the feedback in the database
    public function store(Request $request, $businessId)
    {
        
        // Validate incoming request data
        $validated = $request->validate([
            'comment' => 'required|string|max:500',
            'stars' => 'required|integer|min:1|max:5', // Stars should be between 1 and 5
        ]);

        // Find the business by ID
        $business = Business::findOrFail($businessId);

        // Create the feedback
        $feedback = Feedback::create([
            'user_id' => Auth::id(), // Get the user ID from the authenticated user
            'business_id' => $business->id,
            'comment' => $validated['comment'],
            'stars' => $validated['stars'],
        ]);

        // Return a success response with the feedback data
        return response()->json([
            'message' => 'Feedback submitted successfully!',
            'data' => $feedback,
        ], 201); // 201 means "Created"
    }

    // Display feedback for a specific business
    public function show($businessId)
    {
        // Fetch the business and its feedback
        $business = Business::findOrFail($businessId);
        $feedbacks = $business->feedback()->with('user')->orderBy('created_at', 'desc')->get(); // Eager load the user who left the feedback

        // Return feedback data as JSON
        return response()->json([
            'business' => $business,
            'feedback' => $feedbacks,
        ]);
    }

    // Optional: Method to delete feedback (if needed)
    public function destroy($feedbackId)
    {
        $feedback = Feedback::findOrFail($feedbackId);
        
        // Check if the authenticated user is the owner of the feedback
        if (Auth::id() === $feedback->user_id || Auth::user()->is_admin) {
            $feedback->delete(); // Delete the feedback
            return response()->json(['message' => 'Feedback deleted successfully.']);
        }

        return response()->json(['error' => 'You are not authorized to delete this feedback.'], 403); // 403 Forbidden
    }
}
