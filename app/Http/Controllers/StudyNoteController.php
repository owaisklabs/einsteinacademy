<?php

namespace App\Http\Controllers;

use App\Models\StudyNote;
use App\Models\StudyNotesMedia;
use App\Models\StudyNotesRating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;



class StudyNoteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $studyNotes = StudyNote::with('user','grade','subject','grade','Medias')->get();
        return $this->formatResponse('sucsess','all study note get',$studyNotes);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

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
        ]);
        if ($validator->fails()) {
            return $this->formatResponse('error', 'validation error', $validator->errors(), 400);
        }
        $studyNote = new StudyNote();
        $studyNote->user_id = Auth::id();
        $studyNote->title  = $request->title;
        $studyNote->grade_id  = $request->grade_id;
        $studyNote->subject_id  = $request->subject_id;
        $studyNote->save();
        if ($request->file('files')) {
            foreach ($request->file('files') as $file) {
                $attachSatResultName = Str::random(20) . '.' . $file->getClientOriginalExtension();
                Storage::disk('public_study_notes')->put($attachSatResultName, \File::get($file));
                $media = new StudyNotesMedia();
                $media->name = $request->file_name;
                $media->study_notes_id = $studyNote->id;
                $media->path = asset('public/media/study_notes/'.$attachSatResultName);
                $media->save();
            }
            return $this->formatResponse('sucess','Study material-add-sucessfully');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\StudyNote  $studyNote
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        $studyNotes['study-notes'] = StudyNote::where('id',$id)
        ->with('user','grade','subject','medias')
        ->first();
        $studyNotes['rating'] = StudyNotesRating::where('study_notes_id',$id)->avg('rating');
        if( $studyNotes['rating'] == NULL)
        $studyNotes['rating'] =0;
        return $this->formatResponse('success','study note get',$studyNotes);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\StudyNote  $studyNote
     * @return \Illuminate\Http\Response
     */
    public function edit(StudyNote $studyNote)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\StudyNote  $studyNote
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        return $id;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\StudyNote  $studyNote
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return $id;
    }
}
