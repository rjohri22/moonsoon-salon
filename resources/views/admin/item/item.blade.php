@extends('admin.layout')
@section('content')
    <div class="adminx-content">
        <div class="adminx-main-content">
            <div class="container-fluid">
                <div class="card">
                    <div class="row">
                        <div class="col">
                            <div class="float-left">
                                <h3 class="h-p-bold-gray">Items</h3>
                            </div>
                            <div class="float-right">
                                <!-- Button trigger modal -->
                                <button type="button" class="btn btn-primary" onclick="addItem()">
                                    Add Item
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
                                            <th scope="col">Item Name</th>
                                            <th scope="col">Item Image</th>
                                            <th scope="col">Quantity</th>
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
                                                <td>{{ $data->name }}</td>
                                                <td>
                                                    @if($data->medias)
                                                    @foreach($data->medias as $key => $media)
                                                    <img width="30" height="30" src="{{$media->getUrl()}}">
                                                    @endforeach
                                                    @endif                                                </td>
                                                <td>{{ $data->qty }}</td>
                                                <td>{{ $data->price }}</td>
                                                <td>{{ $data->discount_amount }}</td>
                                                <td>{{ $data->status }}</td>

                                                <td>
                                                    <a href="javascript:void(0)" class="btn btn-sm btn-primary"
                                                        onclick="editItem({{ $data->id }})">Edit</a>
                                                    <a href="{{ route('admin-items.destroy', $data->id) }}"
                                                        class="btn btn-sm btn-danger show-confirm">Delete</a>
                                                </td>
                                            <tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                {{$datas->links()}}
                                </hr>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add/Update Item Modal -->
    <div class="modal fade" id="itemModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title h-bold-gray" id="exampleModalLabel">ITEM : <span id="title_view"></span></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ url('admin/items') }}" method="post" id="itemForm" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="brand_id">Brand <span class="required_symbol">*</span></label>
                                    <input type="hidden" class="form-control" name="id" id="id">
                                    <select class="form-control" name="brand_id" id="brand_id">
                                        <option value="">--SELECT--</option>
                                        @foreach ($brands as $key => $brand)
                                            <option value={{ $brand->id }}>{{ $brand->name }}</option>
                                        @endforeach
                                    </select>
                                    <p class="error" id="brand_id_error"></p>
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
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="item_sub_category_id">Sub-Category <span
                                            class="required_symbol">*</span></label>
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
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="product_category_id">Product Category <span
                                            class="required_symbol">*</span></label>
                                    <select class="form-control" name="product_category_id" id="product_category_id">
                                        <option value="">--SELECT--</option>
                                        @foreach ($productCategories as $key => $productCategory)
                                            <option value={{ $productCategory->id }}>{{ $productCategory->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <p class="error" id="product_category_id_error"></p>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Item Name <span class="required_symbol">*</span></label>
                                    <input type="text" class="form-control" name="name" id="name">
                                    <p class="error" id="name_error"></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="qty">Quantity <span class="required_symbol">*</span></label>
                                    <input type="text" class="form-control" name="qty" id="qty">
                                    <p class="error" id="qty_error"></p>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="price">Price <span class="required_symbol">*</span></label>
                                    <input type="number" class="form-control" name="price" id="price">
                                    <p class="error" id="price_error"></p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="unit_value">Weight</label>
                                    <input type="number" class="form-control" name="unit_value" id="unit_value">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="unit_id">Unit Type</label>
                                    <select class="form-control" name="unit_id" id="unit_id">
                                        <option value="">--SELECT--</option>
                                        @foreach ($units as $key => $unit_id)
                                            <option value={{ $unit_id->id }}>{{ $unit_id->name }}</option>
                                        @endforeach
                                    </select>
                                    {{-- <input type="text" class="form-control" name="unit_id" id="unit_id"> --}}
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
                                    <input class="form-control" type="file" name="file[]" id="file" multiple>
                                </div>
                            </div>
                        </div>


                        <div class="form-group">
                            <label for="name">Item Description</label>
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
                responseData = await fetchData("{{ url('admin/subcategories-dd') }}/" + this
                    .value+"?category_type=Product");
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
        $(document).on('change', '#item_sub_category_id', async function() {
            let dropDown = '<option value="">--SELECT--</option>';
            if (this.value != "") {
                responseData = await fetchData("{{ url('admin/productcategories-dd') }}/" + this
                    .value);
                responseData.forEach(data => {
                    let dropDownSegment =
                        `<option value="${data.id}">${data.name}</option>`;
                    dropDown += dropDownSegment;
                    /* $('#product_category_id').html(dropDown); */
                });
            } else {
                responseData = await fetchData("{{ url('admin/productcategories-dd') }}");
                responseData.forEach(data => {
                    let dropDownSegment =
                        `<option value="${data.id}">${data.name}</option>`;
                    dropDown += dropDownSegment;
                    /* $('#product_category_id').html(dropDown); */
                });
            }
            $('#product_category_id').html(dropDown);
        });
        /* async function fetchData(url) {
            try {
                let res = await fetch(url);
                return await (res.json());
            } catch (error) {
                console.log(error);
            }
        } */

        function addItem() {

            $('#itemForm').trigger("reset");
            $('#itemModal').modal('show');
            $("#title_view").html("Add");
            $("#submitValue").html("Create");
            $("#id").val("");
        }

        function editItem(id) {

            $('#itemForm').trigger("reset");
            brand_id_error = false;
            $("#brand_id").removeClass('is-invalid');
            $("#brand_id_error").html('');
            item_category_id_error = false;
            $("#item_category_id").removeClass('is-invalid');
            $("#item_category_id_error").html('');
            item_sub_category_id_error = false;
            $("#item_sub_category_id").removeClass('is-invalid');
            $("#item_sub_category_id_error").html('');
            product_category_id_error = false;
            $("#product_category_id").removeClass('is-invalid');
            $("#product_category_id_error").html('');
            name_error = false;
            $("#name").removeClass('is-invalid');
            $("#name_error").html('');
            qty_error = false;
            $("#qty").removeClass('is-invalid');
            $("#qty_error").html('');
            price_error = false;
            $("#price").removeClass('is-invalid');
            $("#price_error").html('');
            $("#id").val(id);
            $("#title_view").html("Edit");
            $("#submitValue").html("Submit");

            $.ajax({
                url: "{{ url('admin/items') }}" + "/" + id,
                method: "GET",
                success: function(response) {
                    $('#name').val(response.data.name);
                    $('#description').val(response.data.description);
                    $('#qty').val(response.data.qty);
                    $('#price').val(response.data.price);
                    $('#discount_amount').val(response.data.discount_amount);
                    $('#how_to_use').val(response.data.how_to_use);
                    $('#benefits').val(response.data.benefits);
                    $('#brand_id').val(response.data.brand_id);
                    $('#item_category_id').val(response.data.item_category_id);
                    $('#item_sub_category_id').val(response.data.item_sub_category_id);
                    $('#product_category_id').val(response.data.product_category_id);
                    $('#unit_id').val(response.data.unit_id);
                    $('#unit_value').val(response.data.unit_value);
                    $('#discount_type').val(response.data.discount_type);
                }
            });
            $('#itemModal').modal('show');

        }
    </script>

    {{-- Add/Update Item Validation --}}

    <script>
        var brand_id_error = true;
        var item_category_id_error = true;
        var item_sub_category_id_error = true;
        var product_category_id_error = true;
        var qty_error = true;
        var price_error = true;
        var name_error = true;

        $(document).ready(function() {
            $(document).on("change", "#brand_id", function(e) {
                brandIdValidate();
            });
        });
        $(document).ready(function() {
            $(document).on("change", "#item_category_id", function(e) {
                itemCategoryIdValidate();
            });
        });
        $(document).ready(function() {
            $(document).on("change", "#item_sub_category_id", function(e) {
                subCategoryValidate();
            });
        });
        $(document).ready(function() {
            $(document).on("change", "#product_category_id", function(e) {
                productCategoryValidate();
            });
        });
        $(document).ready(function() {
            $(document).on("change", "#qty", function(e) {
                qtyValidate();
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

        //Brand Id Validation
        function brandIdValidate() {
            let brandId = $.trim($("#brand_id").val());
            if (!validate.requiredCheck(brandId)) {
                brand_id_error = true;
                $("#brand_id").addClass('is-invalid');
                return $("#brand_id_error").html('Brand is Required');
            }
            brand_id_error = false;
            $("#brand_id").removeClass('is-invalid');
            $("#brand_id_error").html('');
        }

        //Item Category Id Validation
        function itemCategoryIdValidate() {
            let itemCategoryId = $.trim($("#item_category_id").val());
            if (!validate.requiredCheck(itemCategoryId)) {
                item_category_id_error = true;
                $("#item_category_id").addClass('is-invalid');
                return $("#item_category_id_error").html('Item Category is Required');
            }
            item_category_id_error = false;
            $("#item_category_id").removeClass('is-invalid');
            $("#item_category_id_error").html('');
        }

        //Item Sub Category Id Validation
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

        //Item Product Category Id Validation
        function productCategoryValidate() {
            let productCategory = $.trim($("#product_category_id").val());
            if (!validate.requiredCheck(productCategory)) {
                product_category_id_error = true;
                $("#product_category_id").addClass('is-invalid');
                return $("#product_category_id_error").html('Product Category is Required');
            }
            product_category_id_error = false;
            $("#product_category_id").removeClass('is-invalid');
            $("#product_category_id_error").html('');
        }

        //Item Quantity Validation
        function qtyValidate() {
            let qty = $.trim($("#qty").val());
            if (!validate.requiredCheck(qty)) {
                qty_error = true;
                $("#qty").addClass('is-invalid');
                return $("#qty_error").html('Item Quantity can not be empty');
            }
            qty_error = false;
            $("#qty").removeClass('is-invalid');
            $("#qty_error").html('');
        }

        //Item Price Validation
        function priceValidate() {
            let price = $.trim($("#price").val());
            if (!validate.requiredCheck(price)) {
                price_error = true;
                $("#price").addClass('is-invalid');
                return $("#price_error").html('Item Price can not be empty');
            }
            price_error = false;
            $("#price").removeClass('is-invalid');
            $("#price_error").html('');
        }

        //Item Name Validation
        function nameValidate() {
            let name = $.trim($("#name").val());
            if (!validate.requiredCheck(name)) {
                name_error = true;
                $("#name").addClass('is-invalid');
                return $("#name_error").html('Item Name can not be empty');
            }
            name_error = false;
            $("#name").removeClass('is-invalid');
            $("#name_error").html('');
        }

        function formSubmit() {

            brandIdValidate();
            itemCategoryIdValidate();
            subCategoryValidate();
            productCategoryValidate();
            qtyValidate();
            priceValidate();
            nameValidate();

            if (brand_id_error ||
                item_category_id_error ||
                item_sub_category_id_error ||
                product_category_id_error ||
                qty_error ||
                price_error ||
                name_error
            ) {
                // console.log(brand_id_error);
                // console.log(item_category_id_error);
                // console.log(item_sub_category_id_error);
                // console.log(product_category_id_error);
                // console.log(name_error);
                alert("Please Fill Carefully", 'error');
                return false;
            } else {
                // console.log(brand_id_error);
                // console.log(item_category_id_error);
                // console.log(item_sub_category_id_error);
                // console.log(product_category_id_error);
                // console.log(name_error);
                return true;
            }
        }
    </script>
@endsection
