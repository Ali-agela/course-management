<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lesson;
use App\Models\Course;
class LessonsController extends Controller
{
    
    // fuction to add a lesson to a course only for instructors
    public function addLesson(Request $request)
    {

        //check that the user is an instructor
        if (auth()->user()->role !== 'instructor') {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        //validate the request data
        $request->validate([
            'title' => 'required|string',
            'content' => 'required|string',
            'course_id' => 'required|exists:courses,id',
        ]);

        //create a new lesson
        $lesson = auth()->user()->courses()->findOrFail($request->course_id)->lessons()->create([
            'title' => $request->title,
            'content' => $request->content,
        ]);
        return response()->json($lesson);
    }


    // fuction to update a lesson only for instructors
    public function updateLesson(Request $request, $id)
    {
        //validate the request data
        $request->validate([
            'title' => 'string',
            'content' => 'string',
        ]);
        //find the lesson if not found return 404 not found
        $lesson = Lesson::findOrFail($id);

        //check that the user is an instructor and the instructor is the owner of the course
        if (auth()->user()->role !== 'instructor' || $lesson->course->instructor_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        //update the lesson
        $lesson->update($request->all());
        return response()->json($lesson);
    }


    // fuction to delete a lesson only for instructors
    public function deleteLesson($id)
    {
        //find the lesson if not found return 404 not found
        $lesson = Lesson::findOrFail($id);


        //check that the user is an instructor and the instructor is the owner of the course
        if (auth()->user()->role !== 'instructor' || $lesson->course->instructor_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        //delete the lesson
        $lesson->delete();
        return response()->json(['message' => 'Lesson deleted successfully']);
    }

    // fuction to get all lessons of a course
    public function courseLessons($id)
    {
        //find the course if not found return 404 not found
        $course = Course::findOrFail($id);

        //return all the lessons of the course
        return response()->json($course->lessons);
    }

}
