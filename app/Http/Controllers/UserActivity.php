<?php

namespace App\Http\Controllers;

use App\Models\StudyMaterialRating;
use App\Models\StudyNotesRating;
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
        if($validator->fails()){
            return $this->sendError('validation error', $validator->errors());
        }
        if($request->type =="study-notes"){
            $studyNotesRating = new  StudyNotesRating();
            $studyNotesRating->study_notes_id = $request->id;
            $studyNotesRating->rating = $request->rating;
            $studyNotesRating->user_id = Auth::id();
            $studyNotesRating->save();
            return $this->formatResponse('success','rating add sucessfully');
        }
        if($request->type =="study-material"){
            $studyMaterialRating = new  StudyMaterialRating();
            $studyMaterialRating->study_notes_id = $request->id;
            $studyMaterialRating->rating = $request->rating;
            $studyMaterialRating->user_id = Auth::id();
            $studyMaterialRating->save();
            return $this->formatResponse('success','rating add sucessfully');
        }
        if($request->type =="past-paper"){

        }

    }
}
