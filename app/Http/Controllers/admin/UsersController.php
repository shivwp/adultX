<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyUserRequest;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\Role;
use App\Models\UserBids;
use App\Models\UserWalletTransection;
use App\Models\Category;
use App\Models\Cart;
use App\Models\OrderMeta;
use App\Models\User;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderedProducts;
use App\Models\Transaction;
use Hash;
use Gate;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ImportCustomer;
use App\Exports\ExportUser;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use DB;

class UsersController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('user_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $title = "Users";
        $buton_name = "Add New";
        $users = User::all();
        $pagination=10;
        if(isset($_GET['paginate'])){
            $pagination=$_GET['paginate'];
        }
         $q=User::select('*')->orderBy('id','DESC');
            if($request->search){
                $q->where('first_name', 'like', "%$request->search%");
            }
            $users=$q->paginate($pagination)->withQueryString();
            return view('admin.users.index', compact('users','title','buton_name'));
    }
    public function index2()
    {
        abort_if(Gate::denies('vuser_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $title = "Users";
        $buton_name = "Add New"; 
        $users = User::join('address', 'address.user_id', '=', 'users.id')->where('address.is_default', '=', '1')->get();
        $pagination=10;
        if(isset($_GET['paginate'])){
            $pagination=$_GET['paginate'];
        }
        $users =User::paginate($pagination)->withQueryString();
        return view('admin.users.index2', compact('users','title','buton_name'));
    }
    public function create()
    {
        abort_if(Gate::denies('user_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

         $title = "User Add";
         $roles = Role::all()->pluck('title', 'id');
         return view('admin.users.create', compact('roles','title'));
    }
    public function store(Request $request)
    {
        $password = Hash::make($request->password);

        $user = User::updateOrCreate(['id'=>$request->id],[

            'first_name'    => $request->first_name,
            'last_name'     => $request->last_name,
            'email'         => $request->email,
            'password'      => $password,
            'dob'           => $request->dob,
            'gender'        => $request->gender,
            'phone'         => $request->phone,
            'user_status'   => $request->user_status,

        ]);
        $user->roles()->sync($request->input('role'));
        return redirect()->route('dashboard.users.index');
    }

    public function storeTransection(Request $request){

        $wallet_transection = Transaction::create(
        [
            'user_id'       => $request->user_id,
            'add_money'     => $request->money,
            'reason'        => $request->reason,
            'discription'   => $request->discription,
            'type'          => $request->type,
        ]);

        $user_wallet = user::where('id',$request->user_id)->first();
        // dd($transec_amount);
        if(!empty($user_wallet->wallet) && isset($request->money)){
            $old_amount = $user_wallet->wallet;
            $add_amount = $request->money;
            $new_amount = $old_amount + $add_amount;

            User::where('id', $request->user_id)->update(["wallet" => $new_amount]);
        }
        return response()->json(['status'=>true, 'amount' => $new_amount]);
    }
    public function blockUser($id){

       User::where('id',$id)->update([
            'block' => '1'
       ]);

       return back();
    }


    public function edit($id)
    {
        abort_if(Gate::denies('user_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
         $title = "User Edit";

        $roles = Role::all()->pluck('title', 'id');

         $user = User::findOrFail($id);
        $user->load('roles');
        return view('admin.users.create', compact('roles', 'user','title'));
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $user->update($request->all());
        $user->roles()->sync($request->input('roles', []));

    }

    public function show($id)
    {

        $d['title']         = "User";

        $user = User::where('id',$id)->first();

        $userOrder = Order::where('user_id',$id)->where('parent_id',0)->get();

        foreach($userOrder as $key => $val){

         $orderMeta = OrderMeta::where('order_id',$val->id)->pluck('meta_value','meta_key');

         $OrderedProducts = OrderedProducts::where('order_id',$val->id)->get();

         $userOrder[$key]['order_meta'] = $orderMeta;

         $userOrder[$key]['order_product'] = $OrderedProducts;

        }

        $topsellingproduct = Order::where('user_id',$id)

        ->join('ordered_products', 'ordered_products.order_id', '=', 'orders.id')

        ->join('products', 'products.id', '=', 'ordered_products.product_id')

        ->selectRaw('products.*, SUM(ordered_products.quantity) AS quantity_sold')

        ->groupBy(['products.id']) // should group by primary key

        ->orderByDesc('quantity_sold')

        ->take(5) // 20 best-selling products

        ->get();

        $topsellingcategory = Order::where('user_id',$id)

        ->join('ordered_products', 'ordered_products.order_id', '=', 'orders.id')

        ->join('categories', 'categories.id', '=', 'ordered_products.category')

        ->selectRaw('categories.*')

        ->groupBy(['categories.id']) 

        ->take(5) // 20 best-selling products

        ->get();
        $usercart = Cart::where('user_id',$id)->get();

        foreach($usercart as $cart_key => $cart_val){

            $pro = Product::where('id',$cart_val->product_id)->first();

            $usercart[$cart_key]['pro_name'] = $pro->pname;
        }

        $d['user']         = $user;

        $d['userOrder']         = $userOrder;

        $d['topsellingproduct']         = $topsellingproduct;

        $d['topsellingcategory']         = $topsellingcategory;

        $d['usercart']         = $usercart;

        $usersbid = UserBids::where('user_id',$id)->get();

        foreach($usersbid as $bid_key => $bid_val){

            $user = User::where('id',$bid_val->user_id)->first();
            $product = Product::where('id',$bid_val->product_id)->first();
            $usersbid[$bid_key]['user'] = !empty($user->name) ? $user->name : '';
            $usersbid[$bid_key]['product'] = !empty($product->pname) ? $product->pname : '';

        }


        $d['usersbid']         = $usersbid;

        return view('admin.users.show',$d);
    }
    public function destroy($id)
    {
        abort_if(Gate::denies('user_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $user = User::findOrFail($id);

        $user_role = DB::table('role_user')->where('user_id',$id)->delete();
        $user->delete();

        return back();
    }
    public function massDestroy(MassDestroyUserRequest $request)
    {

        User::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);


    }
    public function storeCradit(Request $request){
        $d['title'] = "Users";

        $pagination=10;  

        if(isset($_GET['paginate'])){

            $pagination=$_GET['paginate'];

        }

        $d['users']=User::query()

        ->whereHas('roles', function($query){ 

        $query->where('title','=', 'User');

        })->where('user_wallet','!=',0)->paginate($pagination)->withQueryString();
        return view('admin.users.store-cradit',$d);

    }
    public function customer(Request $request){
        $d['title'] = "Customers";

         $pagination=10;  

        if(isset($_GET['paginate'])){

            $pagination=$_GET['paginate'];

        }

        $setting = Role::where('title', 'User')->first()->users();
        if($request->search){

            $setting->where('first_name', 'like', "%$request->search%"); 

        }
        $d['setting']=$setting->paginate($pagination)->withQueryString();
        return view('admin.customer',$d);
    }
    public function importView(Request $request){

        return redirect('/dashboard/product');

    }

    public function importCustomer(Request $request){

        $fileName = time().'_'.request()->importfile->getClientOriginalName();

          Excel::import(new ImportCustomer, $request->file('importfile')->storeAs('product-csv', $fileName));

        return redirect()->back();

    }

    public function clearCache(Request $request){

        return view('admin.clear-cache');

    }

    public function exportUsers(Request $request){
        return Excel::download(new ExportUser, 'users.xlsx');
    }

}



