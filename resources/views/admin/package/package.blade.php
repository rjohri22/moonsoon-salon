@extends('admin.layout')
@section('content')
<div class="adminx-content">
    <div class="adminx-main-content">
        <div class="container-fluid">
            <div class="card">
                <div class="row">
                    <div class="col">
                        <div class="float-left">
                            <h3 class="h-p-bold-gray">Packages</h3>
                        </div>
                        <div class="float-right">
                            <!-- Button trigger modal -->
                            <button type="button" class="btn btn-primary" onclick="addpackage()">
                                Add Package
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
                                        <th scope="col">Package Name</th>
                                        <th scope="col">Package Image</th>
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
                                                <input type="checkbox" class="custom-control-input table-select-row">
                                                <span class="custom-control-indicator"></span>
                                            </label>
                                        </th>
                                        <td>{{ $data->name }}</td>
                                        <td>
                                            @if($data->media)
                                            <img width="30" height="30" src="{{$data->media->getUrl()}}">
                                            @endif
                                        </td>
                                        <td>{{ $data->price }}</td>
                                        <td>{{ $data->discount }}</td>
                                        <td>{{ $data->status }}</td>

                                        <td>
                                            <a href="javascript:void(0)" class="btn btn-sm btn-primary" onclick="editpackage({{ $data->id }})">Edit</a>
                                            <a href="{{ route('admin-packages.destroy', $data->id) }}" class="btn btn-sm btn-danger show-confirm">Delete</a>
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

<!-- Add/Update package Modal -->
<div class="modal fade" id="packageModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content modal-lg">
            <div class="modal-header">
                <h5 class="modal-title h-bold-gray" id="exampleModalLabel">Package : <span id="title_view"></span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ url('admin/packages') }}" method="post" id="packageForm" enctype="multipart/form-data">
                    @csrf

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">Package Name <span class="required_symbol">*</span></label>
                                <input type="text" class="form-control" name="name" id="name">
                                <input type="hidden" class="form-control" name="id" id="id">
                                <p class="error" id="name_error"></p>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="price">Price <span class="required_symbol">*</span></label>
                                <input type="number" class="form-control" name="price" id="price">
                                <p class="error" id="price_error"></p>
                            </div>
                        </div>


                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="discount">Discount Amount</label>
                                <input type="number" class="form-control" name="discount" id="discount">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="discount_type">Discount Type</label>
                                <select class="form-control" name="discount_type" id="discount_type">
                                    <option value="">--SELECT--</option>
                                    @foreach ($discountTypes as $key => $discountType)
                                    <option value="{{ $discountType->id }}">{{ $discountType->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <!-- <label for="name"><small>Image dimension : 300 x 450</small></label>
                                    <input class="form-control" type="file" name="file" id="file"> -->
                                <label for="name"><small>Image dimension : 500 x 500</small></label>
                                <!-- <div id="image_preview_section">
                                        <img id="user_img" height="130"  width="130" style="border:solid" />
                                    </div> -->
                                <input type="file" class="form-control" name="file" id="file" accept="image/*" onchange="validateimg(this)">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="packages_type">Package Type</label>
                                <select class="form-control" name="packages_type" id="packages_type">
                                    <option value="service">Service</option>
                                    <option value="item">Item</option>
                                </select>
                            </div>
                        </div>

                    </div>
                    <div class="form-group">
                        <label for="name">Package Description</label>
                        <textarea class="form-control" name="description" id="description"></textarea>
                    </div>
                    <div id="add_route_div"></div>
                    <table style="margin-left: auto; margin-right: auto; width: 100%;">
                        <tbody class="col-12" id="toAppend_Record">
                        </tbody>
                    </table>
                    <a href="javascript:void(0)" id="add_route" class="float-left" title="Add Route">
                        <i style="width:40px; height:40px;" data-feather="plus-circle"></i>
                    </a>
                    <button type="submit" class="btn btn-primary" onclick="return formSubmit()"><span id="submitValue"></span></button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@section('js')
<script>
    var rowIdx = 1;
    $(document).on('change', '#item_category_id', async function() {
        let dropDown = '<option value="">--SELECT--</option>';
        if (this.value != "") {
            responseData = await fetchData("{{ url('admin/subcategories-dd') }}/" + this
                .value);
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

    function addpackage() {

        $('#packageForm').trigger("reset");
        $('#packageModal').modal('show');
        $("#title_view").html("Add");
        $("#submitValue").html("Create");
        $("#id").val("");
        addChild();
    }

    function editpackage(id) {
        $('#packageForm').trigger("reset");
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
        $("#id").val(id);
        $("#title_view").html("Edit");
        $("#submitValue").html("Submit");
        $.ajax({
            url: "{{ url('admin/packages') }}" + "/" + id,
            method: "GET",
            success: function(response) {
                console.log(response);
                $('#name').val(response.data.name);
                $('#id').val(response.data.id);
                $('#description').val(response.data.description);
                $('#price').val(response.data.price);
                $('#discount').val(response.data.discount);
                $('#benefits').val(response.data.benefits);
                $('#discount_type').val(response.data.discount_type);
                $('#packages_type').val(response.data.packages_type);
                let packagesType = response.data.packages_type;

                var toAppend = "";
                $.each(response.data.package_detail, function(index, value) {
                    console.log("value", value);
                    rowIdx = index + 1;
                    toAppend += `<tr>
                        <td>
                            <div class="row">
                                <div class="h4 font-weight-normal route-card">
                                    <div class="float-left route-number">` + rowIdx + `</div>
                                    <div class="float-right">
                                        <a href="javascript:void"><i class="fa fa-times-circle-o hover1 remove" title="remove" aria-hidden="true"></i></a>
                                    </div>
                                </div>
                            </div>
                            <div class="row pc-row mob-row">
                                <div class="col-lg-6 col-sm-12 item_section" style="margin-top:5px;">
                                    <div class="form-group">
                                        <label for="table_id">Items</label>
                                        <input type="text" name="package_id[]" value="` + value.id + `" id="package_id">
                                        <select class="form-control" name="item_table_id[]" id="item_section">
                                            <option value="">--SELECT--</option>
                                            @foreach ($items as $key => $item)
                                                <option @if(` + value.table_id + `)  {{"selected"}} @endif value="{{ $item->id }}" > {{ $item->name }} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-sm-12 mob-row services_section">
                                    <div class="form-group">
                                        <label for="table_id">Services</label>
                                        <select class="form-control" name="service_table_id[]" id="services_section">
                                            
                                            <option value="">--SELECT--</option>
                                            @foreach ($services as $key => $service)
                                                <option @if("{{$service->id}}" ==` + value.table_id + `) {{"selected"}} @endif value={{ $service->id }}>{{ $service->name }} {{$service->id}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-sm-12 mob-row">
                                    <div class="form-group">
                                        <label for="status">Status</label>
                                        <select class="form-control" name="status[]" id="status">
                                            <option @if("active" ==` + value.status + `) {{"selected"}} @endif value="active">Active</option>
                                            <option @if("inactive" ==` + value.status + `) {{"selected"}} @endif value="inactive">Inactive</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>`;
                });
                $('#toAppend_Record').html(toAppend);

                if (packagesType == "service") {
                    $(".item_section").css('display', "none");
                    $(".services_section").css('display', "");
                } else {
                    $(".item_section").css('display', "");
                    $(".services_section").css('display', "none");
                }

            }
        });
        $('#packageModal').modal('show');

    }
</script>

{{-- Add/Update package Validation --}}

<script>
    var item_category_id_error = true;
    var item_sub_category_id_error = true;
    var packages_type_error = true;
    var price_error = true;
    var name_error = true;

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
    $(document).ready(function() {
        $(document).on("change", "#packages_type", function(e) {
            packagesTypeValidate();
        });
    });



    //package Category Id Validation
    function packageCategoryIdValidate() {
        let packageCategoryId = $.trim($("#item_category_id").val());
        if (!validate.requiredCheck(packageCategoryId)) {
            item_category_id_error = true;
            $("#item_category_id").addClass('is-invalid');
            return $("#item_category_id_error").html('package Category is Required');
        }
        item_category_id_error = false;
        $("#item_category_id").removeClass('is-invalid');
        $("#item_category_id_error").html('');
    }

    //package Sub Category Id Validation
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


    //package Quantity Validation



    //package Price Validation
    function priceValidate() {
        let price = $.trim($("#price").val());
        if (!validate.requiredCheck(price)) {
            price_error = true;
            $("#price").addClass('is-invalid');
            return $("#price_error").html('package Price can not be empty');
        }
        price_error = false;
        $("#price").removeClass('is-invalid');
        $("#price_error").html('');
    }

    //package Name Validation
    function nameValidate() {
        let name = $.trim($("#name").val());
        if (!validate.requiredCheck(name)) {
            name_error = true;
            $("#name").addClass('is-invalid');
            return $("#name_error").html('package Name can not be empty');
        }
        name_error = false;
        $("#name").removeClass('is-invalid');
        $("#name_error").html('');
    }

    function packagesTypeValidate() {
        let packagesType = $.trim($("#packages_type").val());
        if (!validate.requiredCheck(packagesType)) {
            packages_type_error = true;
            $("#packages_type").addClass('is-invalid');
            return $("#packages_type_error").html('Packages_type can not be empty');
        }
        if (packagesType == "service") {
            $(".item_section").css('display', "none");
            $(".services_section").css('display', "");
        } else {
            $(".item_section").css('display', "");
            $(".services_section").css('display', "none");
        }
        packages_type_error = false;
        $("#packages_type").removeClass('is-invalid');
        $("#packages_type_error").html('');
    }

    function formSubmit() {

        priceValidate();
        nameValidate();
        packagesTypeValidate();

        if (
            price_error ||
            name_error ||
            packages_type_error
        ) {
            alert("Please Fill Carefully", 'error');
            return false;
        } else {
            return true;
        }
    }
</script>
<script>
    function addChild() {
        let packagesType = $("#packages_type").val();
        $('#toAppend_Record').append(`
                    <tr>
                        <td>
                            <div class="row">
                                <div class="h4 font-weight-normal route-card">
                                    <div class="float-left route-number">` + rowIdx + `</div>
                                    <div class="float-right">
                                        <a href="javascript:void"><i class="fa fa-times-circle-o hover1 remove" title="remove" aria-hidden="true"></i></a>
                                    </div>
                                </div>
                            </div>
                            <div class="row pc-row mob-row">
                                <div class="col-lg-6 col-sm-12 item_section" style="margin-top:5px;">
                                    <div class="form-group">
                                        <label for="table_id">Items</label>
                                        <select class="form-control" name="item_table_id[]" id="item_section">
                                            <option value="">--SELECT--</option>
                                            @foreach ($items as $key => $item)
                                                <option value={{ $item->id }}>{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-sm-12 mob-row services_section">
                                    <div class="form-group">
                                        <label for="table_id">Services</label>
                                        <select class="form-control" name="service_table_id[]" id="services_section">
                                            
                                            <option value="">--SELECT--</option>
                                            @foreach ($services as $key => $service)
                                                <option value={{ $service->id }}>{{ $service->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-sm-12 mob-row">
                                    <div class="form-group">
                                        <label for="status">Status</label>
                                        <select class="form-control" name="status[]" id="status">
                                            <option value="active">Active</option>
                                            <option value="inactive">Inactive</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                `);
        if (packagesType == "service") {
            $(".item_section").css('display', "none");
            $(".services_section").css('display', "");
        } else {
            $(".item_section").css('display', "");
            $(".services_section").css('display', "none");
        }
    }
    $(document).ready(function() {
        $('#add_route').on('click', function() {
            // Adding a row inside the tbody.
            rowIdx = Number(rowIdx) + 1;
            console.log(rowIdx);
            addChild();
        });
        // jQuery button click event to add a row.
        $('#toAppend_Record').on('click', '.remove', function() {
            // Getting all the row next to the
            // row containing the clicked button
            var child = $(this).closest('tr').nextAll();
            // Iterating across all the row
            // obtained to change the index
            child.each(function() {
                // Getting <tr> id.
                var id = $(this).attr('id');
                // Getting the <p> inside the .row-index class.
                var idx = $(this).children('.row-index').children('p');
                // Gets the row number from <tr> id.
                var dig = parseInt(id.substring(1));
                // Modifying row index.
                idx.html(`Row ${dig - 1}`);
                // Modifying row id.
                $(this).attr('id', `R${dig - 1}`);
            });
            // Removing the current row.
            $(this).closest('tr').remove();

            // Decreasing the total number of row by 1.
            rowIdx--;
        });
    });
</script>


@endsection