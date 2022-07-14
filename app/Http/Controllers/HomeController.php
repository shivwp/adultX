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
use Hash;

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
        // main
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('index');
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

      

        return redirect()->back();
    }
}
