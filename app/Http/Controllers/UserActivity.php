<?php

namespace App\Http\Controllers;

use App\Models\Followe;
use App\Models\StudyMaterialRating;
use App\Models\StudyNotesRating;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

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
        $user = User::where('id', $id)->first();
        if ($user && $user->type == User::STUDENT) {
            $user = User::where('id', $id)
                ->with('studyMaterials.grade', 'studyMaterials.subject')
                ->first();
            return $this->formatResponse('success', 'user-profile', $user);
        }
        if ($user && $user->type == User::TEACHER) {
            $user = User::where('id', $id)
                ->with('studyMaterials.grade', 'studyMaterials.subject')
                ->first();
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
        $userData=User::where(';id',Auth::id())->first();
        return $this->formatResponse('success', 'user-get', $userData);
    }
    public function profilePicUpdate(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'profile-img' => 'required|max:10000|mimes:doc,docx'

            ,
        ]);
        // return $request->all();
        if ($validator->fails()) {
            return $this->sendError('validation error', $validator->errors());
        }
    }
}
