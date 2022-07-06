@extends('layouts.vertical-menu.master')
@section('css')
<link href="{{ URL::asset('assets/plugins/summernote/summernote-bs4.css')}}" rel="stylesheet">
<link href="{{ URL::asset('assets/plugins/wysiwyag/richtext.css')}}" rel="stylesheet">
<link href="{{ URL::asset('assets/plugins/fileuploads/css/fileupload.css')}}" rel="stylesheet" />
<link href="{{ URL::asset('assets/plugins/multipleselect/multiple-select.css')}}" rel="stylesheet" />
<link href="{{ URL::asset('assets/plugins/select2/select2.min.css')}}" rel="stylesheet" />
<style> .note-placeholder {
    display: none !important;
}
</style>
@endsection
@section('page-header')
    <!-- PAGE-HEADER -->
    <div>
        <h1 class="page-title">Feeds</h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard.blogs.index') }}">Feed</a></li>
            @if(isset($blog->id))
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
        <form  method="post" action="{{route('dashboard.blogs.store')}}" enctype="multipart/form-data">
            @csrf
            <div class="card-body">
                <div class="row">
                    <input type="hidden" name="id" value="{{ isset($blog) ? $blog->id : '' }}">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="form-label">Title</label>
                            <input type="text" class="form-control" name="title" placeholder="Title" value="{{ old('title', isset($blog) ? $blog->title : '') }}" required>
                        </div>
                    </div>
                   <!--  <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">Category</label>
                            <select class="form-control select2" name="blog_cat" required>
                            @if(count($blogcat) > 0)
                            <option value="" hidden>Select</option>
                                @foreach($blogcat as $val)
                                    <option value="{{$val->slug}}" {{isset($blog->cat_slug) && ($blog->cat_slug == $val->slug) ? 'selected' : ''}}>{{$val->title}}</option>
                                @endforeach
                            @endif
                            </select>
                        </div>
                    </div> -->
                    <!-- <div class="col-md-12">
                        <div class="form-group">
                            <label class="form-label">Tag</label>
                            <select class="form-control select2" name="tag" required>
                            @if(count($blogtag) > 0)
                                <option value="" hidden>Select</option>
                                @foreach($blogtag as $val)
                                <option value="{{$val->slug}}" {{isset($blog->tag_slug) && ($blog->tag_slug == $val->slug) ? 'selected' : ''}}>{{$val->title}}</option>
                                @endforeach
                            @endif
                            </select>
                        </div>
                    </div> -->
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="form-label">Short Description</label>
                            <textarea class="form-control" name="short_description">{{isset($blog->short_description) ? $blog->short_description : ''}}</textarea>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="form-label">Long Description</label>
                            <div id="summernote"><?php echo isset($blog) ? $blog->long_description : '' ?></div>
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
                        @if(isset($blog->image))
                            <input type="file" class="dropify" data-height="300" data-default-file="{{asset('blog-image/'.$blog->image)}}" name="image" value="">
                        @else
                            <input type="file" class="dropify" data-height="300" name="image" value="">
                        @endif

                        <button type="button" class="dropify-clear">Remove</button>
                        <div class="dropify-preview">
                            <span class="dropify-render"> </span>
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
                <div class="col-md-12">
                    <div class="form-group">
                        <label class="form-label">Gallery Image</label>
                        <input type="file" name="gallery[]" value="" multiple>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label class="form-label">Meta Title</label>
                        <input type="text" name="meta_title" value="{{isset($blog->meta_title) ? $blog->meta_title : ''}}" class="form-control">
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label class="form-label">Meta Keyword</label>
                        <textarea name="meta_keyword" class="form-control">{{isset($blog->meta_keyword) ? $blog->meta_keyword : ''}}</textarea>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label class="form-label">Meta Description</label>
                        <textarea name="meta_description" class="form-control">{{isset($blog->meta_description) ? $blog->meta_description : ''}}</textarea>
                    </div>
                </div>
            </div>

            @if(isset($tax->id))
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
<script src="{{ URL::asset('assets/js/summernote.js') }}"></script>
<script src="{{ URL::asset('assets/js/formeditor.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/fileuploads/js/fileupload.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/fileuploads/js/file-upload.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/multipleselect/multiple-select.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/multipleselect/multi-select.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/select2/select2.full.min.js') }}"></script>
<script src="https://cdn.ckeditor.com/4.16.2/standard/ckeditor.js"></script>
<script>
    CKEDITOR.replace( 'editor1' );
</script>
<script>  
$('document').ready(function() {
    $('.note-codable').attr('name', 'content');
    var pre_editor_val = $('input[name="content"]').val();
    $('textarea[name="content"]').val(pre_editor_val);
    $('.note-editable.card-block').html(pre_editor_val);
    $('button[type="submit"]').click(function(editor_val){
        if(!jQuery('.codeview').lenght){
            var editor_val = $('.note-editable.card-block').html();
            $('textarea[name="content"]').val(editor_val);
        }

    });
  });

function codegenrate() {
    var rnd = Math.floor(Math.random() * 10000);
    document.getElementById('genrate_code').value = 'COUP'+rnd;
}
</script>
@endsection



