<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ModelLanguage;

class ModelLanguageController extends Controller
{
    public function index()
    {
        $d['title'] = "Model Language";
        $d['buton_name'] = "ADD NEW";
        $pagination=10;
        if(isset($_GET['paginate'])){
            $pagination=$_GET['paginate'];
        }
        $q = ModelLanguage::select('*');
        if(!empty($request->search)){
            $q->where('title', 'like', "%$request->search%");  
        }
        $d['model_lang']=$q->paginate($pagination)->withQueryString();
        return view('admin.model-language.index',$d); 
    }
    public function create(){

        $d['title'] = "Add Model Language";
        $d['model_lang'] = ModelLanguage::all();
        return view('admin.model-language.create', $d);
    }
     public function store(Request $request)
    {
        // dd($request);
        $model_lang = ModelLanguage::updateOrCreate(['id'=>$request->id],[

            'title'     => $request->title,
            'status'    => $request->status,
        ]);
        $model_lang->update();

        return redirect()->route('dashboard.model-language.index');
    }
    public function edit($id)
    {
        $d['title'] = "Model Language";
        $d['buton_name'] = "Edit";
        $d['model_lang']=ModelLanguage::findorfail($id);
        return view('admin.model-language.create', $d);
    }
    public function update(Request $request, $id)
    {

        //

    }
     public function destroy($id)
    {
        $model_lang = ModelLanguage::findOrFail($id);

        $model_lang->delete();

        return back();
    }
}
