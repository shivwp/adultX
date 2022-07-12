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

            <li class="breadcrumb-item"><a href="{{ route('dashboard.packages.index') }}">Packages</a></li>

                @if(isset($package->id))

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

                                <form  method="post" action="{{route('dashboard.packages.store')}}" enctype="multipart/form-data">

                                    @csrf

                                    <div class="card-body">

                                        <form  method="post" action="{{route('dashboard.packages.store')}}" enctype="multipart/form-data">

                                      

                                            <div class="row">

                                                <input type="hidden" name="id" value="{{ isset($package) ? $package->id : '' }}">

                                               

                                                <div class="col-md-6">

                                                    <div class="form-group">

                                                        <label class="form-label">Name</label>

                                                        <input type="text" class="form-control" name="package_name" placeholder="Package Name" value="{{ old('package_name', isset($package) ? $package->name : '') }}" required>

                                                    </div>
                                                </div>
                                                    

                                                 <div class="col-md-6">   

                                                     <div class="form-group">

                                                        <label class="form-label">Description</label>

                                                        <input type="text" class="form-control" name="description" placeholder="Description" value="{{ old('url', isset($package) ? $package->description : '') }}" required>

                                                    </div>
                                                </div>

                                                <div class="col-md-6">   

                                                    <div class="form-group">

                                                        <label class="form-label">Amount</label>

                                                        <input type="text" class="form-control" name="amount" placeholder="Amount" value="{{ old('amount', isset($package) ? $package->amount : '') }}" required>

                                                    </div>

                                                </div>

                                                <div class="col-md-6">   

                                                    <div class="form-group">

                                                        <label class="form-label">Credit</label>

                                                        <input type="text" class="form-control" name="credit" placeholder="Credits" value="{{ old('credit', isset($package) ? $package->credit : '') }}" required>

                                                    </div>

                                                </div>

                                                {{-- <div class="col-md-12">
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
                                                        @if(isset($package->image))
                                                            <input type="file" class="dropify" data-height="300" data-default-file="{{asset('home-menu/'.$package->image)}}" name="menu_image" value="">
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

                                                </div> --}}

                                            </div>

                                            
                                            @if(isset($package->id))

                                                <button class="btn btn-success-light mt-3 " type="submit">Update</button>

                                            @else

                                                <button class="btn btn-success-light mt-3 " type="submit">Save</button>

                                            @endif
                                        </div>
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

