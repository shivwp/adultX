<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Wishlist;
use App\Models\Product;
use App\Models\GuestUserData;
use App\Models\Category;
use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\ProductAttribute;
use App\Http\Traits\CurrencyTrait;
use App\Models\ProductVariants;
use App\Models\Cart;
use Validator;
use Auth;
class WishlistApiController extends Controller
{

    use CurrencyTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {
       $user = auth()->guard('api')->user();
       if(!empty($user)){
            $userid = $user->id;
            $wishlist = Wishlist::select('product_id','user_id')->where('user_id',$userid)->get();
        }
        elseif(!empty($request->device_id)){
            $wishlist = GuestUserData::select('device_id','product_id')->where([['device_id',$request->device_id],['type','wishlist']])->get();
        }
        $products = [];
        if(!empty($wishlist) && count($wishlist) > 0){
             foreach($wishlist as $key => $val){
                 $product = Product::where('id',$val->product_id)->first();
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
                    //Product Attributes
                    $productAttributes = ProductAttribute::where('product_id',$product->id)->groupBy('attr_id')->get();
                    if(count($productAttributes)>0){
                        foreach($productAttributes as $attr_key => $attr_val){
                            $attr = Attribute::select('id','slug')->where('id',$attr_val->attr_id)->first();
                            $attrdata[] = $attr;
                        }
                        if(!empty($attrdata)){ 
                            foreach($attrdata as $d => $dv){
                                $proattragain = ProductAttribute::where('attr_id',$dv->id)->get();

                                $attrval = [];
                                foreach($proattragain as $ag => $proagain){
                                $attrval[] = $proagain->attr_value_id;

                                }

                                $attr_value = AttributeValue::select('id','attr_id','slug')->whereIn('id',$attrval)->get();

                            $attrdata[$d]['attribute_value'] = $attr_value;
                            }
                        }
                        $product['attributes'] = $attrdata;
                    }
                    else{
                        $product['attributes'] = [];  
                    }
                    //Product Attributes
                    $productVariants = ProductVariants::select('parent_id','p_id','variant_value','variant_sku','variant_price','variant_stock','variant_images')->where('parent_id',$product->id)->get();

                    if(count($productVariants) > 0){
                        $arr_attr = [];
                        $variants_img = [];
                        foreach($productVariants as $v_k => $v_val){
                            foreach(json_decode($v_val->variant_value) as $key_var =>   $val_var) {
                                $attrval = AttributeValue::where('id', $val_var)->first();
                                $arr_attr[$key_var]['key'] = $attrval->attr_value_name;
                                $arr_attr[$key_var]['value'] = $val_var; 
                                $productVariants[$v_k]['variant_value'] = $arr_attr;
                            }
                            if(!empty($v_val->variant_images)){

                                foreach(json_decode($v_val->variant_images) as $key_var_img =>   $val_var_img) {
                                    $variants_img[] = url('products/gallery/' . $val_var_img);

                                    $productVariants[$v_k]['variant_images'] = $variants_img;

                                }
                            }


                        }

                        $product['variants'] = $productVariants;

                    }
                    else{
                          $product['variants'] = []; 
                    }
                 }
                 $products[] = $product;

             }
              
              return response()->json(['status' => true, 'message' => "Success",  'wishlist' => $products], 200);
        }
        else{
            return response()->json(['status' => false, 'message' => "data not found",  'wishlist' => []], 200); 
        }
    }

    public function create()
    {
    }



    public function store(Request $request)
    {

        $user = auth()->guard('api')->user();
        $validator = Validator::make($request->all(), [
            'product_id' => 'required',
        ]);

        if ($validator->fails()) {
            $er = [];
            $i = 0;
            foreach ($validator->errors() as $err) {
                $er[$i++] = $err[0];
                return $err;
            }
            return response()->json(['status' => false, 'message' => implode("", $validator->errors()->all()), 'user' => Null], 200);
        }

        if(!empty($user)){
            $userid = $user->id;
            $productExist = Product::where('id',$request->product_id)->first();
            if(empty($productExist)){
                return response()->json(['status' => false, 'message' => "product not found"], 200);
            }
            $productInWishlist = Wishlist::where('product_id',$request->product_id)->where('user_id',$userid)->first();

            if(!empty($productInWishlist)){

                return response()->json(['status' => false, 'message' => "product already in wishlist"], 200);
            }
             $wishlist = Wishlist::updateOrCreate(['id' => $request->id],[
                                'user_id' => $userid,
                                'product_id' => $request->product_id
                        ]);
        }
        else{
            $productExist = Product::where('id',$request->product_id)->first();
            if(empty($productExist)){
                return response()->json(['status' => false, 'message' => "product not found"], 200);
            }
            $productInWishlist = GuestUserData::where('product_id',$request->product_id)->where('device_id',$request->device_id)->first();

            if(!empty($productInWishlist)){

                return response()->json(['status' => false, 'message' => "product already in wishlist"], 200);
            }

            $wishlist = GuestUserData::create([
                'device_id' => $request->device_id,
                'product_id' => $request->product_id,
            ]);
           
        }

       

        return response()->json(['status' => true, 'message' => "success"], 200);

    }









    public function show($id)
    {
    }





    public function edit($id)
    {
    }





    public function update(Request $request, $id)
    {
    }






    public function removeWishlistdata(Request $request)
    {

        $user = auth()->guard('api')->user();

        $validator = Validator::make($request->all(), [
            'product_id' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => implode("", $validator->errors()->all())], 200);
        }
       
        if(!empty($user)){
            $userid = $user->id;
              $Wishlist = Wishlist::where([['user_id',$userid],['product_id',$request->product_id]])->first();
            if(empty($Wishlist)){
                return response()->json(['status' => false, 'message' => "data not found"], 200);
            }
            $Wishlist->delete();

            return response()->json(['status' => true, 'message' => "success"], 200);
            
        }
        elseif(!empty($request->device_id)){

             $Wishlist = GuestUserData::where([['device_id',$request->device_id],['product_id',$request->product_id]])->first();
              if(empty($Wishlist)){
                return response()->json(['status' => false, 'message' => "data not found"], 200);
            }
            $Wishlist->delete();

            return response()->json(['status' => true, 'message' => "success"], 200);

        }

    }


   

}



