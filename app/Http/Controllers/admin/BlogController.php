<?php
namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Blogs;
use App\Models\BlogsCategory;
use App\Models\BlogsTags;
use App\Models\BlogComments;
use Auth;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $d['title'] = "Blogs";
        $d['buton_name'] = "ADD NEW";
        $pagination=10;
        if(isset($_GET['paginate'])){
            $pagination=$_GET['paginate'];
        }
        $q=Blogs::select('blogs.*','blogs_category.title as cat_title','users.first_name','users.first_name')->leftJoin('blogs_category','blogs.cat_slug','=', 'blogs_category.slug')->leftJoin('users','blogs.author_id','=', 'users.id');
        if($request->search){
            $q->where('blogs.title', 'like', "%$request->search%");  
        }
        $d['blogs']=$q->paginate($pagination)->withQueryString();
        return view('admin/blog/index',$d);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
    */
    public function create()
    {
        $d['title'] = "Blog Create";
        $d['blogcat'] = BlogsCategory::all();
        $d['blogtag'] = BlogsTags::all();
        return view('admin/blog/add',$d);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
    */
    public function store(Request $request)
    {
        $Blogs = Blogs::updateOrCreate(
        [
            'id' => $request->id
        ],
        [
            'title'                     => $request->input('title'),
            'author_id'                 => Auth::user()->id,
            'cat_slug'                  => $request->input('blog_cat'),
            'short_description'         => $request->input('short_description'),
            'long_description'          => $request->input('content'),
            'meta_title'                => $request->input('meta_title'),
            'meta_keyword'              => $request->input('meta_keyword'),
            'meta_description'          => $request->input('meta_description'),
            'arab_title'                => $request->input('arab_title'),
            'arab_short_description'    => $request->input('arab_short_description'),
            'arab_long_description'     => $request->input('arab_long_description'),
            'tag_slug'                  => $request->input('tag'),
        ]);
        if($request->hasfile('image'))
        {
            $file = $request->file('image');
            $extention = $file->getClientOriginalExtension();
            $filename = time().'.'.$extention;
            $file->move('blog-image/', $filename);
            Blogs::where('id',$Blogs->id)->update([
                'image' => $filename
            ]);
        }
        if($request->hasfile('gallery')){
            $multiimage = [];
            foreach($request->file('gallery') as $key => $val){
                $imageName = uniqid().'.'.$val->getClientOriginalExtension();  
                $val->move("blog-image/gallery/", $imageName);
                $multiimage[] = $imageName;
            }
            Blogs::where('id',$Blogs->id)->update([
                'blog_images' => json_encode($multiimage)
            ]);
        }
    $Blogs->update();
    return redirect('/dashboard/blogs')->with('status', 'your data is updated');
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
        $d['title'] = "Blog Create";
        $d['blog']=Blogs::findorfail($id);
        $d['blogtag'] = BlogsTags::all();
        $d['blogcat'] = BlogsCategory::all();
        return view('admin/blog/add',$d);
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
        $Blog = Blogs::findOrFail($id);
        $Blog->delete();
        return back();
    }
}

