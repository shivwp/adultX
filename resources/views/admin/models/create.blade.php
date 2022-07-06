@extends('layouts.vertical-menu.master')
@section('css')
<link href="{{ URL::asset('assets/plugins/ion.rangeSlider/css/ion.rangeSlider.css')}}" rel="stylesheet">
<link href="{{ URL::asset('assets/plugins/ion.rangeSlider/css/ion.rangeSlider.skinSimple.css')}}" rel="stylesheet">
<link href="{{ URL::asset('assets/plugins/bootstrap-daterangepicker/daterangepicker.css')}}" rel="stylesheet" />
<link href="{{ URL::asset('assets/plugins/date-picker/spectrum.css')}}" rel="stylesheet" />
<link href="{{ URL::asset('assets/plugins/fileuploads/css/fileupload.css')}}" rel="stylesheet" />
<link href="{{ URL::asset('assets/plugins/multipleselect/multiple-select.css')}}" rel="stylesheet" />
<link href="{{ URL::asset('assets/plugins/select2/select2.min.css')}}" rel="stylesheet" />
<link href="{{ URL::asset('assets/plugins/time-picker/jquery.timepicker.css')}}" rel="stylesheet" />
<style type="text/css">
    .heading_detail{
        color: #000;
        font-weight: 700;
    }
    .parc {
    margin-right: 5px;
    margin-bottom: 5px;
    }
    .parc .pip {
    position: relative;
    }
    span.pip img {
    object-fit: cover;
}
.parc .pip .btn {
    position: absolute;
    right: 2px;
    margin-top: 3px;
    background-image: linear-gradient(90deg, #282728 0, #544747);
    height: 20px;
    font-size: smaller;
    min-width: 20px !important;
    line-height: 18px;
    color: #fff;
    padding: 0 !important;
    float: left;
}
</style>
@endsection
@section('page-header')
    <!-- PAGE-HEADER -->
        <div>
            <h1 class="page-title">{{$title}}</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard.models.index') }}">Models</a></li>
                 @if(isset($model->id))
                    <li class="breadcrumb-item active" aria-current="page">Edit</li>
                @else
                    <li class="breadcrumb-item active" aria-current="page">Add</li>
                @endif
            </ol>
        </div>
    <!-- PAGE-HEADER END -->
@endsection
@section('content')
    <!-- ROW-1 OPEN-->
    <div class="card">
        <form  method="post" action="{{route('dashboard.models.store')}}" enctype="multipart/form-data">
            @csrf
            <div class="card-body">
                <input type="hidden" name="id" value="{{ old('id', isset($model->id) ? $model->id : '') }}">
                <input type="hidden" name="userid" value="{{ old('userid', isset($model->users_auto_id) ? $model->users_auto_id : '') }}">
                <h3 class="heading_detail">User Info</h3>
                    <div class="row">
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">First Name</label>
                                <input type="text" class="form-control" name="first_name" placeholder="First Name" value="{{ old('first_name', isset($model->first_name) ? $model->first_name : '') }}" required>
                                <input type="hidden" name="role" value="6">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Last Name</label>
                                <input type="text" class="form-control" name="last_name" placeholder="Last Name" value="{{ old('last_name', isset($model->last_name) ? $model->last_name : '') }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Date of Birth</label>
                                <input type="date" class="form-control" name="dob" placeholder="DOB" value="{{ old('dob', isset($model->dob) ? $model->dob : '') }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" name="email" placeholder="Last Name" value="{{ old('email', isset($model->email) ? $model->email : '') }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Create Password</label>
                                <input type="password" class="form-control" name="password" placeholder="Enter minimum 8 digit password" value="">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Phone</label>
                                <input type="number" class="form-control" name="phone" placeholder="Phone number" value="{{ old('phone', isset($model->phone) ? $model->phone : '') }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">City</label>
                                <input type="text" class="form-control" name="city" placeholder="City" value="{{ old('city', isset($model->city) ? $model->city : '') }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">State</label>
                                <input type="text" class="form-control" name="state" placeholder="State" value="{{ old('state', isset($model->state) ? $model->state : '') }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Gender</label>
                                <select name="gender" class="form-control" required>
                                    <option value="">Select Gender</option>
                                    <option value="male" {{isset($model->gender) && $model->gender == "male" ? "selected" : ''}}>Male</option>
                                    <option value="female" {{isset($model->gender) && $model->gender == "female" ? "selected" : ''}}>Female</option>
                                    <option value="transgender" {{isset($model->gender) && $model->gender == "transgender" ? "selected" : ''}}>Transgender</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Profile Image</label>
                                <input type="file" class="form-control" name="profile_image" value="{{ old('profile_image', isset($model->profile_image) ? $model->profile_image : '') }}">
                            </div>
                        </div>

                        <!-- Gallery images -->
                        <div class="col-md-12 mt-2">
                            @if(!empty($model->gallery_image))
                            @php
                            $value = json_decode($model->gallery_image);
                            @endphp
                            @if(!empty($value))
                            <div class="even" style="display: flex; flex-wrap: wrap; justify-content: flex-start;">
                                @foreach($value as $multidata)
                                <div class="parc">
                                    <span class="pip" data-title="{{$multidata}}">
                                        <img src="{{ url('gallery images').'/'.$multidata ?? "" }}" alt="" width="100" height="100">
                                        <a class="btn"><i class="fa fa-times remove" onclick="removeImage('{{$multidata}}')"></i></a>
                                    </span>
                                </div>
                                @endforeach
                            </div>
                            @endif
                            <input type="hidden" name="gallery_images_old" id="gallery_img" value="{{$model->gallery_image}}">
                            @endif

                            <label class="form-label mt-0">Add Gallery Images</label>
                            <input type="file" class="form-control" name="gallery_image[]" value="" multiple>
                        </div>
                        <!-- Gallery images -->

                    </div>
                    <hr>
                    <h3 class="heading_detail">Basic Info</h3>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Allow Phone Call</label>
                                <select name="phone" class="form-control" required>
                                    <option value="">Select</option>
                                    <option value="1" {{isset($model->phone) && $model->phone == 1 ? 'selected' : '' }}>yes</option>
                                    <option value="0" {{isset($model->phone) && $model->phone == 0 ? 'selected' : ''}}>No</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Allow Video Call</label>
                                <select name="video" class="form-control" required>
                                    <option value="">Select</option>
                                    <option value="1" {{isset($model->video) && $model->video == 1 ? 'selected' : '' }}>yes</option>
                                    <option value="0" {{isset($model->video) && $model->video == 0 ? 'selected' : ''}}>No</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Stage Name</label>
                                <input type="text" class="form-control" name="stage_name" placeholder="Stage Name" value="{{ old('stage_name', isset($model->stage_name) ? $model->stage_name : '') }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Orientation</label>
                                <select name="orientation" class="form-control" required>
                                    <option value="">Select</option>
                                    @if(count($model_orient) > 0)
                                        @foreach($model_orient as $value)
                                            <option value="{{$value->title}}" {{isset($model->Orientation) && ($model->Orientation == $value->title) ? "selected" : ''}}>{{$value->title}}</option>
                                        @endforeach
                                    @endif
                
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Model Category</label>
                                <select name="modelcategory" class="form-control" required>
                                    <option value="">Select</option>
                                    @if(count($model_cate) > 0)
                                        @foreach($model_cate as $cate)
                                            <option value="{{$cate->title}}" {{isset($model->Model_Category) && ($model->Model_Category == $cate->title) ? "selected" : ''}}>{{$cate->title}}</option>
                                        @endforeach
                                    @endif                                    
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Ethnicity</label>
                                <select name="ethnicity" class="form-control" required>
                                    <option value="">Select</option>
                                    @if(count($model_ethnic) > 0)
                                        @foreach($model_ethnic as $ethnic)
                                            <option value="{{$ethnic->title}}" {{isset($model->Ethnicity) && ($model->Ethnicity == $ethnic->title) ? "selected" : ''}}>{{$ethnic->title}}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Language</label>
                                <select name="language" class="form-control" required>
                                    <option value="">Select</option>
                                    @if(count($model_lang) > 0)
                                        @foreach($model_lang as $lang)
                                            <option value="{{$lang->title}}" {{isset($model->Language) && ($model->Language == $lang->title) ? "selected" : ''}}>{{$lang->title}}</option>
                                        @endforeach
                                    @endif
                                    
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Hair</label>
                                <select name="hair" class="form-control" required>
                                    <option value="">Select</option>
                                    @if(count($model_hair) > 0)
                                        @foreach($model_hair as $hair)
                                            <option value="{{$hair->title}}" {{isset($model->Hair) && ($model->Hair == $hair->title) ? "selected" : ''}}>{{$hair->title}}</option>
                                        @endforeach
                                    @endif
                                    
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Fetishes</label>
                                <select name="fetishes" class="form-control" required>
                                    <option value="">Select</option>
                                    @if(count($model_fet) > 0)
                                        @foreach($model_fet as $fet)
                                            <option value="{{$fet->title}}" {{isset($model->Fetishes) && ($model->Fetishes == $fet->title) ? "selected" : ''}}>{{$fet->title}}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">User Status</label>
                                <select name="user_status" class="form-control" required>
                                    <option value="">Select</option>
                                    <option value="verified" {{isset($model->user_status) && $model->user_status == "verified" ? "selected" : ''}}>Verified</option>
                                    <option value="unverified" {{isset($model->user_status) && $model->user_status == "unverified" ? "selected" : ''}}>Unverified</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="form-label">Discription</label>
                                <textarea rows="5" class="form-control" name="description">{{isset($model->discription) ? $model->discription : ''}}</textarea>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <h3 class="heading_detail">Url Info</h3>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Url #1</label>
                                <input type="text" class="form-control" name="url1" placeholder="Enter Your First Url" value="{{ old('url1', isset($model->url1) ? $model->url1 : '') }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Url #2</label>
                                <input type="text" class="form-control" name="url2" placeholder="Enter Your Second Url" value="{{ old('url2', isset($model->url2) ? $model->url2 : '') }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Url #3</label>
                                <input type="text" class="form-control" name="url3" placeholder="Enter Your Third Url" value="{{ old('url3', isset($model->url3) ? $model->url3 : '') }}" required>
                            </div>
                        </div>
                        
                    </div>
                    <hr>
                    <h3 class="heading_detail">Social Links Info</h3>
                    <div class="row">
                        @php
                            $social_link = json_decode($model->socail_links);
                        @endphp
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Twitter</label>
                                <input type="text" class="form-control" name="link1" placeholder="Enter Twitter Url" value="{{isset($social_link->twitter) ? $social_link->twitter : ''}}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Instagram</label>
                                <input type="text" class="form-control" name="link2" placeholder="Enter Instagram Url" value="{{isset($social_link->instagram) ? $social_link->instagram : ''}}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Snapchat</label>
                                <input type="text" class="form-control" name="link3" placeholder="Enter Snapchat Url" value="{{isset($social_link->snapchat) ? $social_link->snapchat : ''}}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">SpankPay</label>
                                <input type="text" class="form-control" name="link4" placeholder="Enter SpankPay Url" value="{{isset($social_link->spankpay) ? $social_link->spankpay : ''}}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Website</label>
                                <input type="text" class="form-control" name="link5" placeholder="Enter Website Url" value="{{isset($social_link->website) ? $social_link->website : ''}}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">CamSite</label>
                                <input type="text" class="form-control" name="link6" placeholder="Enter CamSite Url" value="{{isset($social_link->camsite) ? $social_link->camsite : ''}}" required>
                            </div>
                        </div>
                        
                    </div>
                    <hr>
                    <h3 class="heading_detail">Cost Info</h3>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Cost per Message</label>
                                <input type="text" class="form-control" name="costmsg" placeholder="Enter Your Cost" value="{{ old('costmsg', isset($model->cost_msg) ? $model->cost_msg : '') }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Cost per Picture</label>
                                <input type="text" class="form-control" name="costpicture" placeholder="Enter Your Cost" value="{{ old('costpicture', isset($model->cost_pic) ? $model->cost_pic : '') }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Cost per Video message</label>
                                <input type="text" class="form-control" name="costvideo_msg" placeholder="Enter Your Cost" value="{{ old('costvideo_msg', isset($model->cost_videomsg) ? $model->cost_videomsg : '') }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Cost per Audio message</label>
                                <input type="text" class="form-control" name="costaudio_msg" placeholder="Enter Your Cost" value="{{ old('costaudio_msg', isset($model->cost_audiomsg) ? $model->cost_audiomsg : '') }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Cost per Audio call</label>
                                <input type="text" class="form-control" name="costaudio_call" placeholder="Enter Your Cost" value="{{ old('costaudio_call', isset($model->cost_audiocall) ? $model->cost_audiocall : '') }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Cost per Video call</label>
                                <input type="text" class="form-control" name="cost_videocall" placeholder="Enter Your Cost" value="{{ old('cost_videocall', isset($model->cost_videocall) ? $model->cost_videocall : '') }}" required>
                            </div>
                        </div>
                    </div>
                    @if(isset($model->id))
                        <button class="btn btn-success-light mt-3 " type="submit">Update</button>
                    @else
                        <button class="btn btn-success-light mt-3 " type="submit">Save</button>
                    @endif
            </div>
        </form>
    </div>  
@endsection
@section('js')
<script src="{{ URL::asset('assets/plugins/bootstrap-daterangepicker/moment.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/bootstrap-daterangepicker/daterangepicker.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/date-picker/spectrum.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/date-picker/jquery-ui.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/fileuploads/js/fileupload.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/fileuploads/js/file-upload.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/input-mask/jquery.maskedinput.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/multipleselect/multiple-select.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/multipleselect/multi-select.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/select2/select2.full.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/time-picker/jquery.timepicker.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/time-picker/toggles.min.js') }}"></script>
<script src="{{ URL::asset('assets/js/form-elements.js') }}"></script>
<script>
    $(document).ready(function() {
          $('#dataTable').DataTable();

    });
    function removeImage(data) {
        console.log(data);
        var inputvalue = $('#gallery_img').val();
        var ary = JSON.parse(inputvalue);
        console.log(ary);

        ary.splice($.inArray(data, ary), 1);
        var asd = JSON.stringify(ary);
        $('.pip[data-title="' + data + '"]').remove();
        $('#gallery_img').val(asd);
    }
</script>
@endsection



