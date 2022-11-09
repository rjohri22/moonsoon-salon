@extends('admin.layout')
@section('content')
    <div class="adminx-content">
        <div class="adminx-main-content">
            <div class="container-fluid">

            <div class="row">

                    <div class="com-md-12">

                    <div class="container">
                        <form class="form-inline" method="post" action="{{url('/')}}/admin/item-videos">
                            @csrf

                            <div class="form-group"  style="padding:10px;">
                            <label >Select Video Category:</label> &nbsp;
                            <select class="form-control"  name="search_video_category">
                            <option value=""  @if($search_category=="") selected @endif>All</option>
                            <option value="Upcoming"   @if($search_category=="Upcoming") selected @endif>Upcoming</option>
                                            <option value="Live"   @if($search_category=="Live") selected @endif>Live</option>
                                            <option value="Past"  @if($search_category=="Past") selected @endif>Past</option>
                            </select>
                            </div>
                            <div class="form-group" style="padding:10px;">
                            <label  >Select Item:</label>&nbsp;
                            <select class="form-control" style="width:350px;"  name="search_item" >
                            <option value=""  @if($search_item=="") selected @endif>All</option>
                            @foreach ( $items as  $item_key=>$item_name)
                                        <option value="{{$item_key}}"  @if($search_item==$item_key) selected @endif >{{ $item_name}}</option>
                                        @endforeach
                            </select>
                           

                            </div>
                           
                            <button type="submit" class="btn btn-primary">Search</button>
                        </form>
                </div>



                    </div>

            

             </div> 
                <div class="card mt-4" >
                    <div class="row">
                        <div class="col">
                            <div class="float-left">
                                <h3 class="h-p-bold-gray">Item Videos</h3>
                            </div>
                            <div class="float-right">
                                <!-- Button trigger modal -->
                                <button type="button" class="btn btn-primary" onclick="addItemVideo()">
                                    Add Item Video
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="card mb-grid">
                            <!-- Button trigger modal -->

                            <div class="table-responsive-md">
                                <table class="table table-actions table-striped table-hover mb-0">
                                    <thead>
                                        <tr>
                                            <!-- <th scope="col">
                                                <label class="custom-control custom-checkbox m-0 p-0">
                                                    <input type="checkbox" class="custom-control-input table-select-all">
                                                    <span class="custom-control-indicator"></span>
                                                </label>
                                            </th> -->
                                            <th scope="col">Item Name</th>
                                            <th scope="col">Video Category</th>
                                            <th scope="col">Item Video</th>
                                           
                                            <th scope="col">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    @if(count($itemvideos)==0)
                                    <tr><td colspan="4" class='text-center'>No Videos</td></tr>
                                    @endif

                                    @foreach ($itemvideos as $itemvideo)
                                        <tr>
                                            <td>{{$itemvideo->item_name}}</td>
                                            <td>{{$itemvideo->video_category}}</td>
                                             <td> 
                                            <a target="_blank"  href="{{asset('itemVideos/'.$itemvideo->video_file_name)}}">Video Uploaded</a>
                                            </td>

                                            <td>
                                                    <a href="javascript:void(0)" class="btn btn-sm btn-primary" onclick="editItemVideo({{$itemvideo->id}})">Edit</a>
                                                    <a href="{{url('/').'/admin/item-video-delete/'.$itemvideo->id}}" class="btn btn-sm btn-danger show-confirm">Delete</a>
                                                </td>
                                        </tr>
                                        
                                    @endforeach
                                       
                                    </tbody>
                                </table> 

                             
                                </hr>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add/Update Item Video Modal -->
    <div class="modal fade" id="itemVideoModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title h-bold-gray" id="exampleModalLabel">ITEM VIDEO : <span id="title_view"></span></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ url('admin/item-video-addupdate') }}" method="post"  enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="brand_id">Item Name <span class="required_symbol">*</span></label>
                                    <select class="form-control"  name="item_id"  id="select_item_id" required>
                                        <option value="">--SELECT--</option>
                                        @foreach ( $items as  $item_key=>$item_name)
                                        <option value="{{$item_key}}">{{ $item_name}}</option>
                                        @endforeach
                                 
                                    </select>
                                     
                                </div>
                            </div>
                            <input type='hidden' name="id"  id="edit_video_id" value="" />
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="item_category_id">Video Category <span class="required_symbol">*</span></label>
                                    <select class="form-control" name="video_category" id="video_category_id">
                                        <option value="">--SELECT--</option>
                                            <option value="Upcoming">Upcoming</option>
                                            <option value="Live">Live</option>
                                            <option value="Past">Past</option>
                                    </select> 
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="item_sub_category_id">Upload Video <span
                                            class="required_symbol">*</span></label>
                                    <input type="file"  name="upload_video"     class="form-control" />
                                    <a   class=" d-none" target="_blank"  href="" id="link_edit_video_uploaded"><span>Uploaded Video</span></a>
                                </div>
                            </div>
                          
                        </div>
    
                      
                        <button type="submit" class="btn btn-primary"  ><span  id="submitValue">Add Item Video</span></button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script>

        function addItemVideo(){

            $("#itemVideoModal").modal("show");

        }

 

        function editItemVideo(videoid){ 

            $("#itemVideoModal").modal("show");


            $.get("{{url('/')}}"+"/admin/item-video-detail/"+videoid,function(data,status){

                var result=JSON.parse(JSON.stringify(data))['item_video_detail'];
  
                $("#select_item_id").val(result['item_id']); 
                $("#video_category_id").val(result['video_category']);
                $("#link_edit_video_uploaded").removeClass('d-none');
                $("#link_edit_video_uploaded").addClass('d-block'); 
                $("#link_edit_video_uploaded").attr("href",result['video_file_name']);
                $("#edit_video_id").val(result['id']);
                
                $("#submitValue").html("Update Item Video");

            });

        }
  
    </script>
@endsection
