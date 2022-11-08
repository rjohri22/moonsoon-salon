@extends('admin.layout')
@section('content')
    <div class="adminx-content">
        <div class="adminx-main-content">
            <div class="container-fluid">
                <div class="card">
                    <div class="row">
                        <div class="col">
                            <div class="float-left">
                                <h3 class="h-p-bold-gray">Payment Methods</h3>
                            </div>
                            <div class="float-right">
                                <!-- Button trigger modal -->
                                <button type="button" class="btn btn-primary" onclick="addPaymentMethod()">
                                    Add Payment Method
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
                                            <th scope="col">Payment Method Name</th>
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
                                                <td>{{ $data->status }}</td>

                                                <td>
                                                    <a href="javascript:void(0)" class="btn btn-sm btn-primary"
                                                        onclick="editPaymentMethod({{ $data->id }})">Edit</a>
                                                    <a href="{{ route('admin-payment-methods.destroy', $data->id) }}"
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

    <!-- Add Payment Method Modal -->
    <div class="modal fade" id="paymentMethodModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title h-bold-gray" id="exampleModalLabel">PAYMENT METHOD : <span
                            id="title_view"></span></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ url('admin/payment-methods') }}" method="post" id="paymentMethodForm">
                        @csrf
                        <div class="form-group">
                            <label for="name">Payment Method Name <span class="required_symbol">*</span></label>
                            <input type="hidden" class="form-control" name="id" id="id">
                            <input type="text" class="form-control" name="name" id="name">
                            <p class="error" id="name_error"></p>
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
    {{-- Add & Update Form --}}
    <script>
        function addPaymentMethod() {
            $('#paymentMethodForm').trigger("reset");
            $('#paymentMethodModal').modal('show');
            $("#submitValue").html("Create");
            $("#title_view").html("Add");
            $("#id").val("");
        }

        function editPaymentMethod(id) {
            $('#paymentMethodForm').trigger("reset");
            name_error = false;
            $("#name").removeClass('is-invalid');
            $("#name_error").html('');
            $("#id").val(id);
            $("#submitValue").html("Submit");
            $("#title_view").html("Edit");

            $.ajax({
                url: "{{ url('admin/payment-methods') }}" + "/" + id,
                method: "GET",
                success: function(response) {
                    $('#name').val(response.data.name);
                }
            });
            $('#paymentMethodModal').modal('show');

        }
    </script>

    {{-- Add/Update Payment Method Validation --}}

    <script>
        var name_error = true;

        $(document).ready(function() {
            $(document).on("change", "#name", function(e) {
                nameValidate();
            });
        });

        //Payment Method Name Validation
        function nameValidate() {
            let name = $.trim($("#name").val());
            if (!validate.requiredCheck(name)) {
                name_error = true;
                $("#name").addClass('is-invalid');
                return $("#name_error").html('Payment Method Name can not be empty');
            }
            name_error = false;
            $("#name").removeClass('is-invalid');
            $("#name_error").html('');
        }

        function formSubmit() {
            console.log("abc");
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
