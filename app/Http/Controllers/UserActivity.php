<?php

namespace App\Http\Controllers;

use App\Models\Followe;
use App\Models\Notification;
use App\Models\ReportUser;
use App\Models\StudyMaterial;
use App\Models\StudyMaterialRating;
use App\Models\StudyNote;
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

            $studyNote = StudyNote::find($request->id);
            $user = $studyNote->user;
            $token=[];
            foreach ($studyNote->user->userToken as $item){
                array_push($token,$item->device);
            }
            $firebaseToken =   $token;
            $username = Auth::user()->name;
            $body = $username." Rated your Study Notes";
            if($firebaseToken && $user->rating_notification ==1 ){
                $SERVER_API_KEY = 'AAAAYybufUY:APA91bHGs-BAtISJaRhEWFCk79QKYrydolvdrl6loN1WhOmePN-PD8PLPzcB3sWD9iRO4Y5tQFR3g4poU_0cRkk0rhNePQt4OLnyBUsCCchzIgd9qpkVqw2pk5jEw2WybOLW3dMWaFnT';
                $data = [
                    "registration_ids" => $firebaseToken,
                    "notification" => [
                        "title" => "Rating Notification",
                        "body" => $body,
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
                $notification = new Notification();
                $notification->user_id = $user->id;
                $notification->title = $username;
                $notification->body = " rated your Study Notes";
                $notification->save();
                return $this->formatResponse('success','ratting-add-successfully');
            }
            return $this->formatResponse('success','ratting-add-successfully');

        }
        if ($request->type == "study-material") {
            $studyMaterialRating = new  StudyMaterialRating();
            $studyMaterialRating->study_material_id = $request->id;
            $studyMaterialRating->rating = $request->rating;
            $studyMaterialRating->user_id = Auth::id();
            $studyMaterialRating->save();
            $studyMaterial = StudyMaterial::find($request->id);
             $user = $studyMaterial->user;
             $token=[];
             foreach ($studyMaterial->user->userToken as $item){
                 array_push($token,$item->device);
             }
              $firebaseToken =   $token;
//             return $firebaseToken;
            $username = Auth::user()->name;
            $body = $username." rated your Study Material";
            if($firebaseToken && $user->rating_notification ==1 ){
                $SERVER_API_KEY = 'AAAAYybufUY:APA91bHGs-BAtISJaRhEWFCk79QKYrydolvdrl6loN1WhOmePN-PD8PLPzcB3sWD9iRO4Y5tQFR3g4poU_0cRkk0rhNePQt4OLnyBUsCCchzIgd9qpkVqw2pk5jEw2WybOLW3dMWaFnT';
                $data = [
                    "registration_ids" => $firebaseToken,
                    "notification" => [
                        "title" => "Rating Notification",
                        "body" => $body,
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
                $notification = new Notification();
                $notification->user_id = $user->id;
                $notification->title = $username;
                $notification->body = " Rated your Study Material";
                $notification->save();
                return $this->formatResponse('success','ratting-add-successfully');

            }
            return $this->formatResponse('success', 'rating add successfully');
        }

    }
    public function follow($id)
    {
        $check = Followe::where('user_id', Auth::id())->where('follower_id', $id)->first();
        // return $check;
        if ($check== null) {
            $follower = new Followe();
            $follower->user_id = Auth::id();
            $follower->follower_id = $id;
            $user =User::find($id);
            $follower->save();
            $token=[];
            foreach ( $user->userToken as $item){
                array_push($token,$item->device);
            }

            $firebaseToken = $token ;

            $SERVER_API_KEY = 'AAAAYybufUY:APA91bHGs-BAtISJaRhEWFCk79QKYrydolvdrl6loN1WhOmePN-PD8PLPzcB3sWD9iRO4Y5tQFR3g4poU_0cRkk0rhNePQt4OLnyBUsCCchzIgd9qpkVqw2pk5jEw2WybOLW3dMWaFnT';
            $body = Auth::user()->name." started following you";
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
            $notification = new Notification();
            $notification->user_id = $user->id;
            $notification->title = $user->name;
            $notification->body = " started following you";
            $notification->save();

            return $this->formatResponse('sucess', 'follow successfull');
        } else {
            $check->delete();
            return $this->formatResponse('sucess', 'un follow successfull');
        }
    }
    public function userProfile($id)
    {
        // dd(auth()->user);
        $user = User::where('id', $id)->first();

        if ($user && $user->type == User::STUDENT) {
            $users = User::where('id', $id)
                ->with('studyNotes.grade', 'studyNotes.subject')
                ->first();
            $follower = $user->followers->count();
            $following = $user->followings->count();
            // dd($follower,$following);
            $users['user-follow'] = $follower;
            $users['user-following'] = $following;
            return $this->formatResponse('success', 'user-profile', $users);
        }
        if ($user && $user->type == User::TEACHER) {
            $users = User::where('id', $id)
                ->with('studyMaterials.grade', 'studyMaterials.subject')
                ->first();
            $follower = $user->followers->count();
            $following= $user->followings->count();
            $users['user-follow'] = $follower;
            $users['user-following'] = $following;
            return $this->formatResponse('success', 'user-profile', $users);
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
        $zoom->user_id =Auth::id();
        if($request->file('img')){
            $file = $request->file('img');
            $zoomImg = Str::random(20) . '.' . $file->getClientOriginalExtension();
            Storage::disk('public_zoom_img')->put($zoomImg, \File::get($file));
            $zoom->img = url('media/zoom_imgs/' . $zoomImg);
        }
        $zoom->save();
        return $this->formatResponse('success','zoom created successfully',$zoom);
    }
    public function updateZoomEvent(Request $request,$id)
    {
        $validator = Validator::make($request->all(), [
            'meeting_id' => 'required',
            'meeting_pass' => 'required',
        ]);
        // return $request->all();
        if ($validator->fails()) {
            return $this->sendError('validation error', $validator->errors());
        }
        $zoom = Zoom::find($id);
        $zoom->meeting_id = $request->meeting_id;
        $zoom->meeting_pass = $request->meeting_pass;
        $zoom->save();
        return $this->formatResponse('success','zoom created successfully',$zoom);
    }
    public function getZoomEvents()
    {
        $zoomEvents = Zoom::latest()->with('user')->get();
        return $this->formatResponse('success','zoom events get successfully',$zoomEvents);
    }
    public function deleteZoomEvents($id)
    {
        $zoomEvent = Zoom::where('id',$id)->delete();
        return $this->formatResponse('success','zoom events delete successfully');
    }
    public function followerList($id)
    {
        return User::where('id',$id)->select('id')->with('followers')->get();
    }
    public function followingList($id)
    {
        return User::where('id',$id)->select('id')->with(['followings'])->get();
    }
    public function reportActivity(Request $request)
    {
//        return ReportUser::all();
        $userActivity = new ReportUser();
        $userActivity->type = $request->type;
        $userActivity->activity_id = $request->id;
        $userActivity->user_id =  Auth::id();
        $userActivity->remarks =  $request->remarks;
        $userActivity->save();
        return $this->formatResponse('success','activity report successfully');
    }
    public function reportUser()
    {
        $reportUser = ReportUser::latest()->get();
        return view('admin.user.report_user',get_defined_vars());
    }
    public function blockUser()
    {
        $userBlock = User::where('status',User::BLOCK)->get();
        return view('admin.user.block_user',get_defined_vars());
    }
    public function setting(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'material_notification' => 'required',
            'following_notification' => 'required',
            'rating_notification' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->sendError('validation error', $validator->errors());
        }
        $user = User::find(Auth::id());
        $user->material_notification = $request->material_notification;
        $user->following_notification = $request->following_notification;
        $user->rating_notification = $request->rating_notification;
        $user->save();
        return $this->formatResponse('success','user setting is successfully changed',$user);
    }
    public function notification(){

        $notification = Notification::where('user_id',Auth::id())->get();
        return $this->formatResponse('success','notification get',$notification);
    }
    public function removeFollower($id){
        $check = Followe::where('user_id',  Auth::id())
            ->where('follower_id', $id)
            ->first();
        if (!$check){
            return $this->formatResponse('error','user not Found');
        }
        $check->delete();
        return $this->formatResponse('success','remove follower');
    }
}
