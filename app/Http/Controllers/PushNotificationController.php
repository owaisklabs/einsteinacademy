<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\PushNotification;
use App\Models\User;
use Illuminate\Http\Request;

class PushNotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.push notifiaction.push-notification');
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
        $firebaseToken[] = 'cjuDBAY1QieJeFTtmv_ulw:APA91bG_TVyNw-TfLTRTzhBQdD34xXLW7BtpgA6hqAzgKHKPbIyJAmCg6msW70oA6Id4_C8MGnTKA0NIgnGASA2tE6zIQDBBY99ysPjoiCcrl4Wlf2vBpGVgxapVh3jSyBtGLmii2BJl';

        $SERVER_API_KEY = 'AAAAYybufUY:APA91bHGs-BAtISJaRhEWFCk79QKYrydolvdrl6loN1WhOmePN-PD8PLPzcB3sWD9iRO4Y5tQFR3g4poU_0cRkk0rhNePQt4OLnyBUsCCchzIgd9qpkVqw2pk5jEw2WybOLW3dMWaFnT';

        $data = [
            "registration_ids" => $firebaseToken,
            "notification" => [
                "title" => "Boss Reka",
                "body" => 'Boss reka ',
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

        dd($response);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\PushNotification  $pushNotification
     * @return \Illuminate\Http\Response
     */
    public function show(PushNotification $pushNotification)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PushNotification  $pushNotification
     * @return \Illuminate\Http\Response
     */
    public function edit(PushNotification $pushNotification)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PushNotification  $pushNotification
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PushNotification $pushNotification)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PushNotification  $pushNotification
     * @return \Illuminate\Http\Response
     */
    public function destroy(PushNotification $pushNotification)
    {
        //
    }
}
