@extends('admin.layout')
@section('content')
    <div class="adminx-content">
        <div class="adminx-main-content">
            <div class="container-fluid">
                <div class="card">
                    <div class="row">
                        <div class="col">
                            <div class="float-left">
                                <h3 class="h-p-bold-gray">Delivery Region Setting</h3>
                            </div>
                            <div class="float-right">
                                <!-- Button trigger modal -->
                                <button type="button" class="btn btn-primary" onclick="addZipCode()">
                                    Add Setting
                                </button>
                                <button type="button" class="btn btn-primary" onclick="uploadZipCode()">
                                    Upload
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
                                            <th scope="col">Zip Code</th>
                                            <th scope="col">City</th>
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
                                                <td>{{ $data->zipcode }}</td>
                                                <td>{{ $data->city }}</td>
                                                <td>{{ $data->status }}</td>
                                                <td>
                                                    <a href="javascript:void(0)" class="btn btn-sm btn-primary"
                                                        onclick="editZipCode({{ $data->id }})">Edit</a>
                                                    <a href="{{ route('admin-delivery-regions.destroy', $data->id) }}"
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

    <!-- Add Shop Setting Modal -->
    <div class="modal fade" id="zipCodeModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title h-bold-gray" id="exampleModalLabel">Delivery Region Setting : <span
                            id="title_view"></span>
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ url('admin/delivery-regions') }}" method="post" id="zipCodeForm">
                        @csrf
                        <div class="form-group">
                            <label for="name">Zip Code <span class="required_symbol">*</span></label>
                            <input type="hidden" class="form-control" name="id" id="id">
                            <input type="number" class="form-control" name="zipcode" id="zipcode" required>
                        </div>
                        <div class="form-group">
                            <label for="name">City</label>
                            <input type="text" class="form-control" name="city" id="city" required>
                        </div>
                        <button type="submit" class="btn btn-primary"><span id="submitValue"></span></button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="zipCodeUploadModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title h-bold-gray" id="exampleModalLabel">Delivery Region Setting : <span
                            id="title_upload_view"></span>
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ url('admin/import/delivery-regions') }}" method="post" id="zipCodeFormExcel" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="name">Upload<span class="required_symbol">*</span></label>
                            <input type="file" class="form-control" name="upload_excel" id="upload_excel">
                        </div>
                        
                        <button type="submit" class="btn btn-primary"><span id="submitValue">Upload</span></button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    {{-- Add & Update Form --}}
    <script>
        function addZipCode() {
            $('#zipCodeForm').trigger("reset");
            $('#zipCodeModal').modal('show');
            $("#submitValue").html("Create");
            $("#title_view").html("Add");
            $("#id").val("");
        }
        function uploadZipCode() {
            $('#zipCodeFormExcel').trigger("reset");
            $('#zipCodeUploadModal').modal('show');
            $("#submitValue").html("Create");
            $("#title_view").html("Add");
            $("#id").val("");
        }

        function editZipCode(id) {
            $('#zipCodeForm').trigger("reset");
            $("#id").val(id);
            $("#submitValue").html("Submit");
            $("#title_view").html("Edit");

            $.ajax({
                url: "{{ url('admin/delivery-regions') }}" + "/" + id,
                method: "GET",
                success: function(response) {
                    $('#zipcode').val(response.data.zipcode);
                    $('#city').val(response.data.city);
                }
            });
            $('#zipCodeModal').modal('show');

        }
    </script>
@endsection
