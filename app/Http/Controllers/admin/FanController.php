<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Hash;
use Gate;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ImportCustomer;
use App\Exports\ExportUser;
use Symfony\Component\HttpFoundation\Response;
use DB;
use App\Models\User;
use App\Models\Role;
class FanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        abort_if(Gate::denies('user_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $title = "Users";
        $buton_name = "Add New";
        $users = User::all();
        $pagination=10;
        if(isset($_GET['paginate'])){
            $pagination=$_GET['paginate'];
        }
         $q=User::select('*')->orderBy('id','DESC');
            if($request->search){
                $q->where('first_name', 'like', "%$request->search%");
            }
            $users=$q->paginate($pagination)->withQueryString();
            return view('admin.users.index3', compact('users','title','buton_name'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        abort_if(Gate::denies('user_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

         $title = "User Add";
         $roles = Role::all()->pluck('title', 'id');
         return view('admin.fan.edit', compact('roles','title'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $password = Hash::make($request->password);

        $user = User::updateOrCreate(['id'=>$request->id],[

            'first_name'    => $request->first_name,
            'last_name'     => $request->last_name,
            'email'         => $request->email,
            'password'      => $password,
            'dob'           => $request->dob,
            'gender'        => $request->gender,
            'phone'         => $request->phone,
            'user_status'   => $request->user_status,

        ]);
        $user->roles()->sync($request->input('role'));
        return redirect()->route('dashboard.fans.index');
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
        abort_if(Gate::denies('user_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
         $title = "Fan Edit";

        $roles = Role::all()->pluck('title', 'id');

         $user = User::findOrFail($id);
        $user->load('roles');
        return view('admin.fan.edit', compact('roles', 'user','title'));
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
    public function destroy($id)
    {
        //
    }
}
