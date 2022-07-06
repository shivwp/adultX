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
</style>
@endsection
@section('page-header')
    <!-- PAGE-HEADER -->
        <div>
            <h1 class="page-title">{{$title}}</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard.model-fetishes.index') }}">Model Fetishes</a></li>
                 @if(isset($model_fet->id))
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
        <form  method="post" action="{{route('dashboard.model-fetishes.store')}}" enctype="multipart/form-data">
            @csrf
            <div class="card-body">
                <input type="hidden" name="id" value="{{ old('id', isset($model_fet->id) ? $model_fet->id : '') }}">
                
                
                    <div class="row">
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Title</label>
                                <input type="text" class="form-control" name="title" placeholder="Title" value="{{ old('title', isset($model_fet->title) ? $model_fet->title : '') }}" required>
                            </div>
                        </div>
                       <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-control" required>
                                    <option value="">Select</option>
                                    <option value="active" {{isset($model_fet->status) && $model_fet->status == "active" ? "selected" : ''}}>Active</option>
                                    <option value="pending" {{isset($model_fet->status) && $model_fet->status == "pending" ? "selected" : ''}}>Pending</option>
                                </select>
                            </div>
                        </div>
                    @if(isset($model_fet->id))
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
</script>
@endsection



