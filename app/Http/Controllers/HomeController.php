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
        // $this->middleware('auth');
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
        request()->validate([


            'email' => 'required|email',
            'password' => 'required',
            'acceptbox'=>'required',
        ]);
        
        $user = User::where('email', '=', $request->email)->first();

        if (!Auth::attempt(array('email' => $request->email, 'password' => $request->password))) {         
            return redirect()->back();
        }

        $remember = true;
        Auth::login($user, $remember);
        $userdata=User::where('id','=',Auth::user()->id)->first();
        $userdata->is_online='1';
        $userdata->save();

        if (Auth::user()->roles->first()->title == 'admin' || Auth::user()->roles->first()->title == 'Admin') {
           
            return redirect('/dashboard');
        }
        if (Auth::user()->roles->first()->title == 'Fan' || Auth::user()->roles->first()->title == 'Admin') {
          
            return redirect('/fan-dashboard');
        }
        if (Auth::user()->roles->first()->title == 'Model' || Auth::user()->roles->first()->title == 'Admin') {
            
            return redirect('/model-dashboard');
        }
    }
    public function storeuser(Request $request)
    {

        request()->validate([
            'email' => 'required|email|unique:users',
            'first_name' => 'required',
            'password' => 'required',
            'readbox'=>'required',
        ]);

        $user = User::create([
            'first_name'=>$request->first_name,
            'email'=>$request->email,
            'password'=>Hash::make($request->password),
           ]);

        $user->roles()->sync(4);    
           
        


        return redirect()->back();
    }
}
