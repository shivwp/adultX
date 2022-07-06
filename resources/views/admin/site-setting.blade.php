@extends('layouts.vertical-menu.master')

@section('css')

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<style>

  .input-container {

    padding-bottom: 1em;

  }

  .left-inner-addon {

      position: relative;

  }

  .left-inner-addon input {

      padding-left: 35px !important; 

  }

  .left-inner-addon i {

      position: absolute;

      padding: 12px 12px;

      pointer-events: none;

  }

</style>

<link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.css" integrity="sha512-xmGTNt20S0t62wHLmQec2DauG9T+owP9e6VU8GigI0anN7OXLip9i7IwEhelasml2osdxX71XcYm6BQunTQeQg==" crossorigin="anonymous" referrerpolicy="no-referrer" />

@endsection

@section('page-header')

                            <div>

                                <h1 class="page-title">{{$title}}</h1>

                            </div>



@endsection

@section('content')

<div class="card">

  <div class="card-body" id="add_space">

    <form action="{{ route("dashboard.settings.store") }}" method="post" enctype="multipart/form-data">

      @csrf 

      <div class="row">



          <div class="col-md-2">



            <div class="form-group">



              <label class="control-label ">Business logo </label>



              <input type="hidden" name="logo" value="Business logo">



              <input type="file" class="form-control" id="exampleInputuname_1" name="logo" value="{{($setting['logo'])??''}}">



            </div>



          </div>



          <div class="col-md-4">



            <div class="form-group">



            <img src="{{ url('images/logo').'/'.$setting['logo'] ?? "" }}" style="height:100px;width:200px;" alt="logo">



          </div>



      </div>







       







        <div class="col-md-6">







          <div class="form-group">







            <input type="hidden" name="name_0" value="Business Name">







            <label class="control-label ">Business Name </label>







             <input type="text" class="form-control" id="exampleInputuname_1" name="name" value="{{($setting['name'])??''}}">







           







          </div>







        </div>

      </div>







      <div class="row">







          <div class="col-md-6">







            <div class="form-group">







              <input type="hidden" name="name_1" value="Business Name">







              <label class="control-label ">Country </label>







              <input type="text" class="form-control" id="exampleInputuname_1" name="country" value="{{($setting['country'])??''}}">







            </div>







          </div>







        <div class="col-md-6">







            <div class="form-group">







              <label class="control-label ">State </label>







              <input type="text" class="form-control" id="exampleInputuname_1" name="state" value="{{($setting['state'])??''}}">







            </div>







        </div>







        <div class="col-md-6">







          <div class="form-group">







            <input type="hidden" name="name_3" value="City">







            <label class="control-label ">City </label>







            <input type="text" class="form-control" id="exampleInputuname_1" name="city" value="{{($setting['city'])??''}}">







          </div>







        </div>



        <div class="col-md-6">







          <div class="form-group">


            <label class="control-label ">Postcode </label>

              <input type="text" class="form-control" id="exampleInputuname_1" name="postcode" value="{{($setting['postcode'])??''}}">


          </div>

        </div>

        <!--/span-->
        <div class="col-md-6">

          <div class="form-group">
            <label class="control-label ">Helpline Number </label>

              <input type="text" class="form-control" id="exampleInputuname_1" name="help_number" value="{{($setting['help_number'])??''}}">
          </div>

        </div>

        <div class="col-md-6">


          <div class="form-group">

            <label class="control-label ">Email </label>
              <input type="text" class="form-control" id="exampleInputuname_1" name="email" value="{{($setting['email'])??''}}">

          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <label class="control-label ">PAN Number </label>
              <input type="text" class="form-control" id="exampleInputuname_1" name="pan_number" value="{{($setting['pan_number'])??''}}">

          </div>

        </div>

        <div class="col-md-6">
          <div class="form-group">
            <label class="control-label ">CIN Number </label>

               <input type="text" class="form-control" id="exampleInputuname_1" name="cin_number" value="{{($setting['cin_number'])??''}}">

          </div>


        </div>


        <div class="col-md-6">







          <div class="form-group">







            <label class="control-label ">GSTIN Number </label>







              <input type="text" class="form-control" id="exampleInputuname_1" name="gst_number" value="{{($setting['gst_number'])??''}}">







          </div>







        </div>







         <div class="col-md-6">







          <div class="form-group">







            <label class="control-label ">Site Url </label>







            <input type="text" class="form-control" id="exampleInputuname_1" name="url" value="{{($setting['url'])??''}}">







          </div>







        </div>







        <div class="col-md-6">







          <div class="form-group">







            <input type="hidden" name="name_4" value="Address">







            <label class="control-label ">Address </label>







            <input type="text" class="form-control" id="exampleInputuname_1" name="address" value="{{($setting['address'])??''}}">







          </div>







        </div>







        <div class="col-sm-6">







          <label>Official Hour Type</label>







             <input type="text" class="form-control" id="exampleInputuname_1" name="hour" value="{{($setting['hour'])??''}}">
        </div>
           <div class="col-md-12">
            <h4 class="mt-5">Social links</h4><hr>

        <div class="row">

        <div class="col-md-6">  

          <div class="form-group">
          <div class="left-inner-addon input-container">

            <i class="fa fa-instagram"></i>
             <input  type="text"  class="form-control"  placeholder="instagram" name="instagram" value="{{($setting['instagram'])??''}}" />
           </div>
          </div>
        </div>


        <div class="col-md-6">

          <div class="form-group">

              <div class="left-inner-addon input-container">
            <i class="fa fa-twitter"></i>

             <input  type="text" class="form-control"   placeholder="twitter" name="twitter" value="{{($setting['twitter'])??''}}" />


           </div>

          </div>

        </div>



        <div class="col-md-6">

          <div class="form-group">
            <div class="left-inner-addon input-container">

            <i class="fa fa-facebook"></i>

             <input  type="text"  class="form-control"   placeholder="Facebook" name="facebook" value="{{($setting['facebook'])??''}}" />

           </div>


          </div>

        </div>
        <div class="col-md-6">

          <div class="form-group">

              <div class="left-inner-addon input-container">

            <i class="fa fa-pinterest"></i>
             <input  type="text" class="form-control" placeholder="twitter" name="pinterest" value="{{($setting['pinterest'])??''}}" />


           </div>
          </div>
        </div>


      </div>




      </div>



<div class="form-actions" id="add_space">

    <button class="btn btn-success-light mt-3   ">  Save & update</button>

  </div>

    </div>

  </form>

</div>

</div>

@endsection

@section('js')

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.js" integrity="sha512-VvWznBcyBJK71YKEKDMpZ0pCVxjNuKwApp4zLF3ul+CiflQi6aIJR+aZCP/qWsoFBA28avL5T5HA+RE+zrGQYg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script type="text/javascript">
$('select').select2({

  createTag: function (params) {

    // Don't offset to create a tag if there is no @ symbol

    if (params.term.indexOf('@') === -1) {
      // Return null to disable tag creation

      return null;

    }

    return {
      id: params.term,
      text: params.term

    }
  }
});
</script>


<script>
  if(document.getElementById('free-shipping').checked) {
    $(".free-shipping").show();
  }
  else{
    $(".free-shipping").hide();
  }
  if(document.getElementById('normal-shipping').checked) {
    $(".normal-shipping").show();
  }
  else{
    $(".normal-shipping").hide();
  }

  if(document.getElementById('city-shipping').checked) {
    $(".city-shipping").show();
  }
  else{
    $(".city-shipping").hide();
  }
   function freeShipping()
	{
		if($('#free-shipping').is(":checked")) 
			$(".free-shipping").show();
		else
			$(".free-shipping").hide();
	}



  function normalShipping()
	{
		if($('#normal-shipping').is(":checked"))  
			$(".normal-shipping").show();
		else
			$(".normal-shipping").hide();
	}



  function cityShipping()
	{
		if($('#city-shipping').is(":checked"))
			$(".city-shipping").show();
		else
			$(".city-shipping").hide();

	}

  $(document).ready(function() {
		$("#tags").change(function(){
			var tagval = $('input[name="ship_method"]').val();
			$('#ship_method').val(tagval);
		});

	});
</script>

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>

$(document).ready(function(){

  $(".add-button").on("click", function(){

    var st_incr =  $(this).closest('.col-md-12.state_outer').data('state-index');

    var incr =  $(this).closest('.col-md-12.mb-5').find('.col-md-12.append-data .row').data('index');

    incr++

    var addbutton = $(this).val();

    var buttonindex = $(this).data('index');

    buttonindex++;

    var state_obj = jQuery('#state_obj').val();

    var html = $(this).closest('.col-md-12.mb-5').find(".append-data .row:first-child").html();

    var app_html = $(this).closest('.col-md-12.mb-5').find(".append-data").append('<div class="row mb-2" data-index="'+incr+'">'+html+'</div>');

    // jQuery(app_html).find('[name]').each(function(){

    //   console.log(jQuery(this).attr('name'));

    //   var st_incr_in =  $(this).closest('.col-md-12.state_outer').data('state-index');

    //   var incr_in =  $(this).closest('.col-md-12.mb-5').find('.col-md-12.append-data .row').data('index');

    //   incr_in++

    //   var id = jQuery(this).attr('id');

    //   if(id = 'state_city'){

    //     name_val = 'selling_city['+st_incr+']['+incr+'][city]';

    //   }else if(id = 'normal_price'){

    //     name_val = 'selling_city['+st_incr+']['+incr+'][normal_price]';

    //   }else if(id = 'property_price'){

    //     name_val = 'selling_city['+st_incr+']['+incr+'][property_price]';

    //   }

    //   jQuery(this).attr('name',name_val);

    // });



  });

});

</script>

@endsection