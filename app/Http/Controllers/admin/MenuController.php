<?php



namespace App\Http\Controllers\admin;



use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Models\Menu;

use App\Models\Country;

use App\Models\State;

use App\Models\City;

use Auth;

use Session;



class MenuController extends Controller

{

    /**

     * Display a listing of the resource.

     *

     * @return \Illuminate\Http\Response

     */

    public function index(Request $request)

    {

        $d['title'] = "MENU";

        $d['menu']=Menu::all();

        $d['buton_name'] = "ADD NEW";

         $pagination=10;

        if(isset($_GET['paginate'])){

            $pagination=$_GET['paginate'];

        }

         $q=Menu::select('*');

            if($request->search){

                $q->where('title', 'like', "%$request->search%");  

            }

             $d['menu']=$q->paginate($pagination)->withQueryString();

        

        return view('admin.menu.index',$d);

    }



    /**

     * Show the form for creating a new resource.

     *

     * @return \Illuminate\Http\Response

     */

    public function create()

    {

        $d['title'] = "MENU";
        $d['parent'] = Menu::select('id','slug','title')->get();

        return view('admin/menu/add',$d);

    }



    /**

     * Store a newly created resource in storage.

     *

     * @param  \Illuminate\Http\Request  $request

     * @return \Illuminate\Http\Response

     */

    public function store(Request $request)

    {

        

         $menu = Menu::updateOrCreate(

            [

                'id' => $request->id

            ],

            [

            'arab_title'     => $request->input('arab_title'),

            'title'     => $request->input('title'),

            'position'     => $request->input('position'),

            'url'     => $request->input('url'),

            'parent'     => !empty($request->input('parent')) ? $request->input('parent') : 0,

            'icon'     => $request->input('icon'),

        ]);

         if($request->hasfile('menu_image'))

            {

                $file = $request->file('menu_image');

                $extention = $file->getClientOriginalExtension();

                $filename = time().'.'.$extention;

                $file->move('home-menu/', $filename);

                Menu::where('id',$menu->id)->update([



                    'image' => $filename

                ]);

            }

       

   $menu->update();

   if(Auth::user()->roles->first()->title == 'Vendor'){

    $type='menu';

   \Helper::addToLog('MENU create or update', $type);

   }

   return redirect('/dashboard/menus')->with('status', 'your data is updated');

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

        $d['title'] = "MENU";

        $d['menu']=Menu::findorfail($id);
         $d['parent'] = Menu::select('id','slug','title')->get();

        return view('admin/menu/add',$d);

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

        $pg=Menu::findOrFail($id);

         $pg->delete();

         $type='menu';

        \Helper::addToLog('MENU Delete', $type);

         return redirect('dashboard/menus');

    }



    public function cityadd(){

        //dd($request);



        $d['title'] = "Add New City";

        $d['countries'] = Country::get(["name", "id"]);



       return view('admin/city/add',$d);

    }



    public function citystore(Request $request){

        $City = City::where([['city_name',$request->city],['state_id',$request->state]])->first();

        if(!empty($City)){

            Session::flash('error', "City is already exist");

            return back();

        }

        else{

            City::create([

                "city_name" => $request->city,

                "state_id" => $request->state

            ]);

            Session::flash('success', "City added successfully");

              return back();

        }

    }



    public function fetchState(Request $request)

    {

        $data['states'] = State::where("country_id",$request->country_id)->get(["state_name", "state_id"]);

        return response()->json($data);

    }

    public function fetchCity(Request $request)

    {

        $data['cities'] = City::where("state_id",$request->state_id)->get(["city_name", "city_id"]);

        return response()->json($data);

    }

}

