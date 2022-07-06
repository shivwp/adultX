<?php
namespace App\Http\Controllers\api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Menu;

class MenuController extends Controller
{
    public function index(Request $request)

    {

        $menu = Menu::orderBy('position','ASC')->where([['parent',0],['position','!=',null]])->get();

        if(count($menu) > 0){

                foreach($menu as $key => $val){
                    $top_sub_menu = Menu::where([['parent',$val->id],['is_popular',1]])->get();
                    $more_sub_menu = Menu::where([['parent',$val->id],['is_popular',0]])->get();
                     $menu[$key]['title'] = $val->arab_title;
                        if(count($top_sub_menu) > 0){
                            $menu[$key]['top_sub_menu'] = $top_sub_menu;
                        }
                        if(count($more_sub_menu) > 0){
                            $menu[$key]['more_sub_menu'] = $more_sub_menu;
                        }
                        if(!empty($val->image)){
                            $val->image = url('home-menu/'.$val->image);
                        }

                }

            return response()->json(['status' => true, 'message' => "gift cards", 'data' => $menu], 200);

        }

        else{


            return response()->json(['status' => false, 'message' => "gift cards not found", 'data' => []], 200);

        }

    }



    

}



