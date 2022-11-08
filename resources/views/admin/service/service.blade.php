@extends('admin.layout')
@section('content')
    <div class="adminx-content">
        <div class="adminx-main-content">
            <div class="container-fluid">
                <div class="card">
                    <div class="row">
                        <div class="col">
                            <div class="float-left">
                                <h3 class="h-p-bold-gray">Services</h3>
                            </div>
                            <div class="float-right">
                                <!-- Button trigger modal -->
                                <button type="button" class="btn btn-primary" onclick="addService()">
                                    Add Service
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
                                            <th scope="col">Service Name</th>
                                            <th scope="col">Service Image</th>
                                            <th scope="col">Price</th>
                                            <th scope="col">Discount Amount</th>
                                            <th scope="col">Status</th>
                                            <th scope="col">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($datas as $key => $data)
                                            <tr id="pId{{ $data->id }}">
                                                <th scope="row">
                                                    <label class="custom-control custom-checkbox m-0 p-0">
                                                        <input type="checkbox"
                                                            class="custom-control-input table-select-row">
                                                        <span class="custom-control-indicator"></span>
                                                    </label>
                                                </th>
                                                <td>{{ !empty($data->branch) ? $data->branch->name : "" }}</td>
                                                <td>{{ $data->name }}</td>
                                                <td>
                                                    @if($data->medias)
                                                    @foreach($data->medias as $key => $media)
                                                    <img width="30" height="30" src="{{$media->getUrl()}}">
                                                    @endforeach
                                                    @endif                                                </td>
                                                <td>{{ $data->price }}</td>
                                                <td>{{ $data->discount_amount }}</td>
                                                <td>{{ $data->status }}</td>

                                                <td>
                                                    <a href="javascript:void(0)" class="btn btn-sm btn-primary"
                                                        onclick="editService({{ $data->id }})">Edit</a>
                                                    <a href="{{ route('admin-services.destroy', $data->id) }}"
                                                        class="btn btn-sm btn-danger show-confirm">Delete</a>
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

    <!-- Add/Update service Modal -->
    <div class="modal fade" id="serviceModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title h-bold-gray" id="exampleModalLabel">Service : <span id="title_view"></span></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ url('admin/services') }}" method="post" id="serviceForm" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="branch_id">Branch <span class="required_symbol">*</span></label>
                                    <select class="form-control" name="branch_id" id="branch_id">
                                        <option value="">--SELECT--</option>
                                        @foreach ($branches as $key => $branch)
                                            <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                        @endforeach
                                    </select>
                                    <p class="error" id="branch_id_error"></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="item_category_id">Category <span class="required_symbol">*</span></label>
                                    <select class="form-control" name="item_category_id" id="item_category_id">
                                        <option value="">--SELECT--</option>
                                        @foreach ($categories as $key => $category)
                                            <option value={{ $category->id }}>{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                    <p class="error" id="item_category_id_error"></p>
                                </div>
                            </div>
                       
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="item_sub_category_id">Sub Category <span class="required_symbol">*</span></label>
                                    <select class="form-control" name="item_sub_category_id" id="item_sub_category_id">
                                        <option value="">--SELECT--</option>
                                        @foreach ($subSategories as $key => $subCategory)
                                            <option value={{ $subCategory->id }}>{{ $subCategory->name }} (
                                                {{ $subCategory->category_type }} )</option>
                                        @endforeach
                                    </select>
                                    <p class="error" id="item_sub_category_id_error"></p>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="name">Service Name <span class="required_symbol">*</span></label>
                                    <input type="text" class="form-control" name="name" id="name">
                                    <input type="hidden" class="form-control" name="id" id="id">
                                    <p class="error" id="name_error"></p>
                                </div>
                            </div>
                      
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="price">Price <span class="required_symbol">*</span></label>
                                    <input type="number" class="form-control" name="price" id="price">
                                    <p class="error" id="price_error"></p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="service_time">Service Time(in Min) <span class="required_symbol">*</span></label>
                                    <select class="form-control" name="service_time" id="service_time">
                                        <option value="">--SELECT--</option>
                                        @for ($i=1; $i<=9; $i++) 
                                        <option value="0{{$i}}">0{{$i}}</option>
                                        @endfor

                                        @for ($i=10; $i<=59; $i++) 
                                        <option value="{{$i}}">{{$i}}</option>
                                        @endfor
                                    </select>
                                    <p class="error" id="service_time_error"></p>
                                </div>
                            </div>
                            
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="discount_amount">Discount Amount</label>
                                    <input type="number" class="form-control" name="discount_amount" id="discount_amount">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="discount_type">Discount Type</label>
                                    <select class="form-control" name="discount_type" id="discount_type">
                                        <option value="">--SELECT--</option>
                                        @foreach ($discountTypes as $key => $discountType)
                                            <option value={{ $discountType->id }}>{{ $discountType->name }}</option>
                                        @endforeach
                                    </select>
                                    {{-- <input type="text" class="form-control" name="discount_type" id="discount_type"> --}}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                <label for="name"><small>Image dimension : 500 x 500</small></label>
                                <div id="image_preview_section">
                                    <img id="user_img"
                                        height="130"
                                        width="130"
                                        style="border:solid" />
                                </div>
                                    <input class="form-control" type="file" name="file[]" id="file" multiple  accept="image/png, image/jpeg,image/jpg,image/JPG">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="name">Service Description</label>
                            <textarea class="form-control" name="description" id="description"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="how_to_use">How To Use</label>
                            <textarea class="form-control" name="how_to_use" id="how_to_use"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="benefits">Benefits</label>
                            <textarea class="form-control" name="benefits" id="benefits"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary" onclick="return formSubmit()"><span
                                id="submitValue"></span></button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script>
        $(document).on('change', '#item_category_id', async function() {
            let dropDown = '<option value="">--SELECT--</option>';
            if (this.value != "") {
                responseData = await fetchData("{{ url('admin/subcategories-dd') }}/" + this.value+"?category_type=Service");
                responseData.forEach(data => {
                    let dropDownSegment =
                        `<option value="${data.id}">${data.name}( ${data.category_type} )</option>`;
                    dropDown += dropDownSegment;
                    /* $('#item_sub_category_id').html(dropDown); */
                });
            } else {
                responseData = await fetchData("{{ url('admin/subcategories-dd') }}");
                responseData.forEach(data => {
                    let dropDownSegment =
                        `<option value="${data.id}">${data.name}( ${data.category_type} )</option>`;
                    dropDown += dropDownSegment;
                    /* $('#item_sub_category_id').html(dropDown); */
                });
            }
            $('#item_sub_category_id').html(dropDown);
        });
       
        /* async function fetchData(url) {
            try {
                let res = await fetch(url);
                return await (res.json());
            } catch (error) {
                console.log(error);
            }
        } */

        function addService() {

            $('#serviceForm').trigger("reset");
            $('#serviceModal').modal('show');
            $("#title_view").html("Add");
            $("#submitValue").html("Create");
            $("#id").val("");
        }

        function editService(id) {
            $('#serviceForm').trigger("reset");
            item_category_id_error = false;
            $("#item_category_id").removeClass('is-invalid');
            $("#item_category_id_error").html('');
            item_sub_category_id_error = false;
            $("#item_sub_category_id").removeClass('is-invalid');
            $("#item_sub_category_id_error").html('');
            name_error = false;
            $("#name").removeClass('is-invalid');
            $("#name_error").html('');
            price_error = false;
            $("#price").removeClass('is-invalid');
            $("#price_error").html('');
            $("#image_preview_section").html('');
            $("#id").val(id);
            $("#title_view").html("Edit");
            $("#submitValue").html("Submit");
            let to_append = "";
            $.ajax({
                url: "{{ url('admin/services') }}" + "/" + id,
                method: "GET",
                success: function(response) {
                    console.log(response);
                    $('#name').val(response.data.name);
                    $('#id').val(response.data.id);
                    $('#branch_id').val(response.data.branch_id);
                    $('#description').val(response.data.description);
                    $('#price').val(response.data.price);
                    $('#service_time').val(response.data.service_time);
                    $('#discount_amount').val(response.data.discount_amount);
                    $('#how_to_use').val(response.data.how_to_use);
                    $('#benefits').val(response.data.benefits);
                    $('#item_category_id').val(response.data.item_category_id);
                    $('#item_sub_category_id').val(response.data.item_sub_category_id);
                    $('#discount_type').val(response.data.discount_type);
                    $( response.data.images ).each(function( index,value ) {
                        // console.log( index + ": " + $( this ).text() );
                        to_append += `<div class="images-delete-block"  style="margin-right:5px; display:inline-block; position:relative; padding:3px;border:1px solid #c4c4c4;border-radious:3px;">
                                    <img src="`+ value + `" id="user_img" style="height:130px;width=130px">
                                </div>`;
                    });
                $("#image_preview_section").html(to_append);
                }
            });
            $('#serviceModal').modal('show');

        }
    </script>

    {{-- Add/Update service Validation --}}

    <script>
        var item_category_id_error = true;
        var item_sub_category_id_error = true;
        var price_error = true;
        var name_error = true;

       
        $(document).ready(function() {
            $(document).on("change", "#item_category_id", function(e) {
                serviceCategoryIdValidate();
            });
        });
        $(document).ready(function() {
            $(document).on("change", "#item_sub_category_id", function(e) {
                subCategoryValidate();
            });
        });
        $(document).ready(function() {
            $(document).on("change", "#price", function(e) {
                priceValidate();
            });
        });
        $(document).ready(function() {
            $(document).on("change", "#name", function(e) {
                nameValidate();
            });
        });

        

        //service Category Id Validation
        function serviceCategoryIdValidate() {
            let serviceCategoryId = $.trim($("#item_category_id").val());
            if (!validate.requiredCheck(serviceCategoryId)) {
                item_category_id_error = true;
                $("#item_category_id").addClass('is-invalid');
                return $("#item_category_id_error").html('service Category is Required');
            }
            item_category_id_error = false;
            $("#item_category_id").removeClass('is-invalid');
            $("#item_category_id_error").html('');
        }

        //service Sub Category Id Validation
        function subCategoryValidate() {
            let subCategory = $.trim($("#item_sub_category_id").val());
            if (!validate.requiredCheck(subCategory)) {
                item_sub_category_id_error = true;
                $("#item_sub_category_id").addClass('is-invalid');
                return $("#item_sub_category_id_error").html('Sub Category is Required');
            }
            item_sub_category_id_error = false;
            $("#item_sub_category_id").removeClass('is-invalid');
            $("#item_sub_category_id_error").html('');
        }

        
        //service Quantity Validation
        
        

        //service Price Validation
        function priceValidate() {
            let price = $.trim($("#price").val());
            if (!validate.requiredCheck(price)) {
                price_error = true;
                $("#price").addClass('is-invalid');
                return $("#price_error").html('service Price can not be empty');
            }
            price_error = false;
            $("#price").removeClass('is-invalid');
            $("#price_error").html('');
        }

        //service Name Validation
        function nameValidate() {
            let name = $.trim($("#name").val());
            if (!validate.requiredCheck(name)) {
                name_error = true;
                $("#name").addClass('is-invalid');
                return $("#name_error").html('service Name can not be empty');
            }
            name_error = false;
            $("#name").removeClass('is-invalid');
            $("#name_error").html('');
        }

        function formSubmit() {
            serviceCategoryIdValidate();
            subCategoryValidate();
            priceValidate();
            nameValidate();

            if (
                item_category_id_error ||
                item_sub_category_id_error ||
                price_error ||
                name_error
            ) {
                // console.log(item_category_id_error);
                // console.log(item_sub_category_id_error);
                // console.log(name_error);
                alert("Please Fill Carefully", 'error');
                return false;
            } else {
                // console.log(item_category_id_error);
                // console.log(item_sub_category_id_error);
                // console.log(name_error);
                return true;
            }
        }
    </script>
@endsection
