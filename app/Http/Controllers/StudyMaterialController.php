<?php

namespace App\Http\Controllers;

use App\Models\Followe;
use App\Models\Notification;
use App\Models\StudyMaterial;
use App\Models\User;
use App\Models\StudyMaterialMedia;
use App\Models\StudyMaterialRating;
use App\Models\StudyNotesRating;
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
        if (Auth::user()->type == User::TEACHER){
            $studyMaterial = StudyMaterial::where('user_id',Auth::id())->get();
            $studyMaterialdata =[];
            foreach ($studyMaterial as $key => $value) {
                $studyMaterialdatas['studymaterial']= StudyMaterial::where('id',$value->id)->with('grade','subject')->first();
                $studyMaterialdatas['user']= User::find($value->user_id);
                $studyMaterialdatas['is_follow']= User::isFollowed($value->user_id);
                $studyMaterialdatas['rating'] =$value->rating()->avg('rating');
                array_push($studyMaterialdata,$studyMaterialdatas);
            }
            return $studyMaterialdata;
        }
        else {
            $studyMaterial = StudyMaterial::with('grade','subject','rating')->get();
//            return $studyMaterial[0]->rating()->avg('rating');
//
//            return $studyMaterial;
            $studyMaterialdata = [];
            foreach ($studyMaterial as $key => $value) {
                $studyMaterialdatas['studymaterial'] = StudyMaterial::where('id',$value->id)->with('grade','subject')->first();
                $studyMaterialdatas['user'] = User::find($value->user_id);
                $studyMaterialdatas['is_follow'] = User::isFollowed($value->user_id);
                $studyMaterialdatas['rating'] =$value->rating()->avg('rating');
                array_push($studyMaterialdata, $studyMaterialdatas);
            }
            return $studyMaterialdata;
            return $this->formatResponse('success', 'get all study materials', $studyMaterial);
        }
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
        $user=User::find(Auth::id());
        $user_id= Followe::where('follower_id',$user->id)->pluck('user_id');
        $user= User::whereIn('id',$user_id)->get();
        $tokens =[];
        foreach ($user as $item){
            if($item->material_notification ==0)
                continue;
            foreach ($item->userToken as $token)
                array_push($tokens,$token->device) ;
        }
        $tokens;
        $firebaseToken = $tokens ;

        $SERVER_API_KEY = 'AAAAYybufUY:APA91bHGs-BAtISJaRhEWFCk79QKYrydolvdrl6loN1WhOmePN-PD8PLPzcB3sWD9iRO4Y5tQFR3g4poU_0cRkk0rhNePQt4OLnyBUsCCchzIgd9qpkVqw2pk5jEw2WybOLW3dMWaFnT';
        $body = Auth::user()->name." added Study Material";
        $data = [
            "registration_ids" => $firebaseToken,
            "notification" => [
                "title" => "Follow Notification",
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
            $notification->body = " Uploaded Study Material";
            $notification->save();
        }



        if ($request->file('files')) {
            foreach ($request->file('files') as $file) {
                $attachSatResultName = 'einstein_academy_material_'.Str::random(10).'.'.$file->getClientOriginalExtension();
                Storage::disk('public_study_material')->put($attachSatResultName,\File::get($file));
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
//        return Auth::id();
        $studyMaterial = StudyMaterial::where('id',$id)
        ->with('grade','subject','Medias','user')->first();
        $studyMaterial['rating'] = StudyMaterialRating::where('study_material_id',$id)->avg('rating');
        $studyMaterial['is_follow'] = User::isFollowed($studyMaterial->user->id);
        if( $studyMaterial['rating'] == NULL)
        $studyMaterial['rating'] =0;
        $studyMaterial['rating'] =   (float)number_format($studyMaterial['rating'], 2, '.', ' ');
//        return $studyMaterial;
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
    public function destroy(StudyMaterial $studyMaterial)
    {
        $studyMaterial->delete();
        return $this->formatResponse('success','study-material-delete');
    }
}
