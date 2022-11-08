@extends('admin.layout')
@section('content')
    <div class="adminx-content">
        <div class="adminx-main-content">
            <div class="container-fluid">
                <div class="card">
                    <div class="row">
                        <div class="col">
                            <div class="float-left">
                                <h3 class="h-p-bold-gray">Sub Categories</h3>
                            </div>
                            <div class="float-right">
                                <!-- Button trigger modal -->
                                <button type="button" class="btn btn-primary" onclick="addSubCategory()">
                                    Add Sub Category
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
                                            <th scope="col">Category Type</th>
                                            <th scope="col">Sub Category Name</th>
                                            <th scope="col">Sub Category Description</th>
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
                                                <td>{{ $data->category_type }}</td>
                                                <td>{{ $data->name }}</td>
                                                <td>{{ $data->description }}</td>
                                                <td>{{ $data->status }}</td>

                                                <td>
                                                    <a href="javascript:void(0)" class="btn btn-sm btn-primary"
                                                        onclick="editSubCategory({{ $data->id }})">Edit</a>
                                                    <a href="{{ route('admin-sub-categories.destroy', $data->id) }}"
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

    <!-- Add/Update Sub Category Modal -->
    <div class="modal fade" id="subCategoryModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title h-bold-gray" id="exampleModalLabel">Sub Category : <span id="title_view"></span>
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ url('admin/sub-categories') }}" method="post" id="subCategoryForm" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="item_category_id">Category <span class="required_symbol">*</span></label>
                            <select name="item_category_id" id="item_category_id" class="form-control">
                                <option value="">--SELECT--</option>
                                @foreach ($categories as $key => $category)
                                    <option value={{ $category->id }}>{{ $category->name }}</option>
                                @endforeach
                            </select>
                            <p class="error" id="item_category_id_error"></p>
                        </div>
                        <div class="form-group">
                            <label for="name">Sub Category Name <span class="required_symbol">*</span></label>
                            <input type="text" class="form-control" name="name" id="name">
                            <p class="error" id="name_error"></p>
                        </div>
                        <div class="form-group">
                            <label for="description">Sub Category Description</span></label>
                            <input type="text" class="form-control" name="description" id="description">
                        </div>
                        <div class="form-group">
                            <label for="category_type">Category Type <span class="required_symbol">*</span></span></label>
                            <input type="hidden" class="form-control" name="id" id="id">
                            <select name="category_type" id="category_type" class="form-control">
                                <option value="">--SELECT--</option>
                                <option value="Product">Product</option>
                                <option value="Service">Service</option>
                            </select>
                            <p class="error" id="category_type_error"></p>
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
        function addSubCategory() {
            $('#subCategoryForm').trigger("reset");
            $('#subCategoryModal').modal('show');
            $("#title_view").html("Add");
            $("#submitValue").html("Create");
            $("#id").val("");
        }

        function editSubCategory(id) {
            $('#subCategoryForm').trigger("reset");
            item_category_id_error = false;
            $("#item_category_id").removeClass('is-invalid');
            $("#item_category_id_error").html('');
            name_error = false;
            $("#name").removeClass('is-invalid');
            $("#name_error").html('');
            category_type_error = false;
            $("#category_type").removeClass('is-invalid');
            $("#category_type_error").html('');
            $("#id").val(id);
            $("#title_view").html("Edit");
            $("#submitValue").html("Submit");

            $.ajax({
                url: "{{ url('admin/sub-categories') }}" + "/" + id,
                method: "GET",
                success: function(response) {
                    console.log(response);
                    $('#category_type').val(response.data.category_type);
                    $('#name').val(response.data.name);
                    $('#description').val(response.data.description);
                    $('#item_category_id').val(response.data.item_category_id);
                }
            });
            $('#subCategoryModal').modal('show');

        }
    </script>

    {{-- Add/Update Sub-Category Validation --}}
    <script>
        var item_category_id_error = true;
        var name_error = true;
        var category_type_error = true;

        $(document).ready(function() {
            $(document).on("change", "#item_category_id", function(e) {
                itemCategoryIdValidate();
            });
        });
        $(document).ready(function() {
            $(document).on("change", "#name", function(e) {
                nameValidate();
            });
        });
        $(document).ready(function() {
            $(document).on("change", "#category_type", function(e) {
                categoryTypeValidate();
            });
        });

        //Item Category Id Validation
        function itemCategoryIdValidate() {
            let itemCategoryId = $.trim($("#item_category_id").val());
            if (!validate.requiredCheck(itemCategoryId)) {
                item_category_id_error = true;
                $("#item_category_id").addClass('is-invalid');
                return $("#item_category_id_error").html('Category is Required');
            }
            item_category_id_error = false;
            $("#item_category_id").removeClass('is-invalid');
            $("#item_category_id_error").html('');
        }

        //Sub-Category Name Validation
        function nameValidate() {
            let name = $.trim($("#name").val());
            if (!validate.requiredCheck(name)) {
                name_error = true;
                $("#name").addClass('is-invalid');
                return $("#name_error").html('Sub-Category Name can not be empty');
            }
            name_error = false;
            $("#name").removeClass('is-invalid');
            $("#name_error").html('');
        }

        //Category Type Validation
        function categoryTypeValidate() {
            let categoryType = $.trim($("#category_type").val());
            if (!validate.requiredCheck(categoryType)) {
                category_type_error = true;
                $("#category_type").addClass('is-invalid');
                return $("#category_type_error").html('Category Type is Required');
            }
            category_type_error = false;
            $("#category_type").removeClass('is-invalid');
            $("#category_type_error").html('');
        }

        function formSubmit() {

            itemCategoryIdValidate();
            nameValidate();
            categoryTypeValidate();

            if (name_error || category_type_error || item_category_id_error) {
                alert("Please Fill Carefully", 'error');
                return false;
            } else {
                return true;
            }
        }
    </script>
@endsection
