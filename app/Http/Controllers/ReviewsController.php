<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Review;
class ReviewsController extends Controller
{
    // Add a review to a course 
    public function addReview(Request $request, $id)
    {
        // Validate the request data
        $request->validate([
            'rating' => 'required|numeric|min:1|max:5',
            'comment' => 'required|string',
        ]);

        // Check that the user is a student
        if (auth()->user()->role !== 'student') {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // Find the course if not found return 404 not found
        Course::findOrFail($id);

        //create a new review
        $review = auth()->user()->reviews()->create([
            'course_id' => $id,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);
        return response()->json($review, 201);
    }

    // Update a review 
    public function updateReview(Request $request, $id)
    {
        // Validate the request data
        $request->validate([
            'rating' => 'numeric|min:1|max:5',
            'comment' => 'string',
        ]);

        // Find the review if not found return 404 not found  if found update it
        $review = auth()->user()->reviews()->findOrFail($id);
        $review->update($request->all());
        return response()->json($review);
    }

    // Delete a review
    public function deleteReview($id)
    {

        // Check if the user is an admin if so delete the review
        if (auth()->user()->role === 'admin') {
            $review = Review::findOrFail($id);
            $review->delete();
            return response()->json(['message' => 'Review deleted successfully']);
        }

        // Find the review if it exist and belongs to the user delete it if not return 404 not found
        $review = auth()->user()->reviews()->findOrFail($id);
        $review->delete();
        return response()->json(['message' => 'Review deleted successfully']);
    }


    // Get all reviews for a course
    public function courseReviews($id)
    {
        // Find the course if not found return 404 not found
        $course = Course::findOrFail($id);

        // Get all the reviews of the course
        return response()->json($course->reviews);
    }
}
