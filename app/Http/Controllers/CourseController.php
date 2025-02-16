<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        $courses = Course::all();
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
        if($request->sort){
            $courses = $courses->sortBy($request->sort);
        }
        return response()->json($courses);
    }

    public function show($id)
    {
        $course = Course::find($id);
        return response()->json($course);
    }

    public function createCourse(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'duration' => 'required|string',
            'category' => 'required|in:web development,mobile development,networking,security,data science,machine learning,AI,blockchain',
        ]);
        if (auth()->user()->role !== 'instructor') {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        $course = Course::create([
            'title' => $request->title,
            'description' => $request->description,
            'category' => $request->category,
            'price' => $request->price,
            'duration' => $request->duration,
            'instructor_id' => auth()->id(),
        ]);

        return response()->json($course);
    }

    public function updateCourse(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'duration' => 'required|string',
            'category' => 'required|in:web development,mobile development,networking,security,data science,machine learning,AI,blockchain',
        ]);
        if (auth()->user()->role !== 'instructor') {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
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
    public function deleteCourse($id)
    {
        if (auth()->user()->role !== 'instructor') {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        $course = auth()->user()->courses()->findOrFail($id);
        $course->delete();
        return response()->json(['message' => 'Course deleted successfully']);
    }

    public function instructorCourses()
    {
        if (auth()->user()->role !== 'instructor') {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        $courses = auth()->user()->courses;
        return response()->json(auth()->user()->with('courses')->get());
    }
}
