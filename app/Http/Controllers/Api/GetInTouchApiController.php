<?php



namespace App\Http\Controllers\Api;



use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Models\GetInTouch;

use App\Models\User;

use Illuminate\Support\Facades\Mail;
use App\Mail\Mailtemp;
use App\MailTemplate;
use App\Models\Mails;
use App\Mail\Signup;

use Validator;

use Auth;

class GetInTouchApiController extends Controller

{

    /**

     * Display a listing of the resource.

     *

     * @return \Illuminate\Http\Response

     */

    public function index()

    {



        



       

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

            'name' => 'required',

            'email' => 'required',

            'phone' => 'required',

            'platform' => 'required',

            'message' => 'required'

        ]);



        if ($validator->fails()) {

            return response()->json(['status' => false, 'message' => implode("", $validator->errors()->all())], 200);

        }



        GetInTouch::create([

            'name'      => $request->name,

            'email'     => $request->email,

            'phone'     => $request->phone,

            'company'   => $request->company,

            'message'   => $request->message,
            'platform'   => $request->platform,
            'address'   => $request->address,

        ]);
        //Mail to admin
        $basicinfo = [
                '{name}' => $request->name,
                '{email}' => $request->email,
                '{phone}' => $request->phone,
                '{message}' => $request->message
            ];
        $mail_data = Mails::where('msg_category', 'contact us')->first();

          $msg = $mail_data->message;
            foreach ($basicinfo as $key => $info) {
                $msg = str_replace($key, $info, $msg);
            }

        $config = ['from_email' => $mail_data->from_email,

            "reply_email" => $mail_data->reply_email,

            'subject' => $mail_data->subject, 

            'name' => $mail_data->name,

            'message'=>$mail_data->message,

        ];

        $admin = User::where('id', '1')->first();

         Mail::to($admin->email)->send(new Mailtemp($config));



        return response()->json(['status' => true,'message' => "success"], 200);

        

        

    }



    /**

     * Display the specified resource.

     *

     * @param  int  $id

     * @return \Illuminate\Http\Response

     */

    public function show($id)

    {

        //

    }



    /**

     * Show the form for editing the specified resource.

     *

     * @param  int  $id

     * @return \Illuminate\Http\Response

     */

    public function edit($id)

    {

        //

    }



    /**

     * Update the specified resource in storage.

     *

     * @param  \Illuminate\Http\Request  $request

     * @param  int  $id

     * @return \Illuminate\Http\Response

     */

    public function update(Request $request, $id)

    {

        //

    }



    /**

     * Remove the specified resource from storage.

     *

     * @param  int  $id

     * @return \Illuminate\Http\Response

     */

    public function destroy(Request $request)

    {

       

    }

}

