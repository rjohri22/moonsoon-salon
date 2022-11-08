@extends('admin.layout')
@section('content')
<div class="adminx-content">
    <div class="adminx-main-content">
        <div class="container-fluid">
            <div class="card">
                <div class="row">
                    <div class="col">
                        <div class="float-left">
                            <h3 class="h-p-bold-gray">Branches</h3>
                        </div>
                        <div class="float-right">
                            <!-- Button trigger modal -->
                            <button type="button" class="btn btn-primary" onclick="addBranch()">
                                Add Branch
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
                                        <th scope="col">
                                            <label class="custom-control custom-checkbox m-0 p-0">
                                                <input type="checkbox" class="custom-control-input table-select-all">
                                                <span class="custom-control-indicator"></span>
                                            </label>
                                        </th>   
                                        <th scope="col">Branch Name</th>
                                        <th scope="col">Branch Logo</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($datas as $key => $data)
                                    <tr id="pId{{ $data->id }}">
                                        <th scope="row">
                                            <label class="custom-control custom-checkbox m-0 p-0">
                                                <input type="checkbox" class="custom-control-input table-select-row">
                                                <span class="custom-control-indicator"></span>
                                            </label>
                                        </th>
                                        <td>{{ $data->name }}</td>
                                        <td>
                                            @if (!empty($data->media))
                                            <img src="{{ $data->media->getUrl() }}" width="30" height="30">
                                            @else
                                            @endif
                                        </td>
                                        <td>{{ $data->status }}</td>

                                        <td>
                                            <a href="javascript:void(0)" class="btn btn-sm btn-primary" onclick="editBranch({{ $data->id }})">Edit</a>
                                            <a href="{{ route('admin-branch.destroy', $data->id) }}" class="btn btn-sm btn-danger show-confirm">Delete</a>
                                        </td>
                                    <tr>
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

<!-- Add Branch Modal -->
<div class="modal fade" id="branchModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title h-bold-gray" id="exampleModalLabel">Branch : <span id="title_view"></span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ url('admin/branches') }}" method="post" id="BranchForm" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="name">Branch Name <span class="required_symbol">*</span></label>
                        <input type="text" class="form-control" name="name" id="name">
                        <input type="hidden" class="form-control" name="id" id="id">
                        <p class="error" id="name_error"></p>
                    </div>
                    <div class="form-group">
                        <label for="name">City <span class="required_symbol">*</span></label>
                        <input type="text" class="form-control" name="city" id="city">
                        <p class="error" id="city_error"></p>
                    </div>
                    <div class="form-group">
                        <label for="name">State<span class="required_symbol">*</span></label>
                        <input type="text" class="form-control" name="state" id="state">
                        <p class="error" id="state_error"></p>
                    </div>
                    <div class="form-group">
                        <label for="name">Pincode <span class="required_symbol">*</span></label>
                        <input type="text" class="form-control" name="pincode" id="pincode">
                        <p class="error" id="pincode_error"></p>
                    </div>
                    <div class="form-group">
                        <label for="name">Address </label>
                        <textarea name="address" class="form-control" id="address" cols="30" rows="5"></textarea>
                        <p class="error" id="address_error"></p>
                    </div>
                    <div class="form-group">
                        <label for="name">Latitude <span class="required_symbol">*</span></label>
                        <input type="number" class="form-control" name="lat" id="lat">
                        <p class="error" id="lat_error"></p>
                    </div>
                    <div class="form-group">
                        <label for="name">Longitude <span class="required_symbol">*</span></label>
                        <input type="number" class="form-control" name="lng" id="lng">
                        <p class="error" id="lng_error"></p>
                    </div>
                    <div class="form-group">
                        <label for="name">Branch Description</label>
                        <input type="text" class="form-control" name="description" id="description">
                    </div>
                    <div class="form-group" id="map">
                    </div>
                    <div class="form-group">
                        <label for="name"><small>Image dimension : 500 x 500</small></label>
                        <div id="image_preview_section">
                            <img id="user_img"
                                 height="130"
                                 width="130"
                                 style="border:solid" />
                        </div>
                        <input type="file" class="form-control" name="file" id="file" accept="image/*" onchange="validateimg(this)">
                    </div>
                    <button type="submit" class="btn btn-primary" onclick="return formSubmit()"><span id="submitValue"></span></button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@section('js')
{{-- Add & Update Form --}}
<script>

    function addBranch() {
        $('#branchForm').trigger("reset");
        $('#branchModal').modal('show');
        $("#submitValue").html("Create");
        $("#image_preview_section").html(`<img id="user_img"
                                 height="130"
                                 width="130"
                                 style="border:solid" />`);

        $("#title_view").html("Add");
        $("#id").val("");
    }

    function editBranch(id) {
        $('#branchForm').trigger("reset");
        name_error = false;
        $("#name").removeClass('is-invalid');
        $("#name_error").html('');
        $("#id").val(id);
        $("#submitValue").html("Submit");
        $("#title_view").html("Edit");
        var to_append="";
        $.ajax({
            url: "{{ url('admin/branches') }}" + "/" + id,
            method: "GET",
            success: function(response) {
                $('#name').val(response.data.name);
                $('#id').val(response.data.id);
                $('#city').val(response.data.city);
                $('#state').val(response.data.state);
                $('#pincode').val(response.data.pincode);
                $('#lat').val(response.data.lat);
                $('#lng').val(response.data.lng);
                $('#address').html(response.data.address);
                $('#description').val(response.data.description);
                to_append += `<div class="images-delete-block"  style="margin-right:5px; display:inline-block; position:relative; padding:3px;border:1px solid #c4c4c4;border-radious:3px;">
                            	<img src="`+ response.data.image + `" id="user_img" style="height:130px;width=130px">
                        	</div>`;
                $("#image_preview_section").html(to_append);
            }
        });
        $('#branchModal').modal('show');

    }
    function viewProfile(id) {
    	to_append = "";
    	$("#view_profile_image_section").html("");

    	$.ajax({
        	url: "{{url('profile/fetch')}}" + "/" + id,
        	method: "GET",
        	contentType: 'application/json',
        	dataType: "json",
        	success: function (data) {

            	console.log(data);
            	to_append += `<div class="images-delete-block" style="margin-right:5px; display:inline-block; position:relative; padding:3px;border:1px solid #c4c4c4;border-radious:3px;">
                            	<img src="{{url('public/images/profile/`+ data.profile_pic + `')}}" style="height:90px; min-height:90px; min-width:80px;">
                        	</div>`;
            	$("#name").html(data.name);
            	$("#username").html(data.username);
            	$("#email").html(data.email);
            	var val = data.status;
            	if (val == 1) {
                	$('#view_status').html(`<p class="mb-0">
                                	<span class="badge badge-success">Active</span>
                            	</p>`);
            	} else {
                	$('#view_status').html(`<p class="mb-0">
                                	<span class="badge badge-danger">Inactive</span>
                            	</p>`);
            	}
        	}
    	});
    	$('#guardian_view').modal('show');

	}


  
</script>

{{-- Add/Update Branch Validation --}}

<script>
    var name_error = true;
    var city_error = true;
    var state_error = true;
    var pincode_error = true;
    var lat_error = true;
    var lng_error = true;

    $(document).ready(function() {
        $(document).on("change", "#name", function(e) {
            nameValidate();
        });
        $(document).on("change", "#city", function(e) {
            cityValidate();
        });
        $(document).on("change", "#state", function(e) {
            stateValidate();
        });
        $(document).on("change", "#pincode", function(e) {
            pincodeValidate();
        });
        $(document).on("change", "#lat", function(e) {
            latValidate();
        });
        $(document).on("change", "#lng", function(e) {
            lngValidate();
        });
    });

    //Branch Name Validation
    function nameValidate() {
        let name = $.trim($("#name").val());
        if (!validate.requiredCheck(name)) {
            name_error = true;
            $("#name").addClass('is-invalid');
            return $("#name_error").html('Branch Name can not be empty');
        }
        name_error = false;
        $("#name").removeClass('is-invalid');
        $("#name_error").html('');
    }
    function cityValidate() {
        let city = $.trim($("#city").val());
        if (!validate.requiredCheck(city)) {
            city_error = true;
            $("#city").addClass('is-invalid');
            return $("#city_error").html('Branch city can not be empty');
        }
        city_error = false;
        $("#city").removeClass('is-invalid');
        $("#city_error").html('');
    }
    function stateValidate() {
        let state = $.trim($("#state").val());
        if (!validate.requiredCheck(state)) {
            state_error = true;
            $("#state").addClass('is-invalid');
            return $("#state_error").html('Branch state can not be empty');
        }
        state_error = false;
        $("#state").removeClass('is-invalid');
        $("#state_error").html('');
    }
    function pincodeValidate() {
        let pincode = $.trim($("#pincode").val());
        if (!validate.requiredCheck(pincode)) {
            pincode_error = true;
            $("#pincode").addClass('is-invalid');
            return $("#pincode_error").html('Branch pincode can not be empty');
        }
        pincode_error = false;
        $("#pincode").removeClass('is-invalid');
        $("#pincode_error").html('');
    }
    function latValidate() {
        let lat = $.trim($("#lat").val());
        if (!validate.requiredCheck(lat)) {
            lat_error = true;
            $("#lat").addClass('is-invalid');
            return $("#lat_error").html('Branch Latitude  can not be empty');
        }
        lat_error = false;
        $("#lat").removeClass('is-invalid');
        $("#lat_error").html('');
    }
    function lngValidate() {
        let lng = $.trim($("#lng").val());
        if (!validate.requiredCheck(lng)) {
            lng_error = true;
            $("#lng").addClass('is-invalid');
            return $("#lng_error").html('Branch Longitude  can not be empty');
        }
        lng_error = false;
        $("#lng").removeClass('is-invalid');
        $("#lng_error").html('');
    }

    function formSubmit() {
        console.log("abc");
        nameValidate();
        cityValidate();
        stateValidate();
        pincodeValidate();
        latValidate();
        lngValidate();

        if (name_error||city_error||state_error||pincode_error||lat_error||lng_error) {
            alert("Please Fill Carefully", 'error');
            return false;
        } else {
            return true;
        }
    }
</script>
<script
      src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDu_ZudfXAaFpSQuruLU5cZ37_u5vwfBRQ&callback=initMap&v=weekly"
      async
    ></script>
<script>
    function initMap() {
  const myLatlng = { lat: -25.363, lng: 131.044 };
  const map = new google.maps.Map(document.getElementById("map"), {
    zoom: 4,
    center: myLatlng,
  });
  // Create the initial InfoWindow.
  let infoWindow = new google.maps.InfoWindow({
    content: "Click the map to get Lat/Lng!",
    position: myLatlng,
  });

  infoWindow.open(map);
  // Configure the click listener.
  map.addListener("click", (mapsMouseEvent) => {
    // Close the current InfoWindow.
    infoWindow.close();
    // Create a new InfoWindow.
    infoWindow = new google.maps.InfoWindow({
      position: mapsMouseEvent.latLng,
    });
    infoWindow.setContent(
      JSON.stringify(mapsMouseEvent.latLng.toJSON(), null, 2)
    );
    infoWindow.open(map);
  });
}
</script>
@endsection