<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ModelOrientation;
use Illuminate\Support\Str;
use DB;

class ModelOrientationController extends Controller
{
    public function index()
    {
        $d['title'] = "Model Orientation";
        $d['buton_name'] = "ADD NEW";
        $pagination=10;
        if(isset($_GET['paginate'])){
            $pagination=$_GET['paginate'];
        }
        $q = ModelOrientation::select('*');
        if(!empty($request->search)){
            $q->where('title', 'like', "%$request->search%");  
        }
        $d['model_orient']=$q->paginate($pagination)->withQueryString();
        return view('admin.model-orientation.index',$d); 
    }
    public function create(){

        $d['title'] = "Add Model Orientation";
        $d['model_orient'] = ModelOrientation::all();
        return view('admin.model-orientation.create', $d);
    }
     public function store(Request $request)
    {
        // dd($request);
        $model_orient = ModelOrientation::updateOrCreate(['id'=>$request->id],[

            'title'     => $request->title,
            'status'    => $request->status,
        ]);
        $model_orient->update();

        return redirect()->route('dashboard.model-orientation.index');
    }
    public function edit($id)
    {
        $d['title'] = "Model Orientation";
        $d['buton_name'] = "Edit";
        $d['model_orient']=ModelOrientation::findorfail($id);
        return view('admin.model-orientation.create', $d);
    }
    public function update(Request $request, $id)
    {

        //

    }
     public function destroy($id)
    {
        $model_orient = ModelOrientation::findOrFail($id);

        $model_orient->delete();

        return back();
    }
}
