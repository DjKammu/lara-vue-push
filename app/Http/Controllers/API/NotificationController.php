<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Token;

class NotificationController extends Controller
{
    public function saveToken(Request $request){
      
      $token = $request->input('token');
      $type = $request->input('type');
        
      if($token){
         
	       Token::updateOrCreate(
	        ['token' => $token],[
	         'type'  => $type ]
	       );

	    return response()->json(
	     ['status' => true, 'message' => 'Successfully Saved']
	    );   

      }

	  return response()->json(
	     ['status' => false, 'message' => 'Error']
	   );   
 
    }

    public function sendNotifications(Request $request){

    	$firebaseToken = Token::whereNotNull('token')->pluck('token')->all();
          
        $SERVER_API_KEY = env('FIREBASE_SERVER_KEY');

         $validator = \Validator::make($request->all(), [
		        'title' => 'required',
		        'body' => 'required',
		        'icon' => 'nullable',
		        'click_action' => 'nullable',
		    ]);

		if ($validator->fails()) {
		        $responseArr['status']  = false;
		        $responseArr['message'] = $validator->errors();
		        return response()->json($responseArr);
		 }

        $notification = [
                "title" => $request->title,
                "body" => $request->body,  
        ];

        if($request->filled('icon')){
          $notification['icon'] = $request->icon;
        }
        
        if($request->filled('click_action')){
          $notification['click_action'] = $request->click_action;
        }

        $data = [
            "registration_ids" => $firebaseToken,
            "notification" => $notification
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
  
        $responseArr['status']  = true;
        $responseArr['message'] = 'Successfully Sent';

        return response()->json($responseArr);
    }
}
