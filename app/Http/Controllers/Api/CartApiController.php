<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Product;
use App\Models\Coupon;
use App\Models\Category;
use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\ProductAttribute;
use App\Models\ProductVariants;
use App\Models\GuestUserData;
use App\Models\User;
use App\Models\Wishlist;
use App\Http\Traits\CurrencyTrait;
use App\Models\CustomAttributes;
use App\Models\CouponUser;
use Validator;
use Auth;
use DB;
use Carbon;



class CartApiController extends Controller
{
    use CurrencyTrait;
    public function index(Request $request)
    {
        $user = auth()->guard('api')->user();
        if(!empty($user)){
             $userid = $user->id;
             $cart=Cart::select('id','user_id','product_id','quantity')->where('user_id','=',$userid)->get();
             $sum=Cart::where('user_id','=',$userid)->sum('price');
        }
        elseif(!empty($request->device_id)){
            $cart = GuestUserData::select('device_id','product_id','quantity')->where([['device_id',$request->device_id],['type','cart']])->get();
            $sum=GuestUserData::where('device_id','=',$request->device_id)->sum('price');
        }
        else{
            return response()->json(['status' => false, 'message' => "data not found",'subtotal'=>0, 'total'=>0, 'discount' => 0,'cart'=>[]], 200);
         }
         if(count($cart) > 0 ){
                $productids = [];
                $productdata = [];
                foreach($cart as $c_key => $c_value){
                    $productids[] = $c_value->product_id;
                    $product = Product::where('id',$c_value->product_id)->first();
                    if(!empty($product)){
                        $data = [];
                        $gallery = json_decode($product->gallery_image);
                        if(!empty($gallery)){
                            foreach ($gallery as $key1 => $value) {
                                $value1 = url('products/gallery/' . $value);
                                $data[] = $value1;
                            }
                        $product['gallery_image'] = $data;
                        }
                        if(!empty($product->featured_image)){
                        $product['featured_image'] = url('products/feature/'. $product->featured_image);
                        }
                        //quantity
                        $product['qty'] = $c_value->quantity;
                         //cart & wishlist
                        if(isset($userid)){

                                $Cart =    Cart::where('user_id',$userid)->where('product_id',$product->id)->first();
                                if(!empty($Cart)){
                                    $product['in_cart'] = true;

                                }
                                else{
                                    $product['in_cart'] = false;
                                }
                                $wishlistcheck = Wishlist::where('user_id',$userid)->where('product_id',$product->id)->first();
                                if(!empty($wishlistcheck)){
                                    $product['in_wishlist'] = true;
                                }
                                else{
                                    $product['in_wishlist'] = false; 

                                }
                        }
                        else{
                                $product['in_cart'] = false;
                                $product['in_wishlist'] = false;
                        }

                        // currency 
                        if(!empty($request->currency_code)){
                            $currency = $this->currencyFetch($request->currency_code);
                            $product['currency_sign'] = $currency['sign'];
                            $product['currency_code'] = $currency['code'];
                        }
                        // currency conversion
                        $p_price = $this->currencyConvert($request->currency_code,$product->p_price);
                        $s_price = $this->currencyConvert($request->currency_code,$product->s_price);
                        $product->p_price = round($p_price);
                        $product->s_price = round($s_price);

                        if($product->product_type == "card"){
                          $product['s_price'] = $c_value->card_amount;
                        }
                          $product['attributes'] = [];
                          $product['variants'] = [];
                    }

                    $cart[$c_key]['product'] = !empty($product) ? $product : '' ;

                    unset($c_value->user_id);
                    unset($c_value->product_id);
                    unset($c_value->id);
                    unset($c_value->quantity);
                    unset($c_value->device_id);
                }
                //appaly coupon
                if($request->coupon_code){
                    $currentDate =  Carbon\Carbon::now()->toDateString();
                    $coupoon = Coupon::where('code',$request->coupon_code)->first();
                    if(empty($coupoon)){
                        return response()->json(['status' => false, 'message' => "invalid coupon code",'subtotal'=>$sum, 'total'=>$sum, 'discount' => 0,'cart' => $cart], 200);
                    }

                    $couponproduct = DB::table('coupon_product')->where('coupon_id',$coupoon->id)->whereIn('product_id',$productids)->first();
                    if(!empty($coupoon)){
                        if(!empty($coupoon->minimum_spend) && $coupoon->minimum_spend >=$sum ){
                            return response()->json(['status' => false, 'message' => "Coupon is not applicable", 'subtotal'=>$sum, 'total'=>$sum, 'discount' => 0,'cart' => $cart], 200);
                        }

                        if(!empty($coupoon->maximum_spend) && $coupoon->maximum_spend <=$sum){
                            return response()->json(['status' => false, 'message' => "Coupon is not applicable", 'subtotal'=>$sum, 'total'=>$sum, 'discount' => 0,'cart' => $cart],  200);
                        }

                        //User coupon limit
                        $CouponUser = CouponUser::where('user_id',$userid)->first();
                        if(!empty($coupoon->limit_per_user)){
                            if(isset($CouponUser->total_use_time) && ($coupoon->limit_per_user ==$CouponUser->total_use_time)){
                                return response()->json(['status' => false, 'message' => "Coupon is not applicable", 'subtotal'=>$sum, 'total'=>$sum, 'discount' => 0,'cart' => $cart],  200);
                            }

                        }

                        //coupon expiry
                        $coupoonexpir = Coupon::where('code',$request->coupon_code)->whereDate('expiry_date','<=',$currentDate)->first();
                        if(!empty($coupoonexpir)){
                            return response()->json(['status' => false, 'message' => "Coupon expired"],  200);
                        }

                        //apply coupon
                        if(!empty($CouponUser)){
                            $userlimit = $CouponUser->total_use_time;
                            $updateLimit = (int)$CouponUser->total_use_time + 1;
                            $CouponUser = CouponUser::where('id',$CouponUser->id)->update([
                                'coupon_id' => $coupoon->id,
                                'user_id' => $userid,
                                'total_use_time' => $updateLimit,
                            ]);
                        } 

                        else{
                            $CouponUser = CouponUser::create([
                                'coupon_id' => $coupoon->id,
                                'user_id' => $userid,
                                'total_use_time' => 1,
                            ]);
                        }

                       

                        if($coupoon->discount_type == "flat_rate"){
                            $couponAmount = $coupoon->coupon_amount;
                        }

                        else{
                            $couponAmount = ($coupoon->coupon_amount * $sum) / 100;
                        }
                        $totalAmount = $sum - $couponAmount;
                        return response()->json(['status' => true, 'message' => "Success", 'subtotal'=>$sum, 'total'=>$totalAmount, 'discount' => $couponAmount,'cart' => $cart], 200);
                    }
                }
                return response()->json(['status' => true, 'message' => "Success", 'subtotal'=>$sum, 'total'=>$sum, 'discount' => 0,'cart' => $cart], 200);

         }
         else{
            return response()->json(['status' => false, 'message' => "data not found",'subtotal'=>0,  'cart' => []], 200);
         }

       
    }



    public function dabitaddtocart(Request $request){



        if (Auth::guard('api')->check()) {

            $user = Auth::guard('api')->user();

        } 

        $user_id = $user->id;



        $validator = Validator::make($request->all(), [

            'product_id' => 'required',

            'card_amount' => 'required',

        ]);



        if ($validator->fails()) {

            return response()->json(['status' => false, 'message' => implode("", $validator->errors()->all())], 200);

        }

        $already = Cart::where('user_id',$user_id)->where('product_id',404)->first();

        if(!empty($already)){

            return response()->json(['status' => false, 'message' => 'already exist in cart'], 200);   

        }

        $cart = Cart::create([



            "user_id" => $user_id,

            "card_amount" => $request->card_amount,

            "product_id" => $request->product_id,

            "quantity" => 1,

            "price" =>  $request->card_amount



        ]);





        return response()->json(['status' => true, 'message' => "Success",], 200);



    }







    /**



     * Show the form for creating a new resource.



     *



     * @return \Illuminate\Http\Response



     */



    public function create()



    {



        //



    }







    /**



     * Store a newly created resource in storage.



     *



     * @param  \Illuminate\Http\Request  $request



     * @return \Illuminate\Http\Response



     */



    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'product_id' => 'required',
            'quantity' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false,'message' => implode("", $validator->errors()->all()),'subtotal'=>0,  'cart' => []], 200);
        }
        if($request->quantity <= 0){
         return response()->json(['status' => false, 'message' =>'please select quantity','subtotal'=>$sum,'cart'=>$userCart]); 
        }
       // $userid = Auth::user()->token()->user_id;
        $user = auth()->guard('api')->user();

        if(!empty($user)){
            $userid = $user->id;
                $cart = Cart::where('product_id', $request->product_id)->where('user_id', $userid)->first();
                $product = Product::where('id',$request->product_id)->first();
                if(empty($product)){
                    return response()->json(['status' => false, 'message' => "product not found",'subtotal'=>0,  'cart' => []], 200);
                }
                if($product->in_stock <=0){
                    return response()->json(['status' => false, 'message' => "product is out of stock",'subtotal'=>0,  'cart' => []], 200);
                }

                $variations = $request->variation;
                $id = $request->product_id;

                if(!empty($cart)) {
                     //if cart not empty then check if this product exist then increment quantity
                    $q=(int)$cart->quantity;
                    $quant = $q + $request->quantity;
                    $price = $quant * $product->s_price;
                    $cart_added = Cart::where('id',$cart->id)->update([
                        "quantity" => $quant,
                        "price" => $price
                    ]);

                }
                else{

                     // if cart is empty then this is the first product 
                     $quantity = $request->quantity;
                     $price = $quantity * $product->s_price;
                    $cart_added = Cart::create([
                        'user_id'             => $userid,
                        'product_id'          => $request->product_id,
                        'quantity'            => $request->quantity,
                        'variation'           => json_encode($variations), 
                        "price"                => $price,
                        "vendor_id"                => $product->vendor_id
                    ]);

                }

                $userCart = Cart::where('user_id', $userid)->get();
                $sum = Cart::where('user_id', $userid)->sum('price');

                foreach($userCart as $key => $value){

                    $userCart[$key]['variation'] =  json_decode($value->variation);

                }

                return response()->json(['status' => true, 'message' =>'success','subtotal'=>$sum,'cart'=>$userCart]); 


        }
        elseif(!empty($request->device_id)){

            $exist = GuestUserData::where([['device_id',$request->device_id],['product_id',$request->product_id]])->first();
            $product = Product::where('id',$request->product_id)->first();
            if(!empty($exist)){
              $prevqty = $exist->quantity;
              $newqty = $prevqty + $request->quantity;
               $sum = $newqty * $product->s_price;
                  if($product->in_stock >= $newqty){
                     $exist->update([
                    'quantity'      => $newqty,
                    'price'         => $sum,
                    ]);
                    $exist->save(); 
                   
                 }
                 else{
                     return response()->json(['status' => false, 'message' =>'product out of stock','subtotal'=>0,'cart'=>[]]);   
                 }
             
            }
            else{
                $sum = $request->quantity * $product->s_price;
                $exist = GuestUserData::create([

                    'device_id' => $request->device_id,
                    'type'      => 'cart',
                    'quantity'      =>  $request->quantity,
                    'product_id'      =>  $request->product_id,
                     'price'         => $sum

                ]);
               

            }
            
            return response()->json(['status' => true, 'message' =>'success','subtotal'=>$sum,'cart'=>$exist]); 

        }
        else{
              return response()->json(['status' => false, 'message' => 'user not found','subtotal'=>0,'cart'=>[]], 200); 
        }

    }







    public function qtyupdate(Request $request){

        //$userid = Auth::user()->token()->user_id;
        $user = auth()->guard('api')->user();
        if(!empty($user)){
             $userid = $user->id;
            $cart = Cart::where([['user_id',$userid],['product_id',$request->product_id]])->first();
            if(empty($cart)){
               return response()->json(['status' => false, 'message' =>'cart data not found']); 
             }
             $product = Product::where('id',$cart->product_id)->first();
             if(empty($product)){
               return response()->json(['status' => false, 'message' =>'product not found']); 
             }
            $price = $request->qty * $product->s_price;
                $cartupdate = Cart::where('id',$cart->id)->update([
                    'quantity' =>  $request->qty,
                    'price' => $price,

                ]);
          return response()->json(['status' => true, 'message' =>'success']);
        }
        else{
            $cart = GuestUserData::where([['device_id',$request->device_id],['product_id',$request->product_id]])->first();
             $product = Product::where('id',$cart->product_id)->first();
              if(empty($product)){
               return response()->json(['status' => false, 'message' =>'product not found']); 
             }
            $price = $request->qty * $product->s_price;
                $cartupdate = GuestUserData::where('id',$cart->id)->update([
                    'quantity' =>  $request->qty,
                     'price' => $price,

                ]);

                return response()->json(['status' => true, 'message' =>'success']);
        }
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



        //



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



    public function destroy(Request $request)

    {
        $user = auth()->guard('api')->user();
            $validator = Validator::make($request->all(), [
            'product_id' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json(['status' => false, 'message' => implode("", $validator->errors()->all())], 200);
            }

         if(!empty($user)){
            $userid = $user->id;
            $cart = Cart::where([['user_id',$userid],['product_id',$request->product_id]])->first();
            if(!empty($cart)){
                $cart->delete();
              return response()->json(['status' => true,'message' => "success"], 200);    

            }
            else{
                return response()->json(['status' => false,'message' => "cart not found"], 200);  
            }
         }
         elseif($request->device_id){
            GuestUserData::where([['device_id',$request->device_id],['product_id',$request->product_id]])->delete();
             return response()->json(['status' => true,'message' => "success"], 200);
         }
         else{
             return response()->json(['status' => false,'message' => "please enter device id or auth token"], 200);
         }

    }



}



