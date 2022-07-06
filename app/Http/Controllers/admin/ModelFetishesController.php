<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ModelFetishes;

class ModelFetishesController extends Controller
{
    public function index()
    {
        $d['title'] = "Model Fetishes";
        $d['buton_name'] = "ADD NEW";
        $pagination=10;
        if(isset($_GET['paginate'])){
            $pagination=$_GET['paginate'];
        }
        $q = ModelFetishes::select('*');
        if(!empty($request->search)){
            $q->where('title', 'like', "%$request->search%");  
        }
        $d['model_fet']=$q->paginate($pagination)->withQueryString();
        return view('admin.model-fetishes.index',$d); 
    }
    public function create(){

        $d['title'] = "Add Model Fetishes";
        $d['model_fet'] = ModelFetishes::all();
        return view('admin.model-fetishes.create', $d);
    }
     public function store(Request $request)
    {
        // dd($request);
        $model_fet = ModelFetishes::updateOrCreate(['id'=>$request->id],[

            'title'     => $request->title,
            'status'    => $request->status,
        ]);
        $model_fet->update();

        return redirect()->route('dashboard.model-fetishes.index');
    }
    public function edit($id)
    {
        $d['title'] = "Model Fetishes";
        $d['buton_name'] = "Edit";
        $d['model_fet']=ModelFetishes::findorfail($id);
        return view('admin.model-fetishes.create', $d);
    }
    public function update(Request $request, $id)
    {

        //

    }
     public function destroy($id)
    {
        $model_fet = ModelFetishes::findOrFail($id);

        $model_fet->delete();

        return back();
    }
}
