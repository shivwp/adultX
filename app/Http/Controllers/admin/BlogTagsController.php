<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BlogsTags;

class BlogTagsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
       
        
        $d['title'] = "Blog Tags";
        $d['buton_name'] = "ADD NEW";
        $pagination=10;
        if(isset($_GET['paginate'])){
            $pagination=$_GET['paginate'];
        }
         $q=BlogsTags::select('*')->orderBy('id','DESC');
            if($request->search){
                $q->where('title', 'like', "%$request->search%");  
            }
        $d['cat']=$q->paginate($pagination)->withQueryString();
        
        return view('admin/blog-tags/index',$d);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $d['title'] = "Blog Tags";
        return view('admin/blog-tags/add',$d);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $BlogsTags = BlogsTags::updateOrCreate(
            [
                'id' => $request->id
            ],
            [
            // 'user_id'   => Auth::user()->id,
            'title'     => $request->input('title'),
            'arab_title'     => $request->input('arab_title'),
            
        ]);
       
        
   $BlogsTags->update();
    return redirect('/dashboard/blog-tags')->with('status', 'your data is updated');
    
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
        
        $d['title'] = "Blog Tags";
        $d['blogcat']=BlogsTags::findorfail($id);
        return view('admin/blog-tags/add',$d);
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
        $tax = BlogsTags::findOrFail($id);
        $tax->delete();
        return back();
    }
}
