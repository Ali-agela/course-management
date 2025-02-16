<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lesson;
use App\Models\Course;
class LessonsController extends Controller
{
    public function addLesson(Request $request)
    {

        if (auth()->user()->role !== 'instructor') {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        $request->validate([
            'title' => 'required|string',
            'content' => 'required|string',
            'course_id' => 'required|exists:courses,id',
        ]);

        $lesson = auth()->user()->courses()->findOrFail($request->course_id)->lessons()->create([
            'title' => $request->title,
            'content' => $request->content,
        ]);
        return response()->json($lesson);
    }

    public function updateLesson(Request $request, $id)
    {
        $request->validate([
            'title' => 'string',
            'content' => 'string',
        ]);
        $lesson = Lesson::findOrFail($id);
        if (auth()->user()->role !== 'instructor' || $lesson->course->instructor_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        $lesson->update($request->all());
        return response()->json($lesson);
    }

    public function deleteLesson($id)
    {
        $lesson = Lesson::findOrFail($id);
        if (auth()->user()->role !== 'instructor' || $lesson->course->instructor_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        $lesson->delete();
        return response()->json(['message' => 'Lesson deleted successfully']);
    }

    public function courseLessons($id)
    {
        $course = Course::findOrFail($id);
        return response()->json($course->lessons);
    }

}
