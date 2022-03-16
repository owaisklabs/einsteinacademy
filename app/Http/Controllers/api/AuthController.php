<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Mail\OtpSendMail;
use App\Models\DeviceToken;
use App\Models\TeacherSubject;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;


class AuthController extends Controller
{
    public function register(Request $request)
    {
        // dd("here");
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email | unique:users,email',
            'phone_number' => 'required',
            'city' => 'required',
            'country' => 'required',
            'password' => 'required',
            'type' => 'required'
        ]);
        if($validator->fails())
        {
            return $this->sendError('validation error', $validator->errors());
        }
        // dd("here");
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = $request->password;
        if ($request->file('profile_img')){

        $file=$request->file('profile_img');
        $userProfileName = Str::random(20) . '.' . $file->getClientOriginalExtension();
        Storage::disk('public_user_profile')->put($userProfileName, \File::get($file));
        $user->profile_img =url('media/user_profile/'.$userProfileName);
        }

        if ($request->type == 0) {
            $user->type = User::STUDENT;
        }
        if ($request->type == 1) {
            $user->type = User::TEACHER;
        }
        if ($request->grade) {
            $user->grade_id = $request->grade;
        }

        $user->phone_number = $request->phone_number;
        $user->city = $request->city;
        $user->country = $request->country;
        $user->institue_name = $request->institue_name;
        $user_otp= rand(0, 9999);
        $details = [
            'token' => $user_otp
        ];
        Mail::to($request->email)->send(new OtpSendMail($details));
        $user->user_otp =$user_otp;
        $user->save();
        if ($request->subject) {
            foreach ($request->subject as $item) {
                $subject = new TeacherSubject();
                $subject->subject_id = $item;
                $subject->user_id = $user->id;
                $subject->save();
            }
        }
        return $this->formatResponse('success','user register otp sent',$user_otp);
    }
    public function resendOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);
        if($validator->fails())
        {
            return $this->sendError('validation error', $validator->errors());
        }

        $user_otp= rand(0, 9999);
        $details = [
            'token' => $user_otp
        ];
        $user=User::where('email',$request->email)->first();
        $user->user_otp =$user_otp;
        $user->save();
        return $this->formatResponse('success','user register otp sent',$user_otp);
    }
    public function verifyOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
            'otp_code' => 'required |max:6',
            'device_token' => 'required',
        ]);
        if($validator->fails()){
            return $this->sendError('validation error', $validator->errors());
        }
        $user = User::where('email',$request->email)->where('user_otp',$request->otp_code)->first();
        // dd($user);
        if($user)
        {
            if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){
                $user = User::find(Auth::id());
                $user->email_verified_at= Carbon::now();
                $user->save();
                $user_token = new DeviceToken();
                $user_token->user_id = Auth::id();
                $user_token->device = $request->device_token;
                $user_token->save();
                $success['user'] =  User::where('id',Auth::id())->with('grade','subjects')->get();
                $success['token'] =  $user->createToken('MyApp')->accessToken;
                return $this->formatResponse('success','user-login sucessfully',$success);
            }
            return $this->formatResponse('error','credentials  not match',null,400);
        }
        return $this->formatResponse('error','OTP not match',null,400);

    }
    public function SignIn(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);
        if($validator->fails()){
            return $this->sendError('validation error', $validator->errors());
        }

        if(Auth::attempt(['email' => $request->email, 'password' => $request->password]))
        {
            $user = Auth::user();
            if(!$user->email_verified_at)
            {
                return $this->formatResponse('error','Email not Verify',null,403);
            }
            if($user->status === User::BLOCK)
            {
                return $this->formatResponse('error','User is Blocked',null,403);
            }
            $user_token = new DeviceToken();
            $user_token->user_id = Auth::id();
            $user_token->device = $request->device_token;
            $user_token->save();
            $success['token'] =  $user->createToken('MyApp')->accessToken;
            $success['user'] =  User::where('id',Auth::id())->with('grade','subjects')->first();
            // return response()->json([
            //     'data' => $success,
            // ]);
            return $this->formatResponse('success','user-login sucessfully',$success);
        }
        else
        {
            return $this->formatResponse('error','credentials  not match',null,400);
        }
    }
    public function forgetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);
        if($validator->fails())
        {
            return $this->sendError('validation error', $validator->errors());
        }

        $user= User::where('email',$request->email)->first();
        $user_otp= rand(0, 9999);
        $details = [
            'token' => $user_otp,
        ];
        Mail::to($request->email)->send(new OtpSendMail($details));
        $user->user_otp =$user_otp;
        $user->save();
        return $this->formatResponse('success','OTP code sent on email',$user_otp);

    }
    public function verifyOtpForgetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'new_password' => 'required',
            'otp_code' => 'required',
        ]);
        if($validator->fails())
        {
            return $this->sendError('validation error', $validator->errors());
        }
        $user = User::where('email',$request->email)->where('user_otp',$request->otp_code)->first();
        if(!$user){
            return $this->formatResponse('error','credential not match');
        }
        else{
            // return $user;
            $new_password = $request->new_password;
            $user->password =$new_password;
            $user->save();
            $credentials = [
                'email' => $request['email'],
                'password' => $new_password,
            ];
            if(Auth::attempt($credentials)){
                $user = Auth::user();
                $success['token'] =  $user->createToken('MyApp')->accessToken;
                $success['user'] =  User::where('id',Auth::id())->with('grade','subjects')->get();
                return $this->formatResponse('success','user-login sucessfully',$success);
            }

        }
        // return $user;
    }
    public function test()
    {
        // return 'hello';
        $data =[
            'user'=>User::find(Auth::id()),
            'imageUrl'=>User::find(Auth::id())->getFirstMedia('profile_images')->getFullUrl(),
        ];
        return $this->formatResponse('sucess',null,$data);

    }
}
