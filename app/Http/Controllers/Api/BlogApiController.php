<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Blogs;
use App\Models\BlogComments;
use App\Models\User;
use App\Models\BlogsTags;
use Validator;
use Auth;
use DB;
use Carbon;



class BlogApiController extends Controller
{
  
    public function index(Request $request)
    {

        $newData = [];
        $Blogs = Blogs::select('blogs.*','blogs_category.title as cat_title','users.name as author_name','users.first_name as author_first_name')->leftJoin('blogs_category','blogs.cat_slug','=', 'blogs_category.slug')->leftJoin('users','blogs.author_id','=', 'users.id')->get();
        $recent = Blogs::select('slug','title','image')->orderBy('created_at','desc')->limit('5')->get();
        foreach($recent as $rec => $value){
            $value->image = url('blog-image/'.$value->image);
        }
        $tag = BlogsTags::pluck('title');
        foreach($Blogs as $k => $v){
             $v->image = url('blog-image/'.$v->image);
            $blogComment = BlogComments::where([['blog_slug',$v->slug],['parent_id',0]])->get();
            foreach($blogComment as $key => $val){
                $user = User::where('id',$val->user_id)->first();
                $blogsubComment = BlogComments::where('parent_id',$val->id)->get();
                if(!empty($user)){
                $blogComment[$key]['user_name'] = (!empty($user->name)) ? $user->name : $user->first_name;
                }
                $blogComment[$key]['child_comment'] = $blogsubComment;
            }
            $gallery = json_decode($v->blog_images);
                if(!empty($gallery)){
                    foreach ($gallery as $gall => $gallimg) {
                        $value1 = url('blog-image/gallery/' . $gallimg);
                        $data[] = $value1;
                    }
                    $Blogs[$k]['blog_images'] = $data;
                }
            $Blogs[$k]['blog_comment'] = $blogComment;
        }
        $newData['blogs'] = $Blogs;
        $newData['recent_blog'] = $recent;
        $newData['all_tags'] = $tag;
        if(count($Blogs) > 0){
              return response()->json(['status' => true, 'message' => "success", 'data' => $newData], 200);
        }
        else{
              return response()->json(['status' => false, 'message' => "unsuccess"], 200);
        }

      
    }

    public function storecomment(Request $request){
        $validator = Validator::make($request->all(), [
            'slug' => 'required',
            'comment' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => implode("", $validator->errors()->all())], 200);
        }
        if (Auth::guard('api')->check()) {
            $user = Auth::guard('api')->user();
        } 
        $user_id = $user->id;
        $BlogComments = BlogComments::create([
            'user_id' => $user_id,
            'blog_slug' => $request->slug,
            'comment' => $request->comment,
            'parent_id' => !empty($request->parent_id)?$request->parent_id:0,
        ]);

        return response()->json(['status' => true, 'message' => "Success"], 200);

    }


}



