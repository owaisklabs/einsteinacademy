<?php

namespace App\Http\Controllers;

use App\Models\StudyMaterial;
use App\Models\StudyMaterialMedia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class StudyMaterialController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $studyMaterial = StudyMaterial::with('user','grade','user','subject','Medias')->get();
        return $this->formatResponse('success', 'get all study materials', $studyMaterial);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'subject_id' => 'required |numeric',
            'grade_id' => 'required |numeric',
            'files' => 'required',
            'type' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->formatResponse('error', 'validation error', $validator->errors(), 400);
        }
        $studyMaterial = new StudyMaterial();
        $studyMaterial->user_id = Auth::id();
        $studyMaterial->title  = $request->title;
        $studyMaterial->grade_id  = $request->grade_id;
        $studyMaterial->subject_id  = $request->subject_id;
        $studyMaterial->type  = $request->type;
        $studyMaterial->save();
        if ($request->file('files')) {
            foreach ($request->file('files') as $file) {
                $attachSatResultName = Str::random(20) . '.' . $file->getClientOriginalExtension();
                Storage::disk('public_study_material')->put($attachSatResultName, \File::get($file));
                $media = new StudyMaterialMedia();
                $media->name = $request->file_name;
                $media->study_material_id = $studyMaterial->id;
                $media->path = asset('public/media/study_material/'.$attachSatResultName);
                $media->save();
            }
            return $this->formatResponse('sucess','Study material-add-sucessfully');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\StudyMaterial  $studyMaterial
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $studyMaterial = StudyMaterial::where('id',$id)
        ->with('grade','subject','Medias','user')->first();
        return $this->formatResponse('success', 'get all study materials', $studyMaterial);
    }



    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\StudyMaterial  $studyMaterial
     * @return \Illuminate\Http\Response
     */
    public function update()
    {
        return  "store";
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\StudyMaterial  $studyMaterial
     * @return \Illuminate\Http\Response
     */
    public function destroy()
    {
        return "delete";
    }
}
