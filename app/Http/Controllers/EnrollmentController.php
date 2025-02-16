<?php

namespace App\Http\Controllers;

use App\Models\Enrollment;
use Illuminate\Http\Request;
use App\Models\Course;

class EnrollmentController extends Controller
{

    // Enroll in a course for students only 
    public function enroll(Request $request)
    {

        //check that the user is a student
        if (auth()->user()->role !== 'student') {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        //validate the request data
        $request->validate([
            'course_id' => 'required|exists:courses,id',
        ]);

        $course = Course::findOrFail($request->course_id);


        //check if the user is already enrolled in the course
        if (auth()->user()->enrollments()->where('course_id', $request->course_id)->exists()) {
            return response()->json(['message' => 'Already enrolled'], 400);
        }

        //create a new enrollment
        $enrrollment = auth()->user()->enrollments()->create([
            'course_id' => $request->course_id,
        ]);

        return response()->json(['message' => $enrrollment, 201]);
    }


    // Unenroll from a course for students only
    public function unenroll($id)
    {
        //check that the user is a student
        if (auth()->user()->role !== 'student') {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        //find the enrollment and delete it ifnot found return 404 not found
        $enrollment = auth()->user()->enrollments()->findOrFail($id);
        $enrollment->delete();
        return response()->json(['message' => 'Unenrolled successfully']);
    }


    // Get all courses that the student  is enrolled in 
    public function myCourses()
    {
        //check that the user is a student
        if (auth()->user()->role !== 'student') {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        //get all the enrollments of the authenticated user
        $courses = auth()->user()->enrollments;
        return response()->json($courses);
    }

    // Set the state of an enrollment for instructors only
    public function setState($id, Request $request)
    {
        //validate the request data
        $request->validate([
            'status' => 'required|in:pending,approved,rejected',
        ]);

        //check that the user is an instructor
        if (auth()->user()->role !== 'instructor') {
            return response()->json(['message' => 'Unauthorized'], 401);
        }


        // find the enrollment and it is course 
        $enrollment = Enrollment::findOrFail($id);
        $course = $enrollment->course;

        //check if the course belongs to the authenticated instructor 
        if ($course->instructor_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        //update the state of the enrollment
        $enrollment->status = $request->status;
        $enrollment->save();
        return response()->json(['message' => 'State updated successfully']);
    }


    // Get all enrollments of a course for instructors only
    public function courseEnrollments($id)
    {
        //check that the user is an instructor
        if (auth()->user()->role !== 'instructor') {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        //find the course and get all its enrollments if the course does not belong to the authenticated instructor return 404 not found 
        $course = auth()->user()->courses()->findOrFail($id);
        $enrollments = $course->enrollments;
        return response()->json($enrollments);
    }
}
