<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;

class CourseController extends Controller
{
    // Get all courses with filters  
    public function index(Request $request)
    {
        //fetch all courses
        $courses = Course::all();
        //filter courses based on request parameters
        //category, price, title, description, instructor_id, sort
        if ($request->category) {
            $courses = $courses->where('category', $request->category);
        }
        if ($request->price) {
            $courses = $courses->where('price', '<=', $request->price);
        }
        if ($request->title) {
            $courses = $courses->filter(function ($course) use ($request) {
                return stripos($course->title, $request->title) !== false;
            });
        }
        if ($request->description) {
            $courses = $courses->filter(function ($course) use ($request) {
                return stripos($course->description, $request->description) !== false;
            });
        }
        if ($request->instructor_id) {
            $courses = $courses->where('instructor_id', auth()->id());
        }
        if ($request->sort) {
            $courses = $courses->sortBy($request->sort);
        }

        return response()->json($courses);
    }

    // Get a single course
    public function show($id)
    {
        //fetch a single course if fails returns 404 not found 
        $course = Course::findOrFail($id);
        return response()->json($course);
    }

    // Create a new course only for instructors and admins 
    public function createCourse(Request $request)
    {
        //validate the request data
        $request->validate([
            'title' => 'required|string',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'duration' => 'required|string',
            'category' => 'required|in:web development,mobile development,networking,security,data science,machine learning,AI,blockchain',
        ]);
        //check if the user is an instructor
        if (auth()->user()->role !== 'instructor') {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        //create a new course
        $course = Course::create([
            'title' => $request->title,
            'description' => $request->description,
            'category' => $request->category,
            'price' => $request->price,
            'duration' => $request->duration,
            'instructor_id' => auth()->id(),
        ]);

        return response()->json($course, 201);
    }

    // Update a course only for instructors and admins
    public function updateCourse(Request $request, $id)
    {
        //validate the request data
        $request->validate([
            'title' => 'required|string',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'duration' => 'required|string',
            'category' => 'required|in:web development,mobile development,networking,security,data science,machine learning,AI,blockchain',
        ]);
        //check if the user is an instructor
        if (auth()->user()->role !== 'instructor') {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        //find the course then updates it   if not found returns 404 not found
        $course = auth()->user()->courses()->findOrFail($id);
        $course->update([
            'title' => $request->title,
            'description' => $request->description,
            'category' => $request->category,
            'price' => $request->price,
            'duration' => $request->duration,
        ]);

        return response()->json($course);
    }

    // Delete a course only for instructors and admins
    public function deleteCourse($id)
    {
        //check if the user is an instructor
        if (auth()->user()->role !== 'instructor') {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        //find the course then deletes it   if not found returns 404 not found
        $course = auth()->user()->courses()->findOrFail($id);
        $course->delete();
        return response()->json(['message' => 'Course deleted successfully']);
    }



    // Get all courses of the authenticated  instructor
    public function instructorCourses()
    {
        //check if the user is an instructor
        if (auth()->user()->role !== 'instructor') {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        //fetch all courses of the authenticated instructor
        $courses = auth()->user()->courses;
        return response()->json(auth()->user()->with('courses')->get());
    }
}
