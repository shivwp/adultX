<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Notifications;
use App\Models\UserDeviceToken;
use Validator;
use Auth;
use DB;
use Carbon;



class NotificationsApiController extends Controller
{
  
    public function index(Request $request)
    {
        if (Auth::guard('api')->check()) {
            $user = Auth::guard('api')->user();
        } 

        $user_id = $user->id;

        $Notifications = Notifications::select('title','body','image','status','created_at')->where('user_id',$user_id)->orWhere('user_id','0')->get();
        if(count($Notifications)>0){
            foreach($Notifications as $key => $val){
                    $val->image = url('notification-image/'.$val->image);
                    $Notifications[$key]['created_date'] = date("d-F-Y", strtotime($val->created_at));
                    // $val->created_at  = date("d F Y", strtotime($val->created_at));
                    // dd($val->created_at);
                    unset($val->created_at);
            }
            return response()->json([ 'status'=> true ,'message' => "success",'data'=>$Notifications], 200);
        }
        else{
           return response()->json([ 'status'=> false ,'message' => "unsuccess"], 200);  
        }

        

      
    }

    public function sendNOtification(Request $request){
        if (Auth::guard('api')->check()) {
            $user = Auth::guard('api')->user();
        } 

        $user_id = $user->id;
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'body' => 'required',
            //'sender_id' => 'required',
            //'image' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => implode("", $validator->errors()->all())], 200);
        } 
        $url=null;
         $Notification = Notifications::updateOrCreate(['id' => $request->id], [
            'title'         => $request->title,
            'arab_title'         => $request->arab_title,
            'user_id'       => $user_id,
            'body'          => $request->body,
            'arab_body'          => $request->arab_body,
            'type'          => 'promotional',
        ]);
         if ($request->image) { 
            $img = 'data:image/jpeg;base64,'.$request->image;
              $path = 'notification-image/';
        $image = $this->createImage($img,$path);
         $Notification->update([
                'image' => $image
            ]);
         $url = url($image);
        }
       
         //firebase token
        $firebaseToken =UserDeviceToken::whereNotNull('device_id')
                    ->distinct('device_id')
                    ->pluck('device_id')
                    ->all();
         $SERVER_API_KEY = env('NOTIFICATION_SERVER_KEY');
        $this->notifications($firebaseToken,$SERVER_API_KEY,$request->title,$request->body,$url);
     return response()->json([ 'status'=> true ,'message' => "success"], 200); 

       
    }


    public function notifications($firebaseToken,$SERVER_API_KEY,$title,$body,$url){
        
        $data = [
            "registration_ids" => $firebaseToken,
            "notification" => [
                "title" => $title,
                "body" => $body,
                "image" =>$url,
               // "image" =>"https://ps.w.org/wp-notification-bell/assets/icon-256x256.png",
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
        //dd($response);
    }
     public function createImage($img, $folderPath, $key = 0)
    {
        $image_parts = explode(";base64,", $img);
        $image_type_aux = explode("image/", $image_parts[0]);
        $image_type = $image_type_aux[1];
        $image_base64 = base64_decode($image_parts[1]);
        $file = $folderPath . uniqid() . "_" . $key . 'gallery_image.' . $image_type;
        file_put_contents($file, $image_base64);
        return $file;

    }


}



