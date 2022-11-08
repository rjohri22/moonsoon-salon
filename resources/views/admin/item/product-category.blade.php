@extends('admin.layout')
@section('content')
    <div class="adminx-content">
        <div class="adminx-main-content">
            <div class="container-fluid">
                <div class="card">
                    <div class="row">
                        <div class="col">
                            <div class="float-left">
                                <h3 class="h-p-bold-gray">Product Categories</h3>
                            </div>
                            <div class="float-right">
                                <!-- Button trigger modal -->
                                <button type="button" class="btn btn-primary" onclick="addProductCategory()">
                                    Add Product Category
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
                                            <th scope="col">Product Category Name</th>
                                            <th scope="col">Product Category Description</th>
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
                                                <td>{{ $data->description }}</td>
                                                <td>{{ $data->status }}</td>

                                                <td>
                                                    <a href="javascript:void(0)" class="btn btn-sm btn-primary"
                                                        onclick="editProductCategory({{ $data->id }})">Edit</a>
                                                    <a href="{{ route('admin-product-categories.destroy', $data->id) }}"
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

    <!-- Add/Update Product Category Modal -->
    <div class="modal fade" id="productCategoryModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title h-bold-gray" id="exampleModalLabel">PRODUCT CATEGORY : <span
                            id="title_view"></span></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ url('admin/product-categories') }}" method="post" id="productCategoryForm" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="item_sub_category_id">Sub Category <span class="required_symbol">*</span></label>
                            <select class="form-control" name="item_sub_category_id" id="item_sub_category_id">
                                <option value="">--SELECT--</option>
                                @foreach ($subCategories as $key => $subCategory)
                                    <option value={{ $subCategory->id }}>
                                        {{ $subCategory->name }}({{ $subCategory->category_type }})</option>
                                @endforeach
                            </select>
                            <p class="error" id="item_sub_category_id_error"></p>
                        </div>
                        <div class="form-group">
                            <label for="name">Product Category Name <span class="required_symbol">*</span></label>
                            <input type="hidden" class="form-control" name="id" id="id">
                            <input type="text" class="form-control" name="name" id="name">
                            <p class="error" id="name_error"></p>
                        </div>
                        <div class="form-group">
                            <label for="name">Product Category Description</label>
                            <input type="text" class="form-control" name="description" id="description">
                        </div>
                        <div class="form-group">
                            <label for="name"><small>Image dimension : 300 x 450</small></label>
                            <div id="image_preview_section">
                                <img id="user_img"
                                    height="130"
                                    width="130"
                                    style="border:solid" />
                            </div>
                            <input type="file" class="form-control" name="file" id="file" accept="image/*" onchange="validateimg(this)">
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
        function addProductCategory() {
            $('#productCategoryForm').trigger("reset");
            $('#productCategoryModal').modal('show');
            $("#title_view").html("Add");
            $("#submitValue").html("Create");
            $("#id").val("");
        }

        function editProductCategory(id) {
            $('#productCategoryForm').trigger("reset");
            name_error = false;
            $("#name").removeClass('is-invalid');
            $("#name_error").html('');
            item_sub_category_id_error = false;
            $("#item_sub_category_id").removeClass('is-invalid');
            $("#item_sub_category_id_error").html('');
            $("#id").val(id);
            $("#title_view").html("Edit");
            $("#submitValue").html("Submit");

            $.ajax({
                url: "{{ url('admin/product-categories') }}" + "/" + id,
                method: "GET",
                success: function(response) {
                    $('#name').val(response.data.name);
                    $('#description').val(response.data.description);
                    $('#item_sub_category_id').val(response.data.item_sub_category_id);
                }
            });
            $('#productCategoryModal').modal('show');

        }
    </script>

    {{-- Add/Update Product-Category Validation --}}
    <script>
        var item_sub_category_id_error = true;
        var name_error = true;

        $(document).ready(function() {
            $(document).on("change", "#item_sub_category_id", function(e) {
                itemSubCategoryIdValidate();
            });
        });
        $(document).ready(function() {
            $(document).on("change", "#name", function(e) {
                nameValidate();
            });
        });

        //Sub-Category Validation
        function itemSubCategoryIdValidate() {
            let itemSubCategoryId = $.trim($("#item_sub_category_id").val());
            if (!validate.requiredCheck(itemSubCategoryId)) {
                item_sub_category_id_error = true;
                $("#item_sub_category_id").addClass('is-invalid');
                return $("#item_sub_category_id_error").html('Sub-Category is Required');
            }
            item_sub_category_id_error = false;
            $("#item_sub_category_id").removeClass('is-invalid');
            $("#item_sub_category_id_error").html('');
        }

        //Product-Category Name Validation
        function nameValidate() {
            let name = $.trim($("#name").val());
            if (!validate.requiredCheck(name)) {
                name_error = true;
                $("#name").addClass('is-invalid');
                return $("#name_error").html('Product-Category Name can not be empty');
            }
            name_error = false;
            $("#name").removeClass('is-invalid');
            $("#name_error").html('');
        }

        function formSubmit() {

            itemSubCategoryIdValidate();
            nameValidate();

            if (item_sub_category_id_error || name_error) {
                alert("Please Fill Carefully", 'error');
                return false;
            } else {
                return true;
            }
        }
    </script>
@endsection
