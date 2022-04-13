<?php

namespace App\Http\Controllers;

use App\Models\Followe;
use App\Models\Notification;
use App\Models\StudyMaterial;
use App\Models\StudyNote;
use App\Models\StudyNotesMedia;
use App\Models\StudyNotesRating;
use App\Models\User;
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
        $studyMaterial = StudyNote::with('grade','subject','syllabus')->get();
        $studyMaterialdata =[];
        foreach ($studyMaterial as $key => $value) {
            $studyMaterialdatas['studynotes']= StudyNote::where('id',$value->id)->with('grade','subject')->first();
            $studyMaterialdatas['user']= User::find($value->user_id);
                $studyMaterialdatas['rating'] =$value->rating()->avg('rating');
//            $studyMaterialdatas['is_follow']= User::isFollowed($value->user_id);
            array_push($studyMaterialdata,$studyMaterialdatas);
        }
        return $studyMaterialdata;
        return $this->formatResponse('success', 'get all study materials', $studyMaterial);
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
            'syllabus_id' => 'required',
            'grade_id' => 'required |numeric',
            'files' => 'required',
            'type' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->formatResponse('error', 'validation error', $validator->errors(), 400);
        }
        $studyNote = new StudyNote();
        $studyNote->user_id = Auth::id();
        $studyNote->title  = $request->title;
        $studyNote->grade_id  = $request->grade_id;
        $studyNote->subject_id  = $request->subject_id;
        $studyNote->syllabus_id  = $request->syllabus_id;
        $studyNote->type  = $request->type;
        $studyNote->save();
        $user=User::find(Auth::id());
        $user_id= Followe::where('follower_id',$user->id)->pluck('user_id');
        $user= User::whereIn('id',$user_id)->get();
        $tokens =[];
        foreach ($user as $item){
            if($item->material_notification == 0)
                continue;
            foreach ($item->userToken as $token)
                array_push($tokens,$token->device) ;
        }
        $tokens;
        $firebaseToken = $tokens ;

        $SERVER_API_KEY = 'AAAAYybufUY:APA91bHGs-BAtISJaRhEWFCk79QKYrydolvdrl6loN1WhOmePN-PD8PLPzcB3sWD9iRO4Y5tQFR3g4poU_0cRkk0rhNePQt4OLnyBUsCCchzIgd9qpkVqw2pk5jEw2WybOLW3dMWaFnT';
        $body = Auth::user()->name." added Student Notes";
        $data = [
            "registration_ids" => $firebaseToken,
            "notification" => [
                "title" => "Student Notes Notification",
                "body" =>  $body,
            ]
        ];
        $dataString = json_encode($data);

        $headers = [
            'Authorization: key=' . $SERVER_API_KEY,
            'Content-Type: application/json',
        ];

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);

        $response = curl_exec($ch);
        foreach ($user_id as $item ){
            $notification = new Notification();
            $notification->user_id = $item;
            $notification->title = Auth::user()->name;
            $notification->body = " Uploaded Study Notes";
            $notification->save();
        }
        if ($request->file('files')) {
            foreach ($request->file('files') as $file) {
                $attachSatResultName = 'einstein_academy_notes_'.Str::random(10) .'.'. $file->getClientOriginalExtension();
                Storage::disk('public_study_notes')->put($attachSatResultName, \File::get($file));
                $media = new StudyNotesMedia();
                $media->name = $request->file_name;
                $media->study_notes_id = $studyNote->id;
                $media->path = asset('public/media/study_notes/'.$attachSatResultName);
                $media->save();
            }
            return $this->formatResponse('success','Study material-add-sucessfully');
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

        $studyNotes = StudyNote::where('id',$id)
        ->with('user','grade','subject','medias','syllabus')
        ->first();
        $studyNotes['rating'] = StudyNotesRating::where('study_notes_id',$id)->avg('rating');
        //  $studyNotes['rating'] = (float) $studyNotes['rating'];
        $studyNotes['is_follow']= User::isFollowed($studyNotes->user->id);
         $studyNotes['rating'] =   (float)number_format($studyNotes['rating'], 2, '.', ' ');
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
    public function destroy(StudyNote $studyNote)
    {
        $studyNote->delete();
        return $this->formatResponse('success','study-material-delete');
    }
}
