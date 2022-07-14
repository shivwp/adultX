<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Models;
use Carbon\Carbon;

class FrontendController extends Controller
{
    public function index()
    {
        $a=Carbon::now()->subDays(7);

        $d['online']=Models::join('users','users.id','=','models.user_id')->select('models.*','users.*')->where('users.is_online','=',1)->get();
        $d['new']=Models::join('users','users.id','=','models.user_id')->select('models.*','users.*')->orderBy('users.created_at','asc')->take(4)->get();        
        $d['phone']=Models::join('users','users.id','=','models.user_id')->select('models.*','users.*')->orderBy('users.created_at','asc')->where('models.phone','=',1)->take(4)->get();
        $d['video']=Models::join('users','users.id','=','models.user_id')->select('models.*','users.*')->orderBy('users.created_at','asc')->where('models.video','=',1)->take(4)->get();
        $d['featured']=Models::join('users','users.id','=','models.user_id')->select('models.*','users.*')->orderBy('users.created_at','asc')->where('models.featured','=',1)->get();
        $d['trending']=Models::join('users','users.id','=','models.user_id')->select('models.*','users.*')->orderBy('users.created_at','asc')->where('models.trending','=',1)->take(4)->get();
        $d['explore']=Models::join('users','users.id','=','models.user_id')->select('models.*','users.*')->orderBy('users.created_at','asc')->where('models.explore','=',1)->take(4)->get();
        return view('frontend.home',$d);
    }
}
