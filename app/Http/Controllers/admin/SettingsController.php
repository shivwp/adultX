<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Models\Setting;

use App\Models\State;

class SettingsController extends Controller

{

    public function index()

    {

        $d["title"] = "Web-settings";

        $d["setting"] = Setting::pluck("value", "name");

       // dd($statedata);

        return view("admin.site-setting", $d);

    }



    public function create()

    {

    }



    public function store(Request $request)

    {

        $ship = explode(",", $request->ship_method);

        $setting["logo"] = "";

         $setting["app_dashbooard_banner"] = "";
         $setting["app_store_banner"] = "";

        $setting["value_banner"] = "";

        $setting["top_banner"] = "";

        $setting["arrival_banner"] = "";

        $setting["arab_value_banner"] = "";

        $setting["arab_top_banner"] = "";

        $setting["arab_arrival_banner"] = "";

        $setting["sale_with_us"] = "";

        $setting["arab_sale_with_us"] = "";

        $setting["all_cat_page_banner"] = "";

        $setting["arab_all_cat_page_banner"] = "";

        $setting["name"] = $request->name;

        $setting["country"] = $request->country;
       

        $setting["state"] = $request->state;

        $setting["city"] = $request->city;

        $setting["postcode"] = $request->postcode;

        $setting["help_number"] = $request->help_number;

        $setting["email"] = $request->email;

        $setting["pan_number"] = $request->pan_number;

        $setting["cin_number"] = $request->cin_number;

        $setting["gst_number"] = $request->gst_number;

        $setting["url"] = $request->url;

        $setting["address"] = $request->address;

        $setting["hour"] = $request->hour;

        $setting["instagram"] = $request->instagram;

        $setting["twitter"] = $request->twitter;

        $setting["facebook"] = $request->facebook;

        $setting["pinterest"] = $request->pinterest;

        $setting["facebook"] = $request->facebook;

       
        foreach ($setting as $key => $value) {

            if ($key == "logo" && $request->hasfile("logo")) {

                $file = $request->logo;

                $extention = $file->getClientOriginalExtension();

                $filename = time() . "." . $extention;

                $file->move("images/logo", $filename);

                Setting::updateOrCreate(

                    [

                        "name" => $key,

                    ],

                    [

                        "value" => $filename,

                    ]

                );

            }



            

            if ($value) {

                Setting::updateOrCreate(

                    [

                        "name" => $key,

                    ],

                    [

                        "value" => $value,

                    ]

                );

            }

        }

        //dd($request->selling_city);



        //Shipping Method

        $c = 0; 

        $newarray = array();

        
        //dd($newarray);

        foreach($newarray as $new_k => $new_val){

           foreach($new_val as $sub_key => $sub_val){

            CityPrice::updateOrCreate(

                [

                    "state_id" => $sub_val["state_id"],

                    "city_id" => $sub_val["city"],

                ],

                [

                    "city_id" => $sub_val["city"],



                    "normal_price" => !empty($sub_val["normal_price"])

                        ? $sub_val["normal_price"]

                        : 0,



                    "priority_price" => !empty($sub_val["property_price"])

                        ? $sub_val["property_price"]

                        : 0,

                ]

            );



           }

        }


        return back();

    }



    /**







     * Display the specified resource.







     *







     * @param  int  $id







     * @return \Illuminate\Http\Response







     */



    public function language(Request $request)

    {

        $d["title"] = "Web-settings";



        $d["setting"] = Setting::all();



        //  dd($d['setting']);



        return view("admin.site-setting", $d);

    }



    public function currency(Request $request)

    {

        //

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

}