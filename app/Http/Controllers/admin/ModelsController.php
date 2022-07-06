<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\Models;
use App\Models\ModelCategory;
use App\Models\ModelEthnicity;
use App\Models\ModelFetishes;
use App\Models\ModelHair;
use App\Models\ModelLanguage;
use App\Models\ModelOrientation;
use App\Models\User;
use Hash;
use DB;

class ModelsController extends Controller
{
    public function index(){
        $d['title'] = "Model";
       
        // dd($d['model']);
        $d['buton_name'] = "ADD NEW";
        $pagination=10;
        if(isset($_GET['paginate'])){
            $pagination=$_GET['paginate'];
        }
        $q=DB::table('users')
                    ->leftjoin('role_user', 'role_user.user_id', '=', 'users.id')
                    ->leftjoin('models', 'models.user_id', '=', 'users.id')
                    ->select('users.*','models.*','users.id as users_auto_id')
                    ->where('role_user.role_id', '=', 6);
        if(!empty($request->search)){
            $q->where('title', 'like', "%$request->search%");  
        }
        $d['model']=$q->paginate($pagination)->withQueryString();
        return view('admin.models.index',$d); 
    }

    public function create(){

        $d['title'] = "Model Add";
        $d['roles'] = Role::all()->pluck('title', 'id');
        $d['model_cate'] = ModelCategory::where('status','active')->get();
        $d['model_ethnic'] = ModelEthnicity::where('status','active')->get();
        $d['model_fet'] = ModelFetishes::where('status','active')->get();
        $d['model_hair'] = ModelHair::where('status','active')->get();
        $d['model_lang'] = ModelLanguage::where('status','active')->get();
        $d['model_orient'] = ModelOrientation::where('status','active')->get();
        $d['model'] = DB::table('users')
                    ->leftjoin('role_user', 'role_user.user_id', '=', 'users.id')
                    ->leftjoin('models', 'models.user_id', '=', 'users.id')
                    ->select('users.*','models.*')
                    ->where('role_user.role_id', '=', 6)
                    ->get();

        return view('admin.models.create', $d);
    }
    public function store(Request $request)
    {
        // dd($request);
        
        $d['model'] = DB::table('users')
                    ->leftjoin('role_user', 'role_user.user_id', '=', 'users.id')
                    ->leftjoin('models', 'models.user_id', '=', 'users.id')
                    ->select('users.*','models.*')
                    ->where('role_user.role_id', '=', 6)
                    ->get();
        $password = Hash::make($request->password);
        $user = User::updateOrCreate(['id'=>$request->userid],[

            'first_name'    => $request->first_name,
            'last_name'     => $request->last_name,
            'dob'           => $request->dob,
            'email'         => $request->email,
            'password'      => $password,
            'phone'         => $request->phone,
            'city'          => $request->city,
            'state'         => $request->state,
            'gender'        => $request->gender,
            'user_status'   => $request->user_status,
            'discription'   => $request->description,
        ]);
        $user->roles()->sync(6);

        if($request->hasfile('profile_image'))
        {
            $file = $request->file('profile_image');
            $extention = $file->getClientOriginalExtension();
            $filename = time().'.'.$extention;
            $file->move('profile-image/', $filename);
            User::where('id',$user->id)->update([
                'profile_image' => $filename
            ]);
        }
        $user->update();

        $user_id = User::orderBy('created_at','desc')->first();

        $social_links = [];
        $social_links = [
            'twitter'       => $request->link1,
            'instagram'     => $request->link2,
            'snapchat'      => $request->link3,
            'spankpay'      => $request->link4,
            'website'       => $request->link5,
            'camsite'       => $request->link6,
        ];
        $models = Models::updateOrCreate(['id'=>$request->id],[

            'user_id'           => $user_id->id,
            'phone'             => $request->phone,
            'video'             => $request->video,
            'stage_name'        => $request->stage_name,
            'Orientation'       => $request->orientation,
            'Model_Category'    => $request->modelcategory,
            'Ethnicity'         => $request->ethnicity,
            'Language'          => $request->language,
            'Hair'              => $request->hair,
            'Fetishes'          => $request->fetishes,
            'url1'              => $request->url1,
            'url2'              => $request->url2,
            'url3'              => $request->url3,
            'cost_msg'          => $request->costmsg,
            'cost_pic'          => $request->costpicture,
            'cost_videomsg'     => $request->costvideo_msg,
            'cost_audiomsg'     => $request->costaudio_msg,
            'cost_audiocall'    => $request->costaudio_call,
            'cost_videocall'    => $request->cost_videocall,
            'socail_links'      => json_encode($social_links, true),
            
        ]);
        if($request->hasfile('gallery_image'))
        {
            $file1 = $request->file('gallery_image');
            foreach($file1 as $image)
            {
              $name =$image->getClientOriginalName();
              $destinationPath = 'gallery images';
              $image->move($destinationPath, $name);
              $gallery_image_name[] =  $name;
                  
            }
        }

        $result = [];
        // dd($request->gallery_images_old);
        $varimg = json_decode($request->gallery_images_old);

        if(!empty($gallery_image_name) && !empty($varimg)){

            $result = array_merge($gallery_image_name, $varimg);
        }
        else if(!empty($gallery_image_name)) {

            $result = $gallery_image_name;

        } else {
            $result = $varimg;
        }

        Models::where('id','=',$models->id)->update([
            'gallery_image' => json_encode($result,true)
        ]);

        $models->update();
        return redirect()->route('dashboard.models.index');
    }
    public function edit($id)
    {
        $d['title'] = "Model Edit";
        $d['buton_name'] = "Edit";
        $d['model_cate'] = ModelCategory::where('status','active')->get();
        $d['model_ethnic'] = ModelEthnicity::where('status','active')->get();
        $d['model_fet'] = ModelFetishes::where('status','active')->get();
        $d['model_hair'] = ModelHair::where('status','active')->get();
        $d['model_lang'] = ModelLanguage::where('status','active')->get();
        $d['model_orient'] = ModelOrientation::where('status','active')->get();
        $d['model'] = User::leftjoin('models', 'models.user_id', '=', 'users.id')
                    ->select('users.*','models.*','users.id as users_auto_id')
                    ->where('models.user_id', '=', $id)
                    ->where('users.id', '=', $id)
                    ->first();
        // dd($d['model']);
        return view('admin.models.create', $d);
    }

    public function update(UpdateUserRequest $request, User $user)
    {

    }

    public function show()
    {

    }
    public function destroy($id)
    {
        $user_delete = User::where('id', $id)->delete();
        $model_delete = Models::where('user_id', $id)->delete();
        return back();
    }

}
