<?php

namespace App\Http\Controllers;

use App\Models\Followe;
use App\Models\StudyMaterialRating;
use App\Models\StudyNotesRating;
use App\Models\User;
use App\Models\Zoom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class UserActivity extends Controller
{
    public function rating(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'type' => 'required',
            'rating' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->sendError('validation error', $validator->errors());
        }
        if ($request->type == "study-notes") {
            $studyNotesRating = new  StudyNotesRating();
            $studyNotesRating->study_notes_id = $request->id;
            $studyNotesRating->rating = $request->rating;
            $studyNotesRating->user_id = Auth::id();
            $studyNotesRating->save();
            return $this->formatResponse('success', 'rating add sucessfully');
        }
        if ($request->type == "study-material") {
            $studyMaterialRating = new  StudyMaterialRating();
            $studyMaterialRating->study_material_id = $request->id;
            $studyMaterialRating->rating = $request->rating;
            $studyMaterialRating->user_id = Auth::id();
            $studyMaterialRating->save();
            return $this->formatResponse('success', 'rating add sucessfully');
        }
        if ($request->type == "past-paper") {
        }
    }
    public function follow($id)
    {
        $check = Followe::where('user_id', Auth::id())->where('follower_id', $id)->get();
        // return $check;
        if ($check->isEmpty()) {
            $user = User::find(Auth::id());
            $user->followers()->attach($id);
            return $this->formatResponse('sucess', 'follow sucessfull');
        } else {
            $user = User::find(Auth::id());
            $user->followers()->detach($id);
            return $this->formatResponse('sucess', 'un follow sucessfull');
        }
    }
    public function userProfile($id)
    {
        $student = User::where('id', $id)->first();
        $followers = $student->followers->count();
        $followings = $student->followings->count();
        if ($student && $student->type == User::STUDENT) {
            $student = User::where('id', $id)
                ->with('studyMaterials.grade', 'studyMaterials.subject')
                ->first();
            $student['followers'] = $followers;
            $student['followings'] = $followings;
            return $this->formatResponse('success', 'user-profile', $user);
        }
        if ($teacher && $teacher->type == User::TEACHER) {
            $teacher = User::where('id', $id)
                ->with('studyMaterials.grade', 'studyMaterials.subject')
                ->first();
            $followers = $teacher->followers->count();
            $followings = $teacher->followings->count();
            $teacher['followers'] = $followers;
            $teacher['followings'] = $followings;
            return $this->formatResponse('success', 'user-profile', $user);
        }
    }
    public function userProfileUpdate(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'phone_number' => 'required',
            'country' => 'required',
            'institute' => 'required',
        ]);
        // return $request->all();
        if ($validator->fails()) {
            return $this->sendError('validation error', $validator->errors());
        }
        $user = User::where('id', Auth::id())->first();

        $user->name = $request->name;
        $user->phone_number = $request->phone_number;
        $user->city = $request->city;
        $user->country = $request->country;
        $user->institue_name = $request->institute;
        $user->save();
        $userData = User::where('id', Auth::id())->with('grade', 'subjects')->first();
        return $this->formatResponse('success', 'user-get', $userData);
    }
    public function profilePicUpdate(Request $request)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'profile-img' => 'required|max:10000|mimes:jpg,jpeg,png'
        ]);
        // return $request->all();
        if ($validator->fails()) {
            return $this->sendError('validation error', $validator->errors());
        }
        $file = $request->file('profile-img');
        $user = User::find(Auth::id());
        Storage::disk('public_user_profile')->delete($user->profile_img);
        $userProfileName = Str::random(20) . '.' . $file->getClientOriginalExtension();
        Storage::disk('public_user_profile')->put($userProfileName, \File::get($file));
        $user->profile_img = url('media/user_profile/' . $userProfileName);
        $user->save();
        $user = User::where('id', Auth::id())
            ->with('grade', 'subjects')
            ->first();
        return $this->formatResponse('success', 'profile-imge-update', $user);
    }
    public function createZoomEvent(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'meeting_id' => 'required',
            'meeting_pass' => 'required',
            'date_time' => 'required',
        ]);
        // return $request->all();
        if ($validator->fails()) {
            return $this->sendError('validation error', $validator->errors());
        }
        $zoom = new Zoom();
        $zoom->title = $request->title;
        $zoom->description = $request->description;
        $zoom->date_and_time = $request->date_time;
        $zoom->meeting_id = $request->meeting_id;
        $zoom->meeting_pass = $request->meeting_pass;
        $zoom->user_id =Auth::id();
        if($request->file('img')){
            $file = $request->file('img');
            $zoomImg = Str::random(20) . '.' . $file->getClientOriginalExtension();
            Storage::disk('public_zoom_img')->put($zoomImg, \File::get($file));
            $zoom->img = url('media/zoom_imgs/' . $zoomImg);
        }
        $zoom->save();
        return $this->formatResponse('success','zoom created sucessfully',$zoom);
    }
    public function getZoomEvents()
    {
        $zoomEvents = Zoom::latest()->with('user')->get();
        return $this->formatResponse('success','zoom events get sucessfully',$zoomEvents);
    }
    public function deleteZoomEvents($id)
    {
        $zoomEvent = Zoom::where('id',$id)->delete();
        return $this->formatResponse('success','zoom events delete sucessfully');
    }
    public function followList($id)
    {
        return $id;
    }
}
