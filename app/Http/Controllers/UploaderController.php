<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lesson;

class UploaderController extends Controller
{
    // upload user profile pic
    public function uploadProfilePic(Request $request)
    {

        //validate the request data
        $request->validate([
            'profile_photo_path' => 'required|image|mimes:jpeg,png,jpg,gif,svg',
        ]);


        //store the uploaded image   and update the photo path in the authentaced user
        $img = $request->file('profile_photo_path')->store('/users', 'public');
        $user = auth()->user();
        $user->update([
            'profile_photo_path' => $img,
        ]);

        //return a success message with the name of th stored image 
        return response()->json([
            'message' => 'image uploaded successfully',
            'image_name' => $img
        ]);

    }

    //upload video lesson
    public function uploadVideoLesson(Request $request, $id)
    {
        //validate the request data
        $request->validate([
            'video' => 'required|mimes:mp4,mov,ogg,qt|max:20000',
        ]);


        //find the lesson if not found return 404 not found
        $lesson = Lesson::findOrFail($id);


        //check that the user is an instructor and the instructor is the owner of the course
        if (auth()->user()->role !== 'instructor' || $lesson->course->instructor_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        //store the uploaded video and update the video path in the lesson
        $video = $request->file('video')->store('/lessons', 'public');
        $lesson->update([
            'video_url' => $video,
        ]);

        //return a success message with the name of th stored video
        return response()->json([
            'message' => 'video uploaded successfully',
            'video_name' => $video
        ]);



    }


}
