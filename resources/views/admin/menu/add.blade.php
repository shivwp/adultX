@extends('layouts.vertical-menu.master')

@section('css')

<link href="{{ URL::asset('assets/plugins/summernote/summernote-bs4.css')}}" rel="stylesheet">

<link href="{{ URL::asset('assets/plugins/wysiwyag/richtext.css')}}" rel="stylesheet">

<link href="{{ URL::asset('assets/plugins/fileuploads/css/fileupload.css')}}" rel="stylesheet" />

@endsection

@section('page-header')

                        <!-- PAGE-HEADER -->

                            <div>

                                <h1 class="page-title">{{$title}}</h1>

                                <ol class="breadcrumb">

                                    <li class="breadcrumb-item"><a href="{{ route('dashboard.menus.index') }}">Menu</a></li>

                                      @if(isset($menu->id))

                                        <li class="breadcrumb-item active" aria-current="page">Edit</li>

                                    @else

                                        <li class="breadcrumb-item active" aria-current="page">Add</li>

                                    @endif

                                </ol>

                            </div>

                        

                        <!-- PAGE-HEADER END -->

@endsection



@section('content')

             <div class="card">

                                <form  method="post" action="{{route('dashboard.menus.store')}}" enctype="multipart/form-data">

                                    @csrf

                                    <div class="card-body">

                                        <form  method="post" action="{{route('dashboard.menus.store')}}" enctype="multipart/form-data">

                                      

                                            <div class="row">

                                                <input type="hidden" name="id" value="{{ isset($menu) ? $menu->id : '' }}">

                                               

                                                <div class="col-md-6">

                                                    <div class="form-group">

                                                        <label class="form-label">Title(English)</label>

                                                        <input type="text" class="form-control" name="title" placeholder="Title" value="{{ old('title', isset($menu) ? $menu->title : '') }}" required>

                                                    </div>
                                                </div>
                                                 <!-- <div class="col-md-6">

                                                    <div class="form-group">

                                                        <label class="form-label">Title(Arabic)</label>

                                                        <input type="text" class="form-control" name="arab_title" placeholder="Title" value="{{ old('title', isset($menu) ? $menu->arab_title : '') }}" required>

                                                    </div>
                                                </div> -->

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="form-label">Select Parent</label>
                                                         <select class="form-control" name="parent">
                                                            <option value="">Select</option>
                                                            @if(count($parent)>0)
                                                                @foreach($parent as $value)
                                                                <option value="{{$value->id}}" {{isset($menu) && ($menu->parent == $value->id)}}>{{$value->title}}</option>
                                                                @endforeach
                                                            @endif
                                                         </select>
                                                    </div>
                                                </div>

                                                    

                                                 <div class="col-md-6">   

                                                     <div class="form-group">

                                                        <label class="form-label">url</label>

                                                        <input type="text" class="form-control" name="url" placeholder="Title" value="{{ old('url', isset($menu) ? $menu->url : '') }}" required>

                                                    </div>
                                                </div>

                                                <div class="col-md-6">   

                                                    <div class="form-group">

                                                        <label class="form-label">Position</label>

                                                        <input type="text" class="form-control" name="position" placeholder="Title" value="{{ old('position', isset($menu) ? $menu->position : '') }}" >

                                                    </div>

                                                  </div>

                                                  <div class="col-md-6">

                                                        <div class="form-group">

                                                            <label class="form-label">Icon</label>

                                                            <input type="text" class="form-control" name="icon" placeholder="icon" value="{{ old('icon', isset($menu) ? $menu->icon : '') }}" >

                                                        </div>

                                                     </div>
                                                      <div class="col-md-12">
                        <label class="form-label mt-0">Image</label>
                        <div class="dropify-wrapper" style="height: 302px;border: 1px solid #cdcdcd;">
                            <div class="dropify-message" >

                                <span class="file-icon"> <p>Drag and drop a file here or click</p>
                                </span>
                                <p class="dropify-error">Ooops, something wrong appended.</p>
                            </div>
                            <div class="dropify-loader"></div><div class="dropify-errors-container">
                                    <ul>
                                    </ul>
                            </div>
                            @if(isset($menu->image))
                                <input type="file" class="dropify" data-height="300" data-default-file="{{asset('home-menu/'.$menu->image)}}" name="menu_image" value="">
                            @else
                                <input type="file" class="dropify" data-height="300" name="menu_image" value="">
                            @endif

                            <button type="button" class="dropify-clear">Remove</button>
                            <div class="dropify-preview">
                                <span class="dropify-render">
                                </span>
                                <div class="dropify-infos">
                                    <div class="dropify-infos-inner">
                                        <p class="dropify-filename">
                                            <span class="dropify-filename-inner"></span>
                                        </p>
                                        <p class="dropify-infos-message">Drag and drop or click to replace</p>

                                    </div>
                                </div>
                            </div>

                        </div>

                    </div>


                                                    

                                                  

                                                </div>

                                                

                                            </div>

                                       

                                        @if(isset($menu->id))

                                            <button class="btn btn-success-light mt-3 " type="submit">Update</button>

                                        @else

                                            <button class="btn btn-success-light mt-3 " type="submit">Save</button>

                                        @endif

                                    </div>



                                     </form>

                                    

                                </div>              

@endsection

@section('js')

<script src="{{ URL::asset('assets/plugins/chart/Chart.bundle.js') }}"></script>

<script src="{{ URL::asset('assets/plugins/chart/utils.js') }}"></script>

<script src="{{ URL::asset('assets/plugins/wysiwyag/jquery.richtext.js') }}"></script>

<script src="{{ URL::asset('assets/plugins/wysiwyag/wysiwyag.js') }}"></script>

<script src="{{ URL::asset('assets/plugins/summernote/summernote-bs4.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/fileuploads/js/fileupload.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/fileuploads/js/file-upload.js') }}"></script>



@endsection

