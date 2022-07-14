<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Helper\Helper;
use App\Models\LogActivity;
use Validator;
use Auth;
use Mail;
use Session;
use App\Models\User;
use App\Models\Earning;
use App\Models\Blogs;
use Hash;
use DateTime;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        // main
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $dt = new DateTime();


        $fancount = User::whereHas('roles', function ($q) {
            $q->where('title', '=', "Fan");
       })->get();
        $modelcount = User::whereHas('roles', function ($q) {
            $q->where('title', '=', "Model");
       })->get();
       $date = \Carbon\Carbon::today()->subDays(7);
       $countusers = User::where('created_at', '>=', $date)->get();
       $total_earning = Earning::sum('amount');

        $today_date= $dt->format('Y-m-d');

       $today_earning = Earning::whereDate('created_at',$today_date)->sum('amount');

       $today_feeds = Blogs::whereDate('created_at',$today_date)->get();



       $OnlineModel = User::whereHas('roles', function ($q) {
        $q->where([
            'title'=> "Model",
            'is_online'=> "1",
        ]);
   })->get();
       $Onlinefan = User::whereHas('roles', function ($q) {
        $q->where([
            'title'=> "Fan",
            'is_online'=> "1",
        ]);
   })->get();

       $d['countusers']=$countusers;
       $d['fancount']=$fancount;
       $d['modelcount']=$modelcount;
       $d['total_earning']=$total_earning;
       $d['today_earning']=$today_earning;
       $d['OnlineModel']=$OnlineModel;
       $d['Onlinefan']=$Onlinefan;
       $d['today_feeds']=$today_feeds;
        return view('index', $d);
    }
     public function myTestAddToLog()
    {
        \Helper::addToLog('My Testing Add To Log.');
        dd('log insert successfully.');
    }


    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function logActivity()
    {  $data['title'] = "Logs";
        $logs = \Helper::logActivityLists();
        return view('logActivity',compact('logs'));
    }
    public function logsdelete($id)
    {
        $log = LogActivity::findOrFail($id);
        $log->delete();
        return back();
    }
    public function userlogin()
    {
        if (Auth::user()) {
            if (Auth::user()->roles->first()->title == 'admin' || Auth::user()->roles->first()->title == 'Admin') {
                return redirect('/admin');
            }
            if (Auth::user()->roles->first()->title == 'Fan' || Auth::user()->roles->first()->title == 'Fan') {
                return redirect('/fan-dashboard');
            }
            if (Auth::user()->roles->first()->title == 'Model' || Auth::user()->roles->first()->title == 'Model') {
                return redirect('/model-dashboard');
            }
        } else {
            return view('frontend.auth.login');
        }
    }
    public function logs()
    {
        return view('frontend.auth.login');
    }
    public function registeruser()
    {
        return view('auth.register');
    }
    public function postlogin(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = User::where('email', '=', $request->email)->first();

        if (!Auth::attempt(array('email' => $request->email, 'password' => $request->password))) {
            // Session::flash('error', "email or password not match!");
            // return redirect()->back();
            if ($request->ajax()) {
                return response()->json(['status' => false, 'msg' => 'User credential not match', 'redirect_to' => ''], 200);
            }
            return redirect()->back();
        }

        $remember = true;
        Auth::login($user, $remember);

        if (Auth::user()->roles->first()->title == 'admin' || Auth::user()->roles->first()->title == 'Admin') {
            if ($request->ajax()) {
                return response()->json(['status' => true, 'msg' => 'Admin log in ', 'redirect_to' => 'admin'], 200);
            }
            return redirect('/dashboard');
        }
        if (Auth::user()->roles->first()->title == 'Fan' || Auth::user()->roles->first()->title == 'Admin') {
            if ($request->ajax()) {
                return response()->json(['status' => true, 'msg' => 'Admin log in ', 'redirect_to' => 'Fan'], 200);
            }
            return redirect('/fan-dashboard');
        }
        if (Auth::user()->roles->first()->title == 'Model' || Auth::user()->roles->first()->title == 'Admin') {
            if ($request->ajax()) {
                return response()->json(['status' => true, 'msg' => 'Admin log in ', 'redirect_to' => 'Model'], 200);
            }
            return redirect('/model-dashboard');
        }
    }
    public function storeuser(Request $request)
    {
        //dd($request);
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:users',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'error' => $validator->errors()], 200);
        }
        $user = User::create([
            'first_name'=>$request->first_name,
            'email'=>$request->email,
            'password'=>Hash::make($request->password),
           ]);
        $user->roles()->sync(4);

        if ($request->ajax()) {
            //  //mail send to user
            //     $email = $request->email;
            //     if(!empty($email)){

            //         $details = ['email' => $email,'name' =>$request->name];
            //         Mail::send('mail.register', $details, function($message) use ($details){
            //             $message->to($details['email'])->subject('Zataat Registration')->from(env('MAIL_FROM_ADDRESS'));
            //         });
            //     }

            //     //mail send to admin
            //     $email = $request->email;
            //     $admindata = User::where('id',1)->first();
            //     $adminemail = $admindata->email;
            //     $name = $request->name;
            //     if(!empty($email)){
            //         $details = ['email' => $adminemail,'name' =>$request->name];
            //         Mail::send('mail.adminregister', $details,function($message) use ($details){
            //             $message->to($details['email'])->subject('Zataat Registration')->from(env('MAIL_FROM_ADDRESS'));
            //         });
            //     }






        }

        return redirect()->back();
    }
}
