<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;

class ReviewsController extends Controller
{
    public function addReview(Request $request, $id)
    {
        $request->validate([
            $id => 'exists:courses,id',
            'rating' => 'required|numeric|min:1|max:5',
            'comment' => 'required|string',
        ]);
        if (auth()->user()->role !== 'student') {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        $review = auth()->user()->reviews()->create([
            'course_id' => $id,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);
        return response()->json($review);
    }

    public function updateReview(Request $request, $id)
    {
        $request->validate([
            'rating' => 'numeric|min:1|max:5',
            'comment' => 'string',
        ]);
        $review = auth()->user()->reviews()->findOrFail($id);
        $review->update($request->all());
        return response()->json($review);
    }

    public function deleteReview($id)
    {
        $review = auth()->user()->reviews()->findOrFail($id);
        $review->delete();
        return response()->json(['message' => 'Review deleted successfully']);
    }

    public function courseReviews($id)
    {
        $course = Course::findOrFail($id);
        return response()->json($course->reviews);
    }
}
