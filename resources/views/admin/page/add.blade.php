@extends('layouts.vertical-menu.master')

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css" type="text/css"/>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

@section('css')



<link href="{{ URL::asset('assets/plugins/summernote/summernote-bs4.css')}}" rel="stylesheet">



<link href="{{ URL::asset('assets/plugins/wysiwyag/richtext.css')}}" rel="stylesheet">



<style>
    .note-placeholder {
        display: none !important;
    }
    .fieldGroup {
        border: 1px solid #ebe6e6;
        border-radius: 9px;
        margin-top: 20px;
        margin-bottom: 20px;
        padding-bottom: 10px;
    }
</style>



@endsection



@section('page-header')



                        <!-- PAGE-HEADER -->



                            <div>



                                <h1 class="page-title">{{$title}}</h1>



                                <ol class="breadcrumb">



                                    <li class="breadcrumb-item"><a href="{{ route('dashboard.pages.index') }}">Page</a></li>

                                    @if(isset($page->id))

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



                                <form  method="post" action="{{route('dashboard.pages.store')}}" enctype="multipart/form-data">



                                    @csrf



                                    <div class="card-body">



                                        <form  method="post" action="{{route('dashboard.pages.store')}}" enctype="multipart/form-data">







                                            <div class="row">



                                                <input type="hidden" name="id" value="{{ isset($page) ? $page->id : '' }}">



                                                <input type="hidden" name="content" value="{{ isset($page) ? $page->content : '' }}">



                                                <div class="col-md-12">

                                                    <div class="form-group">

                                                        <label class="form-label">Title</label>

                                                        <input type="text" class="form-control" name="title" placeholder="Title" value="{{ old('title', isset($page) ? $page->title : '') }}" required>

                                                    </div>

                                                    {{--<div class="form-group">

                                                        <label class="form-label">Title(Arabic)</label>

                                                        <input type="text" class="form-control" name="arab_title" placeholder="Title" value="{{ old('title', isset($page) ? $page->arab_title : '') }}" required>

                                                    </div>--}}

                                                    <label class="form-label">Content</label>

                                                    <div id="summernote"></div>

                                                    </div>

                                                </div>

                                                {{-- <div class="col-12">

                                                    <label class="form-label">Content(Arabic)</label>

                                                    <textarea id="editor1" name="arab_content" value=""><?php echo isset($page) ? $page->arab_content : '' ?></textarea>

                                                </div> --}}



                                            <div class="col-12">

                                                <label class="form-label">Meta title</label>

                                                <input type="text" class="form-control" name="page_title" placeholder="Title" value="{{($data['Pagemeta_title'])??''}}">

                                            </div>

                                            <div class="col-12">

                                                <label class="form-label">Meta keywords</label>

                                                <textarea type="text" class="form-control" name="page_keywords" row="20" placeholder="keywords" value="">{{($data['Pagemeta_keywords'])??''}}</textarea>

                                            </div>

                                            <div class="col-12">

                                                <label class="form-label">Meta Deatils</label>

                                                <textarea type="text" class="form-control" name="page_details" row="20" placeholder="Title" value="">{{($data['Pagemeta_details'])??''}}</textarea>

                                            </div>
                                            <div class="col-12 mt-5 d-flex">

                                                    <label class="form-label">Page Slider</label>

                                                <div class=" mr-0 ml-auto">
                                                    <a href="javascript:void(0)" class="btn btn-success-light ml-5 mr-0  addMore add_btnnn"><span class="glyphicon glyphicon glyphicon-plus" aria-hidden="true"></span> Add</a>
                                                </div>
                                            </div>
                                         <div class="fieldGroup">
                                            <div class="col-md-12 pr-1 pl-1">

                                                <div class="form-group">

                                                    <label class="form-label">Title</label>

                                                    <input type="text" class="form-control" name="sub[0][slider_title]" placeholder="Title" value="">

                                                </div>


                                                </div>
                                                <div class="form-group pr-1 pl-1">
                                                    <label class="form-label">Description</label>
                                                    <input type="text" class="form-control" name="sub[0][slider_desc]" row="20" placeholder="Description" >

                                                </div>
                                                <div class="form-group pr-1 pl-1">

                                                    <label class="form-label">Image</label>
                                                    <input type="file" class="form-control" name="sub[0][slider_image][]" value="">

                                                </div>

                                         </div>
                                         <div class="fieldGroupCopy" style="display: none;">
                                            <div class="col-md-12 pr-1 pl-1">

                                                <div class="form-group">

                                                    <label class="form-label">Title</label>

                                                    <input type="text" class="form-control" name="sub[1][slider_title]" placeholder="Title" value="">

                                                </div>


                                                </div>
                                                <div class="form-group pr-1 pl-1">
                                                    <label class="form-label">Description</label>
                                                    <input type="text" class="form-control" name="sub[1][slider_desc]" row="20" placeholder="Description" >

                                                </div>
                                                <div class="form-group pr-1 pl-1">

                                                    <label class="form-label">Image</label>
                                                    <input type="file" class="form-control" name="sub[1][slider_image][]" value="">

                                                </div>
                                          <div class="row">
                                            <div class="col-12">

                                                <div class="col-2">
                                                    <div class="mr-0 ml-auto">
                                                        <a href="javascript:void(0)" class="btn btn-success-light ml-0 mt-2 mr-0  remove add_btnnn"><span class="glyphicon glyphicon glyphicon-remove" aria-hidden="true"></span>Remove</a>
                                                    </div>
                                                </div>
                                              </div>
                                          </div>
                                         </div>
                                             @if(isset($page->id))

                                             <button type ="submit" class="btn btn-success-light mt-3 ">Update</button>

                                        @else

                                            <button type ="submit" class="btn btn-success-light mt-3 pr-0 mr-0 ">Save</button>

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



<script src="{{ URL::asset('assets/js/summernote.js') }}"></script>



<script src="{{ URL::asset('assets/js/formeditor.js') }}"></script>

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
  var maxGroup = 100;
  $(".addMore").click(function(){
    if($('body').find('.fieldGroup').length < maxGroup){
        var fieldHTML = '<div class="fieldGroup">'+$(".fieldGroupCopy").html()+'</div>';
        $('body').find('.fieldGroup:last').after(fieldHTML);
    }else{
        alert('Maximum '+maxGroup+' groups are allowed.');
    }
});
$("body").on("click",".remove",function(){
    $(this).parents(".fieldGroup").remove();
});

</script>



@endsection



