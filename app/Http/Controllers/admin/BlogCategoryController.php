<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BlogsCategory;

class BlogCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // mail
        $d['title'] = "Blog Category";
        $d['buton_name'] = "ADD NEW";
        $pagination=10;
        if(isset($_GET['paginate'])){
            $pagination=$_GET['paginate'];
        }
         $q=BlogsCategory::select('*')->orderBy('id','DESC');
            if($request->search){
                $q->where('title', 'like', "%$request->search%");
            }
        $d['cat']=$q->paginate($pagination)->withQueryString();

        return view('admin/blog-cat/index',$d);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $d['title'] = "Blog Category";
        return view('admin/blog-cat/add',$d);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $BlogsCategory = BlogsCategory::updateOrCreate(
            [
                'id' => $request->id
            ],
            [
            // 'user_id'   => Auth::user()->id,
            'title'     => $request->input('title'),
            'arab_title'     => $request->input('arab_title'),

        ]);


   $BlogsCategory->update();
    return redirect('/dashboard/blog-category')->with('status', 'your data is updated');

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

        $d['title'] = "Blog Category";
        $d['blogcat']=BlogsCategory::findorfail($id);
        return view('admin/blog-cat/add',$d);
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
        $tax = BlogsCategory::findOrFail($id);
        $tax->delete();
        return back();
    }
}
