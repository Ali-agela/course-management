<?php

namespace App\Http\Controllers;

use App\Models\Enrollment;
use Illuminate\Http\Request;
use App\Models\Course;

class EnrollmentController extends Controller
{
    public function enroll(Request $request)
    {

        if (auth()->user()->role !== 'student') {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        $request->validate([
            'course_id' => 'required|exists:courses,id',
        ]);

        $course = Course::find($request->course_id);
        if (!$course) {
            return response()->json(['message' => 'Course not found'], 404);
        }

        if (auth()->user()->enrollments()->where('course_id', $request->course_id)->exists()) {
            return response()->json(['message' => 'Already enrolled'], 400);
        }

        // $enrrollment = Enrollment::create([
        //     'student_id' => auth()->id(),
        //     'course_id' => $request->course_id,
        // ]);
        $enrrollment = auth()->user()->enrollments()->create([
            'course_id' => $request->course_id,
        ]);

        return response()->json(['message' => $enrrollment]);
    }

    public function unenroll($id)
    {
        if (auth()->user()->role !== 'student') {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        $enrollment = auth()->user()->enrollments()->findOrFail($id);
        $enrollment->delete();
        return response()->json(['message' => 'Unenrolled successfully']);
    }

    public function myCourses()
    {
        if (auth()->user()->role !== 'student') {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        $courses = auth()->user()->enrollments;
        return response()->json($courses);
    }

    public function setState($id, Request $request)
    {
        $request->validate([
            'status' => 'required|in:pending,approved,rejected',
        ]);

        if (auth()->user()->role !== 'instructor') {
            return response()->json(['message' => 'Unauthorized'], 401);
        }


        $enrollment = Enrollment::findOrFail($id);
        $course = $enrollment->course;

        if ($course->instructor_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $enrollment->status = $request->status;
        $enrollment->save();
        return response()->json(['message' => 'State updated successfully']);
    }


    public function courseEnrollments($id)
    {
        if (auth()->user()->role !== 'instructor') {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $course = auth()->user()->courses()->findOrFail($id);
        $enrollments = $course->enrollments;
        return response()->json($enrollments);
    }
}
