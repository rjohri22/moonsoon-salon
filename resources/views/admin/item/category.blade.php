@extends('admin.layout')
@section('content')
    <div class="adminx-content">
        <div class="adminx-main-content">
            <div class="container-fluid">
                <div class="card">
                    <div class="row">
                        <div class="col">
                            <div class="float-left">
                                <h3 class="h-p-bold-gray">Categories</h3>
                            </div>
                            <div class="float-right">
                                <!-- Button trigger modal -->
                                <button type="button" class="btn btn-primary" onclick="addCategory()">
                                    Add Category
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
                                            <th scope="col">Category Name</th>
                                            <th scope="col">Category Description</th>
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
                                                        onclick="editCategory({{ $data->id }})">Edit</a>
                                                    <a href="{{ route('admin-categories.destroy', $data->id) }}"
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

    <!-- Add/Update Category Modal -->
    <div class="modal fade" id="categoryModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title h-bold-gray" id="exampleModalLabel">CATEGORY : <span id="title_view"></span></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ url('admin/categories') }}" method="post" id="categoryForm" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="name">Category Name <span class="required_symbol">*</span></label>
                            <input type="hidden" class="form-control" name="id" id="id">
                            <input type="text" class="form-control" name="name" id="name">
                            <p class="error" id="name_error"></p>
                        </div>
                        <div class="form-group">
                            <label for="name">Category Description</label>
                            <input type="text" class="form-control" name="description" id="description">
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
                        
                        <button type="submit" class="btn btn-primary" onclick="return valSubmit()"><span
                                id="submitValue"></span></button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script>
        function addCategory() {
            $('#categoryForm').trigger("reset");
            $('#categoryModal').modal('show');
            $("#title_view").html("Add");
            $("#submitValue").html("Create");
            $("#id").val("");
        }

        function editCategory(id) {
            $('#categoryForm').trigger("reset");
            name_error = false;
            description_error = false;
            $("#name").removeClass('is-invalid');
            $("#name_error").html('');
            $("#description").removeClass('is-invalid');
            $("#description_error").html('');
            $("#id").val(id);
            $("#title_view").html("Edit");
            $("#submitValue").html("Submit");

            $.ajax({
                url: "{{ url('admin/categories') }}" + "/" + id,
                method: "GET",
                success: function(response) {
                    $('#name').val(response.data.name);
                    $('#description').val(response.data.description);
                }
            });
            $('#categoryModal').modal('show');

        }
    </script>

    {{-- Add/Update Category Validation --}}

    <script>
        var name_error = true;

        $(document).ready(function() {
            $(document).on("change", "#name", function(e) {
                nameValidate();
            });
        });

        //Category Name Validation
        function nameValidate() {
            let name = $.trim($("#name").val());
            if (!validate.requiredCheck(name)) {
                name_error = true;
                $("#name").addClass('is-invalid');
                return $("#name_error").html('Category Name can not be empty');
            }
            name_error = false;
            $("#name").removeClass('is-invalid');
            $("#name_error").html('');
        }

        function valSubmit() {

            nameValidate();

            if (name_error) {
                alert("Please Fill Carefully", 'error');
                return false;
            } else {
                return true;
            }
        }
    </script>
@endsection
