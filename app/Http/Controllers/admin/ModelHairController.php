<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ModelHair;

class ModelHairController extends Controller
{
    public function index()
    {
        $d['title'] = "Model Hair";
        $d['buton_name'] = "ADD NEW";
        $pagination=10;
        if(isset($_GET['paginate'])){
            $pagination=$_GET['paginate'];
        }
        $q = ModelHair::select('*');
        if(!empty($request->search)){
            $q->where('title', 'like', "%$request->search%");  
        }
        $d['model_hair']=$q->paginate($pagination)->withQueryString();
        return view('admin.model-hair.index',$d); 
    }
    public function create(){

        $d['title'] = "Add Model Hair";
        $d['model_hair'] = ModelHair::all();
        return view('admin.model-hair.create', $d);
    }
     public function store(Request $request)
    {
        // dd($request);
        $model_hair = ModelHair::updateOrCreate(['id'=>$request->id],[

            'title'     => $request->title,
            'status'    => $request->status,
        ]);
        $model_hair->update();

        return redirect()->route('dashboard.model-hair.index');
    }
    public function edit($id)
    {
        $d['title'] = "Model Hair";
        $d['buton_name'] = "Edit";
        $d['model_hair']=ModelHair::findorfail($id);
        return view('admin.model-hair.create', $d);
    }
    public function update(Request $request, $id)
    {

        //

    }
     public function destroy($id)
    {
        $model_hair = ModelHair::findOrFail($id);

        $model_hair->delete();

        return back();
    }
}
