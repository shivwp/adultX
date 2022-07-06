<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ModelCategory;

class ModelCategoryController extends Controller
{
    public function index()
    {
        $d['title'] = "Model Category";
        $d['buton_name'] = "ADD NEW";
        $pagination=10;
        if(isset($_GET['paginate'])){
            $pagination=$_GET['paginate'];
        }
        $q = ModelCategory::select('*');
        if(!empty($request->search)){
            $q->where('title', 'like', "%$request->search%");  
        }
        $d['model_cate']=$q->paginate($pagination)->withQueryString();
        return view('admin.model-category.index',$d); 
    }
    public function create(){

        $d['title'] = "Add Model Category";
        $d['model_cate'] = ModelCategory::all();
        return view('admin.model-category.create', $d);
    }
     public function store(Request $request)
    {
        // dd($request);
        $model_cate = ModelCategory::updateOrCreate(['id'=>$request->id],[

            'title'     => $request->title,
            'status'    => $request->status,
        ]);
        $model_cate->update();

        return redirect()->route('dashboard.model-category.index');
    }
    public function edit($id)
    {
        $d['title'] = "Model Category";
        $d['buton_name'] = "Edit";
        $d['model_cate']=ModelCategory::findorfail($id);
        return view('admin.model-category.create', $d);
    }
    public function update(Request $request, $id)
    {

        //

    }
     public function destroy($id)
    {
        $model_cate = ModelCategory::findOrFail($id);

        $model_cate->delete();

        return back();
    }
}
