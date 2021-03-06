<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\AttributeValue;
use App\Models\Category;
use App\Models\Attribute;
use App\Models\ProductVariants;
use App\Models\ProductAttribute;
use App\Models\PageMeta;
use App\Models\Coupon;
use App\Models\Cart;
use App\Models\Setting;
use App\Models\Wishlist;
use App\Models\ProductBid;
use App\Models\VendorSetting;
use App\Models\Feedback;
use App\Models\Brand;
use App\Models\UserBids;
use App\Models\GuestUserData;
use App\Http\Traits\CurrencyTrait;
use Auth;
use Carbon;
use Validator;
use DB;

class ProductApiController extends Controller
{
   use CurrencyTrait;
    public function index(Request $request)
    {
        $currencycode = $request->currency_code;

        $prod=Product::orderBy('id', 'DESC')->where('product_type','!=','giftcard')->where('product_type','!=','card')->where('parent_id','=',0)->where('is_publish','=',1)->where('product_type','!=','booking');

        if(!empty($request->location)){
            // $prod->leftJoin('vendorsettings', 'vendorsettings.vendor_id', '=', 'products.vendor_id');
            // $prod->where('vendorsettings.value', '=', $request->location)->select('products.*','vendorsettings.name');
            $prod->leftJoin('city_user', 'city_user.user_id', '=', 'products.vendor_id')->where('city_user.city_id', $request->location);
        }

        if($request->category_id){
            $prod->where('products.cat_id',$request->category_id)->orWhere('products.cat_id_2', $request->category_id)->orWhere('products.cat_id_3', $request->category_id); 
        }

        if($request->sub_cat_id){
            $prod->where('products.cat_id_2',$request->sub_category_id); 
        }

        if($request->search){
            $prod->where('products.pname','LIKE','%'.$request->search.'%'); 
        }

        if($request->brand){
            $prod->where('products.brand_slug',$request->brand);
        }

        if($request->price_range){

            $exp = explode("-",$request->price_range); 
                $min_price = $exp[0];
                $max_price = $exp[1];
                $prod->whereBetween('products.s_price', [$min_price, $max_price]);
            }
        if($request->in_stock){
         if($request->in_stock == true){
            $prod->where('products.in_stock','>',0); 
         }

        }
        if(!empty($request->on_sale) && $request->on_sale == true){
            $prod->where('products.offer_discount','!=', null);
        }
        if($request->attr_value_id){

            $ProductAttribute = ProductAttribute::select('product_id')->whereIn('attr_value_id',$request->attr_value_id)->groupBy('product_id')->get();

            if(count($ProductAttribute)>0){
                foreach($ProductAttribute as $pro_attr => $pro_value){
                    $proidmatch[] = $pro_value->product_id;   
                }
            }
            if(!empty($proidmatch)){
                $prod->whereIn('products.id', $proidmatch); 

            }
        }
        if(!empty($request->page) && !empty($request->limit)){
            $page = $request->page;
            $limit = $request->limit;
            $product = $prod->limit($limit)->offset(($page - 1) * $limit)->get();

        }
        else{
        $product = $prod->get();
        }
        if(count($product) > 0){
            $this->productCommondata($product,$currencycode);
            return response()->json(['status' => true, 'message' => "All product list", 'product' => $product], 200);
        }
        else{
            return response()->json(['status' => false, 'message' => "Product Not Found", 'product' => []], 200);
        }

    }

    public function homeProducts(Request $request){
         $currencycode = $request->currency_code;
        $prod=Product::orderBy('id', 'DESC')->where([['product_type','!=','giftcard'],['product_type','!=','card'],['parent_id','=',0],['is_publish','=',1],['product_type','!=','booking']]);
         if(!empty($request->is_featured)){
            $prod->where('featured',1); 
         }
         if(!empty($request->is_bidding)){
            $prod->where('product_type','=','bid'); 
         }
         if(!empty($request->best_seller)){
            $prod->orderBy('id','DESC'); 
         }
         if(!empty($request->top_hundered)){
            $prod->where('top_hunderd',1); 
         }
         if(!empty($request->new)){
            $prod->orderBy('id','DESC'); 
         }

         $product = $prod->get();
        if(count($product) > 0){
            $this->productCommondata($product,$currencycode);
            return response()->json(['status' => true, 'message' => "All product list", 'product' => $product], 200);
        }
        else{
            return response()->json(['status' => false, 'message' => "Product Not Found", 'product' => []], 200);
        }
        

    }

    public function productCommondata($product,$currencycode,$device_id=null){
          foreach($product as $key => $val){
                $data = [];
                $gallery = json_decode($val->gallery_image);
                if(!empty($gallery)){
                    foreach ($gallery as $key1 => $value) {
                        $value1 = url('products/gallery/' . $value);
                        $data[] = $value1;
                    }
                $product[$key]['gallery_image'] = $data;
                }
                if(!empty($val->featured_image)){
                $product[$key]['featured_image'] = url('products/feature/'. $val->featured_image);

                }
                //cart & wishlist
                    if (Auth::guard('api')->check()) {
                        $user = Auth::guard('api')->user();
                        $user_id = $user->id;
                    } 
                    if(isset($user_id)){

                        $Cart =    Cart::where('user_id',$user_id)->where('product_id',$val->id)->first();
                        if(!empty($Cart)){
                            $product[$key]['in_cart'] = true;

                        }
                        else{
                            $product[$key]['in_cart'] = false;
                        }
                        $wishlist = Wishlist::where('user_id',$user_id)->where('product_id',$val->id)->first();
                        if(!empty($wishlist)){
                            $product[$key]['in_wishlist'] = true;
                        }
                        else{
                            $product[$key]['in_wishlist'] = false; 

                        }
                    }
                    elseif($device_id != null){
                        $Cart =    GuestUserData::where('device_id',$device_id)->where('product_id',$val->id)->first();
                        if(!empty($Cart)){
                            $product[$key]['in_cart'] = true;

                        }
                        else{
                            $product[$key]['in_cart'] = false;
                        }
                        $wishlist = GuestUserData::where('device_id',$device_id)->where('product_id',$val->id)->first();
                        if(!empty($wishlist)){
                            $product[$key]['in_wishlist'] = true;
                        }
                        else{
                            $product[$key]['in_wishlist'] = false; 

                        }
                            
                    }
                    else{
                        $product[$key]['in_cart'] = false;
                        $product[$key]['in_wishlist'] = false;

                    }
                // currency 
                if(!empty($currencycode)){
                    $currency = $this->currencyFetch($currencycode);
                    $product[$key]['currency_sign'] = $currency['sign'];
                    $product[$key]['currency_code'] = $currency['code'];
                }
               // currency conversion
                $p_price = $this->currencyConvert($currencycode,$val->p_price);
                $s_price = $this->currencyConvert($currencycode,$val->s_price);
                $val->p_price = round($p_price);
                $val->s_price = round($s_price);
               // language
                if(!empty($request->language) && ($request->language = "arabic")){

                    $product[$key]['pname'] = $val->arab_pname;
                    $product[$key]['short_description'] = $val->arab_short_description;
                    $product[$key]['long_description'] = $val->arab_long_description;
                }

                //Product Bids
                $productbid = ProductBid::where('product_id',$val->id)->first();
                if(!empty($productbid)){

                   $mytime = Carbon\Carbon::now();
                   $currenttime  =  $mytime->toDateString();
                   $productbidavailable = ProductBid::whereDate('end_date','>=',$currenttime)->first();
                   if(!empty($productbidavailable)){
                        $product[$key]['bid_status'] = 'is_available';
                   }
                   else{
                        $product[$key]['bid_status'] = 'not_available';
                   }
                    $product[$key]['start_date']    = $productbid->start_date;
                    $product[$key]['end_date']      = $productbid->end_date;
                    $product[$key]['min_bid_price'] = $productbid->min_bid_price;
                    $product[$key]['step_price']    = $productbid->step_price;

                }
                //Attributes
                $productAttributes = ProductAttribute::where('product_id',$val->id)->groupBy('attr_id')->get();
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
                        //cart 
                        $product[$key]['attributes'] = $attrdata;
                 }
                 else{
                    $product[$key]['attributes'] = [];  
                 }
                //Variants
                $productVariants = ProductVariants::select('parent_id','p_id','variant_value','variant_sku','variant_price','variant_stock','variant_images')->where('parent_id',$val->id)->get();
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
                        $product[$key]['variants'] = $productVariants;
                }
                else{
                        $product[$key]['variants'] = $productVariants; 
                }



          }

    }



    public function create()
    {
    }



     public function relatedproduct(Request $request)
    {
         $currencycode = $request->currency_code;
        $pro=Product::orderBY('id','DESC')->where('parent_id','=',0)->where('cat_id','=',$request->cat_id)->limit('5')->where('product_type','!=','giftcard')->where('product_type','!=','card')->where('is_publish','=',1)->where('product_type','!=','booking');
        if(!empty($request->location)){
            $pro->leftJoin('city_user', 'city_user.user_id', '=', 'products.vendor_id')->where('city_user.city_id', $request->location);

        }
        $product = $pro->get();
        $banner = Setting::where('name','=','arrival_banner')->first('value');
        $products = [];
        $url = PageMeta::where('key','new_product_url')->first();
        if(count($product)>0){
            $this->productCommondata($product,$currencycode);
             $products['product'] = $product;
             $banner['image']  = url('images/'. $banner->value);
             return response()->json(['status' => true, 'message' => "success",'banner'=>$banner['image'], 'product' => $products], 200);   
        }
        else{
            return response()->json(['status' => false, 'message' => "unsuccess", 'product' => []], 200);

        }
    }


    public function productAttributes(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required'
        ]);
        $resultattrdata = [];
         $currencycode = $request->currency_code;
        $product=Product::where('id','=',$request->product_id)->first();
        if(!empty($product)){
            if($product->product_type == "single"){
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
                    $resultattrdata[] = $attrdata;
                }
            }
            else{
                $variants = [];
                $variations = DB::table('variations')->where('parent_id',$request->product_id)->where('attribute_id',$request->attribute_id)->where('attribute_term_id',$request->attr_value_id)->get();
                foreach($variations as $key => $vAL){
                   $variants[] = $vAL->variant_id;
                }
                $resultattrdata = ProductVariants::select('parent_id','p_id','variant_value','variant_sku','variant_price','variant_stock','variant_images')->whereIn('id',$variants)->get();
                $arr_attr = [];
                foreach($resultattrdata as $v_key => $v_val){
                    foreach(json_decode($v_val->variant_value) as $key1 =>  $val1){
                        $attrval = AttributeValue::where('id', $val1)->first();
                        $arr_attr[$key1]['key'] = $attrval->attr_value_name;
                        $arr_attr[$key1]['value'] = $val1; 
                        $resultattrdata[$v_key]['variant_value'] = $arr_attr;

                    }

                }
            }

        return response()->json(['status' => true, 'message' => "success", 'attributes' => $resultattrdata], 200); 

        }
        else{
            return response()->json(['status' => false, 'message' => "not found", 'attributes' => []], 200);  
        }

    }



    public function newproduct(Request $request)
    {
        // $product=Product::orderBY('id','DESC')->where('parent_id','=',0)->where('product_type','!=','giftcard')->where('product_type','!=','card')->limit('8'); 
         $currencycode = $request->currency_code;
        $pro = Product::orderBY('products.id','DESC')->where('parent_id','=',0)->where('product_type','!=','giftcard')->where('product_type','!=','card')->limit('10')->where('is_publish','=',1)->where('product_type','!=','booking');
        if(!empty($request->location)){
            $pro->leftJoin('city_user', 'city_user.user_id', '=', 'products.vendor_id')->where('city_user.city_id', $request->location);
        }
        $product = $pro->get();
        $banner = Setting::where('name','=','arrival_banner')->first('value');
        $products = [];
        $url = PageMeta::where('key','new_product_url')->first();
        if(count($product)>0){
            $this->productCommondata($product,$currencycode);
         $products['product'] = $product;
         $banner['image']  = url('images/'. $banner->value);
         return response()->json(['status' => true, 'message' => "success",'banner'=>$banner['image'], 'product' => $products], 200);   
        }
        else{
            return response()->json(['status' => false, 'message' => "unsuccess", 'product' => []], 200);
        }

    }



    public function bestseller(Request $request){
         $currencycode = $request->currency_code;
        $prod=Product::where('featured','=',1)->where('parent_id','=',0)->where('product_type','!=','giftcard')->where([['product_type','!=','card'],['product_type','!=','booking']])->limit('5')->where('is_publish','=',1)->orderBy('id', 'DESC'); 
        if(!empty($request->location)){
            $prod->leftJoin('city_user', 'city_user.user_id', '=', 'products.vendor_id')->where('city_user.city_id', $request->location);
        }

        $product = $prod->get();
        $products = [];
        if(count($product)>0){
             $products['url'] = 'sfcsd';
             $this->productCommondata($product,$currencycode);
            $products['product'] = $product;
            return response()->json(['status' => true, 'message' => "success", 'product' => $products], 200); 
        }
        else{
            return response()->json(['status' => false, 'message' => "unsuccess", 'product' => []], 200);
        }

    }



    public function trendingProduct(Request $request){
         $currencycode = $request->currency_code;

        $pro=Product::orderBY('avg_rating','DESC')->where('parent_id','=',0)->where('product_type','!=','giftcard')->where('product_type','!=','card')->limit('7')->where([['is_publish','=',1],['product_type','!=','booking']]); 
        if(!empty($request->location)){
            $pro->leftJoin('city_user', 'city_user.user_id', '=', 'products.vendor_id')->where('city_user.city_id', $request->location);
        }
        $product = $pro->get();
        $products = [];
        if(count($product)>0){
         $products['url'] = 'sfcsd';
          $this->productCommondata($product,$currencycode);
            $products['product'] = $product;
            return response()->json(['status' => true, 'message' => "success", 'product' => $products], 200); 

        }
        else{
            return response()->json(['status' => false, 'message' => "unsuccess", 'product' => []], 200);
        }
    }

    public function Featureproduct(Request $request){
         $currencycode = $request->currency_code;
        $pro=Product::where('featured',1)->where('parent_id','=',0)->where('product_type','!=','giftcard')->where('product_type','!=','card')->limit('8')->where([['is_publish','=',1],['product_type','!=','booking']]); 
        if(!empty($request->location)){
            $pro->leftJoin('city_user', 'city_user.user_id', '=', 'products.vendor_id')->where('city_user.city_id', $request->location);
        }
        $product = $pro->get();
        $products = [];
        if(count($product)>0){
         $products['url'] = 'sfcsd';
        $this->productCommondata($product,$currencycode);
        $products['product'] = $product;
            return response()->json(['status' => true, 'message' => "success", 'product' => $products], 200); 
        }
        else{
            return response()->json(['status' => false, 'message' => "unsuccess", 'product' => []], 200);
        }
    }



    public function singleproduct(Request $request){
        if($request->slug){
            $product=Product::where('slug','=',$request->slug)->first();
        }
        else{
            $product=Product::where('id','=',$request->id)->first(); 
        }

        if(!empty($product)){

            $product->featured_image = url('products/feature/'. $product->featured_image);
            $gallery_data = [];
           // dd($product->gallery_image);
                if($product->gallery_image != "null"){
                    foreach(json_decode($product->gallery_image) as $gall_val){
                        $value1 = url('products/gallery/' . $gall_val);
                        $gallery_data[] = $value1;

                    }

                    $product->gallery_image = $gallery_data;
                }



                 //cart & wishlist
                 if (Auth::guard('api')->check()) {
                    $user = Auth::guard('api')->user();
                    $user_id = $user->id;
                } 
                if(isset($user_id)){
                    $Cart =    Cart::where('user_id',$user_id)->where('product_id',$product->id)->first();
                    if(!empty($Cart)){
                        $product['in_cart'] = true;
                    }
                    else{
                        $product['in_cart'] = false;   
                    }
                    $wishlist = Wishlist::where('user_id',$user_id)->where('product_id',$product->id)->first();
                    if(!empty($wishlist)){
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

                //Store
                $storedata = [];
                $storemeta = VendorSetting::where('vendor_id',$product->vendor_id)->pluck('value','name');
                $storeCat = Category::where('vendor_id',$product->vendor_id)->get();
                 $storedata['first_name'] = $storemeta['first_name'];
                 $storedata['last_name'] = $storemeta['last_name'];
                   $storedata['store_id'] = $product->vendor_id;
                 $storedata['profile_img'] = url('images/vendor/settings/'.$storemeta['profile_img']);
                 $storedata['banner_img'] = url('images/vendor/settings'.$storemeta['banner_img']);
                 $storedata['store_category'] = $storeCat;
                 $product['storemeta'] = $storedata;


                  // language

                if(!empty($request->language) && ($request->language = "arabic")){

                    $product['pname'] = $product->arab_pname;

                    $product['short_description'] = $product->arab_short_description;

                    $product['long_description'] = $product->arab_long_description;
                }


                //Product Bids
                $productbid = ProductBid::where('product_id',$product->id)->first();

                if(!empty($productbid)){
                   $mytime = Carbon\Carbon::now();

                   $currenttime  =  $mytime->toDateString();

                   $productbidavailable = ProductBid::whereDate('end_date','>=',$currenttime)->first();
                   if(!empty($productbidavailable)){

                        $product['bid_status'] = 'is_available';

                   }

                   else{

                        $product['bid_status'] = 'not_available';

                   }
                    $product['start_date']    = $productbid->start_date;

                    $product['end_date']      = $productbid->end_date;

                    $product['min_bid_price'] = $productbid->min_bid_price;

                    $product['step_price']    = $productbid->step_price;

                }
                //Attributes
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
                  //Variants
                  $variants = DB::table('variations')->where('parent_id',$request->id)->groupBy('attribute_id')->get();
                  $arr_attr = [];

                  if(count($variants) > 0){

                      foreach($variants as $v_k => $v_value){

                        $attrdataa = Attribute::where('id', $v_value->attribute_id)->first();
                        $arr_attr[$v_k]['name'] = $attrdataa->slug;
                        $vari = DB::table('variations')->where('parent_id',$request->id)->where('attribute_id',$v_value->attribute_id)->groupBy('attribute_term_id')->get();
                        $attr_term = [];

                        foreach($vari as $t_key => $t_val){
                            $attrval = AttributeValue::where('id',  $t_val->attribute_term_id)->first();

                            $stocks = ProductVariants::where('id',$t_val->variant_id)->first();

                            $attr_term[$t_key]['id'] = $attrval->id;
                            $attr_term[$t_key]['name'] = $attrval->slug;

                            $attr_term[$t_key]['sku'] = $stocks->variant_sku;
                            $attr_term[$t_key]['price'] = $stocks->variant_price;

                            $attr_term[$t_key]['stock'] = $stocks->variant_stock;

                            $varimg = json_decode($stocks->variant_images);

                            $attr_term[$t_key]['image'] = url('products/gallery/' .$varimg[0]);

                            $arr_attr[$v_k]['values'] = $attr_term;

                        }


                      }

                       $product['variants'] = $arr_attr;

                  }
                  else{

                  $product['variants'] = [];

                 }

            return response()->json(['status' => true, 'message' => "singleproduct", 'product' => $product], 200);

        }
        else{
            return response()->json(['status' => false, 'message' => "singleproduct", 'product' => []], 200);

        }


    }





    public function allFilters(Request $request)



    {



        if($request->category_id){



            $product = Product::whereIn('cat_id',$request->category_id)->where('parent_id',0)->get();



            $pro_id = [];



            $attrdata = [];



            if(count($product) > 0){



                foreach($product as $key => $value){



                    $pro_id[] = $value->id;



                }



                if(!empty($pro_id)){



                    $proattr = ProductAttribute::whereIn('product_id',$pro_id)->groupBy('attr_id')->get();



                    if(count($proattr)>0){



                        foreach($proattr as $attr_key => $attr_val){



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



                      



                       return response()->json(['status' => true, 'message' => "success", 'attributes' => $attrdata], 200);



                    }



                    else{



                        return response()->json(['status' => false, 'message' => "unsuccess", 'attributes' => []], 200);        



                    }



                }



                else{



                    return response()->json(['status' => false, 'message' => "unsuccess", 'attributes' => []], 200);



                }







            }



            else{



                return response()->json(['status' => false, 'message' => "unsuccess", 'attributes' => []], 200);



            }



        }



        else{







            $attributes = Attribute::select('id','slug')->get();



            if(count($attributes)>0){



                foreach($attributes as $key => $value){



                    $attributesValue = AttributeValue::select('id','attr_id','slug')->where('attr_id',$value->id)->get();



                    $attributes[$key]['attributes_value'] = $attributesValue;



                }



                return response()->json(['status' => true, 'message' => "success", 'attributes' => $attributes], 200);



            }



            else{







                return response()->json(['status' => false, 'message' => "unsuccess", 'attributes' => []], 200);







            }



           



        }



        







        



        



    }







    public function filterProduct(Request $request){



        DB::enableQueryLog();







        $product =Product::select('products.*')



                            ->where('parent_id','=',0);



        if(!empty($request->sorting)){



            $product->orderBy('id', $request->sorting);



        }



        if(!empty($request->category_id)){



            $product->where('cat_id',$request->category_id);



        }



        if(!empty($request->filter_data[0]) && $request->filter_data[0]['type']=="attribute" && !empty($request->filter_data[0]['attributes_id'])){



            $imp=$request->filter_data[0]['attributes_id'];



            $proattr = [];



            $proAttr = DB::table('product_attributes')->whereIn('attr_id', $imp)->get();



            foreach($proAttr as $key => $val){



               $proattr[] = $val->product_id; 



            }



            $product->whereIn('id',$proattr);



        }



        if(!empty($request->filter_data[0]) && $request->filter_data[0]['type']=="attribute_value" && !empty($request->filter_data[0]['attributes_value_id'])){



            $imp1   =   $request->filter_data[0]['attributes_value_id'];   



            $proattr1 = [];



            $proAttr1 = DB::table('product_attributes')->whereIn('attribute_value', $imp1)->get();



            foreach($proAttr1 as $key1 => $val1){



               $proattr1[] = $val->product_id; 



            }



            $product->whereIn('id',$proattr1);



        }



        if($request->filter_data[2]['min_price']){



            $product->where('s_price','>=',$request->filter_data[2]['min_price'])



                    ->where('s_price','<=',$request->filter_data[2]['max_price']);



           



        }







        $data = $product->get();







        //dd(DB::getQueryLog());







        return response()->json(['status' => true, 'message' => "success", 'data' => $data], 200);



      



    }











    public function searchProduct(Request $request){



        



        $category = Category::where('title', 'like', "%{$request->search}%")->get();







        if(count($category) > 0){







            $catId =  [];







            foreach($category as $key => $value){







                $catId[] = $value->id;







            }



        }







        if(!empty($catId)){







            if(!empty($request->page) && !empty($request->limit)){



                $page = $request->page;



                $limit = $request->limit;



                $pro=Product::where('parent_id',0)->where('is_publish','=',1)->whereIn('cat_id',$catId)



                ->orWhereIn('cat_id_2', $catId)



                ->orWhereIn('cat_id_3', $catId)



                ->limit($limit)



                ->offset(($page - 1) * $limit);



                if(!empty($request->location)){



                    $pro->leftJoin('vendorsettings', 'vendorsettings.vendor_id', '=', 'products.vendor_id');



                    $pro->where('vendorsettings.value', '=', $request->location)->select('products.*','vendorsettings.name');



                }



                $product = $pro->get();







            }



            else{







                $pro=Product::where('parent_id',0)->whereIn('cat_id',$catId)



                ->orWhereIn('cat_id_2', $catId)



                ->orWhereIn('cat_id_3', $catId);



                if(!empty($request->location)){



                    $pro->leftJoin('city_user', 'city_user.user_id', '=', 'products.vendor_id')->where('city_user.city_id', $request->location);



                }



                $product = $pro->get();



            }



        }



        else{



            if(!empty($request->page) && !empty($request->limit)){



            



                $pro=Product::where('parent_id',0)->where('pname', 'like', "%{$request->search}%")->limit($limit)



                ->offset(($page - 1) * $limit);  



                if(!empty($request->location)){



                    $pro->leftJoin('city_user', 'city_user.user_id', '=', 'products.vendor_id')->where('city_user.city_id', $request->location);



                }



                $product = $pro->get();







            }



            else{



                $pro=Product::where('parent_id',0)->where('pname', 'like', "%{$request->search}%"); 



                if(!empty($request->location)){



                    $pro->leftJoin('vendorsettings', 'vendorsettings.vendor_id', '=', 'products.vendor_id');



                    $pro->where('vendorsettings.value', '=', $request->location)->select('products.*','vendorsettings.name');



                }



                $product = $pro->get(); 



            }



        }



        if(count($product)>0){



            foreach($product as $key => $val){



                $data = [];



                $gallery = json_decode($val->gallery_image);



                if(!empty($gallery)){



                    foreach ($gallery as $key1 => $value) {



                        $value1 = url('products/gallery/' . $value);



                        $data[] = $value1;



                    }



                $product[$key]['gallery_image'] = $data;



                }



                if(!empty($val->featured_image)){



                $product[$key]['featured_image'] = url('products/feature/'. $val->featured_image);



                }



                //cart & wishlist



                if (Auth::guard('api')->check()) {



                    $user = Auth::guard('api')->user();



                    $user_id = $user->id;



                } 



                if(isset($user_id)){



                    $Cart =    Cart::where('user_id',$user_id)->where('product_id',$val->id)->first();



                    if(!empty($Cart)){



                        $product[$key]['in_cart'] = true;



                    }



                    else{



                        $product[$key]['in_cart'] = false;     



                    }



                    $wishlist = Wishlist::where('user_id',$user_id)->where('product_id',$val->id)->first();



                    if(!empty($wishlist)){



                        $product[$key]['in_wishlist'] = true;



                    }



                    else{



                        $product[$key]['in_wishlist'] = false;    







                    }



                }



                else{



                    $product[$key]['in_cart'] = false;



                    $product[$key]['in_wishlist'] = false;







                }



                 // currency 



                 if(!empty($request->currency_code)){



                    $currency = $this->currencyFetch($request->currency_code);



                    $product[$key]['currency_sign'] = $currency['sign'];



                    $product[$key]['currency_code'] = $currency['code'];



                }


                // currency conversion
                $p_price = $this->currencyConvert($request->currency_code,$val->p_price);
                $s_price = $this->currencyConvert($request->currency_code,$val->s_price);
                $val->p_price = round($p_price);
                $val->s_price = round($s_price);



                // language



                if(!empty($request->language) && ($request->language = "arabic")){







                    $product[$key]['pname'] = $val->arab_pname;



                    $product[$key]['short_description'] = $val->arab_short_description;



                    $product[$key]['long_description'] = $val->arab_long_description;



                }





                //Product Bids



                $productbid = ProductBid::where('product_id',$val->id)->first();

                if(!empty($productbid)){

                   

                   $mytime = Carbon\Carbon::now();

                   $currenttime  =  $mytime->toDateString();

                   $productbidavailable = ProductBid::whereDate('end_date','>=',$currenttime)->first();



                   if(!empty($productbidavailable)){

                        $product[$key]['bid_status'] = 'is_available';

                   }

                   else{

                        $product[$key]['bid_status'] = 'not_available';

                   }



                    $product[$key]['start_date']    = $productbid->start_date;

                    $product[$key]['end_date']      = $productbid->end_date;

                    $product[$key]['min_bid_price'] = $productbid->min_bid_price;

                    $product[$key]['step_price']    = $productbid->step_price;





                }



                



                if($val->product_type == "single"){



                    $productAttributes = ProductAttribute::where('product_id',$val->id)->groupBy('attr_id')->get();



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



                        $product[$key]['attributes'] = $attrdata;



                    }



                }



                else{



                    $productVariants = ProductVariants::select('parent_id','p_id','variant_value','variant_sku','variant_price','variant_stock','variant_images')->where('parent_id',$val->id)->get();



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



                        $product[$key]['variants'] = $productVariants;



                    }



                }



    



                



    



            } 



            return response()->json(['status' => true, 'message' => "products", 'product' => $product], 200);



        }



        else{



            return response()->json(['status' => false, 'message' => "products", 'product' => []], 200);



        }







    }







    public function valueOfTheDay(Request $request){

        $currentDate  = Carbon\Carbon::now()->toDateString();
         $currencycode = $request->currency_code;
       $pro = Product::where('parent_id',0)->where('product_type','!=','giftcard')->where('product_type','!=','card')->whereDate('offer_end_date','>=',$currentDate)->where('is_publish','=',1); 
        if(!empty($request->location)){
            $pro->leftJoin('city_user', 'city_user.user_id', '=', 'products.vendor_id')->where('city_user.city_id', $request->location);
        }
        $product = $pro->get();
       $banner = Setting::where('name','=','value_banner')->first('value');

       if(count($product)>0){
             $this->productCommondata($product,$currencycode); 
            return response()->json(['status' => true, 'message' => "success", 'banner'=>  $banner['image'] , 'product' => $product], 200);

       }

       else{

        return response()->json(['status' => false, 'message' => "no products", 'product' => []], 200);  
       }



    }







    public function topHunderd(Request $request){
        $currencycode = $request->currency_code;
        $pro = Product::where('parent_id',0)->where('product_type','!=','giftcard')->where('product_type','!=','card')->where('top_hunderd',1)->where('is_publish','=',1);
        if(!empty($request->location)){
            $pro->leftJoin('city_user', 'city_user.user_id', '=', 'products.vendor_id')->where('city_user.city_id', $request->location);
        }
        $product = $pro->get(); 
        $banner = Setting::where('name','=','top_banner')->first('value'); 


        if(count($product)>0){

        $this->productCommondata($product,$currencycode); 
             return response()->json(['status' => true, 'message' => "success", 'banner'=>  $banner['image'] , 'product' => $product], 200);  

        }

        else{

         return response()->json(['status' => false, 'message' => "no products", 'product' => []], 200);

        }

    }







    public function feedback()



    {



        //







    }



    public function feedbacksave( Request $request)



    {



        if (Auth::guard('api')->check()) {







            $user = Auth::guard('api')->user();







        } 







        $user_id = $user->id;







        $validator = Validator::make($request->all(), [



            'rating' => 'required',



            'discription' => 'required',



            'follow_up' => 'required',



            'product_id' => 'required',



            'user_id' => 'required'







        ]);







        if ($validator->fails()) {



            return response()->json(['status' => false, 'message' => implode("", $validator->errors()->all())], 200);



        }







       $feedback = Feedback::create([



            'rating'      => $request->rating,



            'discription'     => $request->discription,



            'follow_up'     => $request->follow_up,



            'product_id'     => $request->product_id,



            'user_id'     => $user_id,



          



        ]);







        return response()->json(['status' => true,'message' => "success" ,"data"=>$feedback], 200);







    }







    public function feedbacklist(Request $request)



    {   



            $Feedback=Feedback::orderBY('id','DESC')->where('product_id','=',$request->product_id)->get(); 



            $validator = Validator::make($request->all(), [



                'product_id' => 'required'



            ]);







            if ($validator->fails()) {



                return response()->json(['status' => false, 'message' => implode("", $validator->errors()->all())], 200);



            }



            if(count($Feedback)>0){







        $Feedback=Feedback::orderBY('id','DESC')->where('product_id','=',$request->product_id)->get(); 







            return response()->json(['status' => true,'message' => "success" ,"data"=>$Feedback], 200);



        }else{



            return response()->json(['status' => false,'message' => "no Feedback" ], 200);



        }



    }







    public function brands(Request $request){







        if(!empty($request->cat_id)){



            $product = Product::whereIn('cat_id',$request->cat_id)->groupBy('brand_slug')->get();



            $getslug = [];



            foreach($product as $val){



                $getslug[] = $val->brand_slug;



            }



            $brands = Brand::whereIn('slug',$getslug)->get();



        }



        else{



            $brands = Brand::all();



        }



       



      



        if(count($brands) > 0){







            if($request->language){







                foreach($brands as $key => $val){



                    $val->title = $val->arabic_title;



                }







            }







            return response()->json(['status' => true,'message' => "success" ,"brand"=>$brands], 200);







        }else{



            return response()->json(['status' => true,'message' => "success" ,"brand"=>[]], 200);



        }







    }







    public function offerProduct(Request $request){







        $pro = Product::where('parent_id',0)->where('product_type','!=','giftcard')->where('product_type','!=','card')->where('in_offer',1)->where('is_publish','=',1); 
        $currencycode = $request->currency_code;


         if(!empty($request->location)){



             $pro->leftJoin('city_user', 'city_user.user_id', '=', 'products.vendor_id')->where('city_user.city_id', $request->location);



         }



         $products = $pro->get();



 



        $banner = Setting::where('name','=','value_banner')->first('value');



 



        if(count($products)>0){



 



             foreach($products as $key => $val){



                 $data = [];



                 $gallery = json_decode($val->gallery_image);



                 if(!empty($gallery)){



                     foreach ($gallery as $key1 => $value) {



                         $value1 = url('products/gallery/' . $value);



                         $data[] = $value1;



                     }



                 $products[$key]['gallery_image'] = $data;



                 }



                 if(!empty($val->featured_image)){



                 $products[$key]['featured_image'] = url('products/feature/'. $val->featured_image);



                 }



                  //cart & wishlist



                  if (Auth::guard('api')->check()) {



                     $user = Auth::guard('api')->user();



                     $user_id = $user->id;



                 } 



                 if(isset($user_id)){



                     $Cart =    Cart::where('user_id',$user_id)->where('product_id',$val->id)->first();



                     if(!empty($Cart)){



                         $products[$key]['in_cart'] = true;



                     }



                     else{



                         $products[$key]['in_cart'] = false;     



                     }



                     $wishlist = Wishlist::where('user_id',$user_id)->where('product_id',$val->id)->first();



                     if(!empty($wishlist)){



                         $products[$key]['in_wishlist'] = true;



                     }



                     else{



                         $products[$key]['in_wishlist'] = false;    



 



                     }



                 }



                 else{



                     $products[$key]['in_cart'] = false;



                     $products[$key]['in_wishlist'] = false;



 



                 }



                 // currency 



                 if(!empty($request->currency_code)){



                     $currency = $this->currencyFetch($request->currency_code);



                     $products[$key]['currency_sign'] = $currency['sign'];



                     $products[$key]['currency_code'] = $currency['code'];



                 }



                 // language



                 if(!empty($request->language) && ($request->language = "arabic")){



 



                     $products[$key]['pname'] = $val->arab_pname;



                     $products[$key]['short_description'] = $val->arab_short_description;



                     $products[$key]['long_description'] = $val->arab_long_description;



                 }



                  //Product Bids



                $productbid = ProductBid::where('product_id',$val->id)->first();

                if(!empty($productbid)){

                   

                   $mytime = Carbon\Carbon::now();

                   $currenttime  =  $mytime->toDateString();

                   $productbidavailable = ProductBid::whereDate('end_date','>=',$currenttime)->first();



                   if(!empty($productbidavailable)){

                        $products[$key]['bid_status'] = 'is_available';

                   }

                   else{

                        $products[$key]['bid_status'] = 'not_available';

                   }



                    $products[$key]['start_date']    = $productbid->start_date;

                    $products[$key]['end_date']      = $productbid->end_date;

                    $products[$key]['min_bid_price'] = $productbid->min_bid_price;

                    $products[$key]['step_price']    = $productbid->step_price;





                }



                 



                 if($val->product_type == "single"){



                     $productAttributes = ProductAttribute::where('product_id',$val->id)->groupBy('attr_id')->get();



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



                         $product[$key]['attributes'] = $attrdata;



                     }



                 }



                 else{



                     $productVariants = ProductVariants::select('parent_id','p_id','variant_value','variant_sku','variant_price','variant_stock','variant_images')->where('parent_id',$val->id)->get();



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



                         $product[$key]['variants'] = $productVariants;



                     }



                 }



 



                 



                 $banner['image']  = url('images/'. $banner->value);



             } 



 



             return response()->json(['status' => true, 'message' => "success", 'banner'=>  $banner['image'] , 'product' => $products], 200);



 



        }



        else{



 



         return response()->json(['status' => false, 'message' => "no products", 'product' => []], 200);   



        }











    }











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



    public function destroy($id)



    {



        //



    }





     public function bidproduct(Request $request){

          $prod=Product::orderBy('id', 'DESC')->where([['product_type','=','bid'],['product_type','!=','giftcard'],['product_type','!=','card'],['parent_id','=',0],['is_publish','=',1]]);

             $currencycode = $request->currency_code;

            if(!empty($request->location)){

                $prod->leftJoin('city_user', 'city_user.user_id', '=', 'products.vendor_id')->where('city_user.city_id', $request->location);

            }
            if(!empty($request->is_featured) && ($request->is_featured == 1)){
              $prod->where('products.featured',1);  
            }
             if(!empty($request->product_limit)){
              $prod->limit($request->product_limit);
            }

        if(!empty($request->page) && !empty($request->limit)){
            $page = $request->page;

            $limit = $request->limit;

            $product = $prod->limit($limit)->offset(($page - 1) * $limit)->get();

        }

        else{

        $product = $prod->get();
        }

        //dd($product);
        $currencycode = $request->currency_code;

        if(count($product) > 0){

              $this->productCommondata($product,$currencycode);

            return response()->json(['status' => true, 'message' => "All product list", 'product' => $product], 200);

        }

        else{
            return response()->json(['status' => false, 'message' => "Product Not Found", 'product' => []], 200);

        }

 }


public function appdashboard(Request $request){

    $data =  [];
    $bannerImages = [];
    $banner = Setting::where('name','app_dashbooard_banner')->first();
    foreach(json_decode($banner->value) as $sliderbanner){
           $bannerImages[] =  url('images/' . $sliderbanner);
    }

     $currencycode = $request->currency_code;

    $category =  $category=Category::select('id','title','slug','category_image','category_image_banner','slug','parent_id','arab_description','arab_title')->where('parent_id','=',0)->get();
    if(count($category)>0){
        foreach($category as $catval){
            $catval->category_image = url('category/'.$catval->category_image);
            $catval->category_image_banner = url('category/'.$catval->category_image_banner);
        }
    }
     $prod=Product::where('product_type','!=','giftcard')->where('product_type','!=','card')->where('parent_id','=',0)->where('is_publish','=',1);
     if(!empty($request->location)){
            // $prod->leftJoin('vendorsettings', 'vendorsettings.vendor_id', '=', 'products.vendor_id');
            // $prod->where('vendorsettings.value', '=', $request->location)->select('products.*','vendorsettings.name');
            $prod->leftJoin('city_user', 'city_user.user_id', '=', 'products.vendor_id')->where('city_user.city_id', $request->location);
        }

        if($request->category_id){
            $prod->where('products.cat_id',$request->category_id); 
        }

        if($request->search){
            $prod->where('products.pname','LIKE','%'.$request->search.'%'); 
        }

        if($request->brand){
            $prod->where('products.brand_slug',$request->brand);
        }

        if($request->price_range){
            $exp = explode("-",$request->price_range); 
                $min_price = $exp[0];
                $max_price = $exp[1];
                $prod->whereBetween('products.s_price', [$min_price, $max_price]);
        }
        if($request->in_stock){
         if($request->in_stock == true){
            $prod->where('products.in_stock','>',0); 
         }
        }
        if($request->sort_by){
            if($request->sort_by == "price_low_to_high"){
                $prod->orderBy('products.s_price','ASC');       
            }
            elseif($request->sort_by == "price_high_to_low"){
                 $prod->orderBy('products.s_price','DESC'); 
            }
            elseif($request->sort_by == "top_rated"){
                $prod->orderBy('products.avg_rating','DESC'); 
            }
            elseif($request->sort_by == "recently_added"){
                $prod->orderBy('products.id','DESC'); 
            }
        }
        // if(isset($request->popular_product) && ($request->popular_product == true)){
        //     $prod->leftJoin('ordered_products', 'ordered_products.product_id', '=', 'products.id')->groupBy(['products.id'])->orderByDesc('products.*, SUM(ordered_products.quantity) AS quantity_sold');
        // }
        if(!empty($request->on_sale) && $request->on_sale == true){
            $prod->where('products.offer_discount','!=', null);
        }
        if($request->attr_value_id){

            $ProductAttribute = ProductAttribute::select('product_id')->whereIn('attr_value_id',$request->attr_value_id)->groupBy('product_id')->get();

            if(count($ProductAttribute)>0){
                foreach($ProductAttribute as $pro_attr => $pro_value){
                    $proidmatch[] = $pro_value->product_id;   
                }
            }
            if(!empty($proidmatch)){
                $prod->whereIn('products.id', $proidmatch); 

            }
        }
        if(!empty($request->page) && !empty($request->limit)){
            $page = $request->page;
            $limit = $request->limit;
            $product = $prod->limit($limit)->offset(($page - 1) * $limit)->get();

        }
        else{
        $product = $prod->get();
        }
        if(count($product) > 0){
            $this->productCommondata($product,$currencycode,$request->device_id);
        }
        



    $data['banner'] = $bannerImages;
    $data['category'] = $category;
    $data['product'] = $product;


return response()->json(['status' => true, 'message' => "Product Not Found", 'dashboard' => $data], 200);
}


public function trendingCategory(Request $request){
   $product = Product::where([['product_type','!=','giftcard'],['product_type','!=','card'],['parent_id','=',0],['is_publish','=',1],['avg_rating','!=',0]])->orderBy('avg_rating','DESC')->get();

   $catId = [];

   foreach($product as $key => $val){
    if(!empty($val->cat_id)){
         $catId[] = $val->cat_id;
    }

   }
   //dd($catId);

    $category = Category::whereIn('id',$catId)->limit(5)->get();

    if(count($category) > 0){

        foreach($category as $catval){
            $catval->category_image = url('category/'.$catval->category_image);
            $catval->category_image_banner = url('category/'.$catval->category_image_banner);

        }

         return response()->json(['status' => true, 'message' => "Success", 'category' => $category], 200);

    }
    else{

         return response()->json(['status' => false, 'message' => "unsuccess", 'category' => $category], 200);
    }


}

public function reletedbidproduct(Request $request){
     $currencycode = $request->currency_code;
    $product  = Product::where([['product_type','=','bid'],['parent_id','=',0],['is_publish','=',1]])->limit('5')->get();
    if(count($product) > 0){
            $this->productCommondata($product,$currencycode);
            return response()->json(['status' => true, 'message' => "All product list", 'product' => $product], 200);
    }
    else{
            return response()->json(['status' => false, 'message' => "Product Not Found", 'product' => []], 200);
    }
}

public function categoryProduct(Request $request){
    $currencycode = $request->currency_code;
    $product  = Product::where([['cat_id','=',$request->cat_id],['parent_id','=',0],['is_publish','=',1]])->limit('5')->get();
    if(count($product) > 0){
            $this->productCommondata($product,$currencycode);
            return response()->json(['status' => true, 'message' => "All product list", 'product' => $product], 200);
    }
    else{
            return response()->json(['status' => false, 'message' => "Product Not Found", 'product' => []], 200);
    }
}
     



}



