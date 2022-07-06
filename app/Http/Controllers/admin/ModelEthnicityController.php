<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\ModelEthnicity;
use Illuminate\Http\Request;

class ModelEthnicityController extends Controller
{
    public function index()
    {
        $d['title'] = "Model Ethnicity";
        $d['buton_name'] = "ADD NEW";
        $pagination=10;
        if(isset($_GET['paginate'])){
            $pagination=$_GET['paginate'];
        }
        $q = ModelEthnicity::select('*');
        if(!empty($request->search)){
            $q->where('title', 'like', "%$request->search%");  
        }
        $d['model_ethnic']=$q->paginate($pagination)->withQueryString();
        return view('admin.model-ethnicity.index',$d); 
    }
    public function create(){

        $d['title'] = "Add Model Ethnicity";
        $d['model_ethnic'] = ModelEthnicity::all();
        return view('admin.model-ethnicity.create', $d);
    }
     public function store(Request $request)
    {
        // dd($request);
        $model_ethnic = ModelEthnicity::updateOrCreate(['id'=>$request->id],[

            'title'     => $request->title,
            'status'    => $request->status,
        ]);
        $model_ethnic->update();

        return redirect()->route('dashboard.model-ethnicity.index');
    }
    public function edit($id)
    {
        $d['title'] = "Model Ethnicity";
        $d['buton_name'] = "Edit";
        $d['model_ethnic']=ModelEthnicity::findorfail($id);
        return view('admin.model-ethnicity.create', $d);
    }
    public function update(Request $request, $id)
    {

        //

    }
     public function destroy($id)
    {
        $model_ethnic = ModelEthnicity::findOrFail($id);

        $model_ethnic->delete();

        return back();
    }
}
