@extends('admin.layout')
@section('content')
    <div class="adminx-content">
        <div class="adminx-main-content">
            <div class="container-fluid">
                <div class="card">
                    <div class="row">
                        <div class="col">
                            <div class="float-left">
                                <h3 class="h-p-bold-gray">Shop Setting</h3>
                            </div>
                            <div class="float-right">
                                <!-- Button trigger modal -->
                                <button type="button" class="btn btn-primary" onclick="addShopSetting()">
                                    Add Setting
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
                                            <th scope="col">Delivery Charge</th>
                                            <th scope="col">Service Charge</th>
                                            <th scope="col">Return Days setup</th>
                                            <th scope="col">Minimum Order Value</th>
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
                                                <td>{{ $data->delivery_charge }}</td>
                                                <td>{{ $data->service_charge }}</td>
                                                <td>{{ $data->avail_return_days }}</td>
                                                <td>{{ $data->min_order_value }}</td>

                                                <td>
                                                    <a href="javascript:void(0)" class="btn btn-sm btn-primary"
                                                        onclick="editShopSetting({{ $data->id }})">Edit</a>
                                                    <a href="{{ route('admin-shop-setting.destroy', $data->id) }}"
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
    <div class="modal fade" id="settingModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title h-bold-gray" id="exampleModalLabel">Shop Setting : <span id="title_view"></span>
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ url('admin/shop-settings') }}" method="post" id="shopSettingForm">
                        @csrf
                        <div class="form-group">
                            <label for="name">Delivery Charge <span class="required_symbol">*</span></label>
                            <input type="hidden" class="form-control" name="id" id="id">
                            <input type="number" class="form-control" name="delivery_charge" id="delivery_charge"
                                required>
                        </div>
                        <div class="form-group">
                            <label for="name">Service Charge</label>
                            <input type="number" class="form-control" name="service_charge" id="service_charge" required>
                        </div>
                        <div class="form-group">
                            <label for="name">Max Return Days Allowed</label>
                            <input type="number" class="form-control" name="avail_return_days" id="avail_return_days"
                                required>
                        </div>
                        <div class="form-group">
                            <label for="name">Minimum Order Amount for free Delivery</label>
                            <input type="number" class="form-control" name="min_order_value" id="min_order_value"
                                required>
                        </div>
                        <button type="submit" class="btn btn-primary"><span id="submitValue"></span></button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    {{-- Add & Update Form --}}
    <script>
        function addShopSetting() {
            $('#shopSettingForm').trigger("reset");
            $('#settingModal').modal('show');
            $("#submitValue").html("Create");
            $("#title_view").html("Add");
            $("#id").val("");
        }

        function editShopSetting(id) {
            $('#shopSettingForm').trigger("reset");
            $("#id").val(id);
            $("#submitValue").html("Submit");
            $("#title_view").html("Edit");

            $.ajax({
                url: "{{ url('admin/shop-settings') }}" + "/" + id,
                method: "GET",
                success: function(response) {
                    $('#delivery_charge').val(response.data.delivery_charge);
                    $('#service_charge').val(response.data.service_charge);
                    $('#avail_return_days').val(response.data.avail_return_days);
                    $('#min_order_value').val(response.data.min_order_value);
                }
            });
            $('#settingModal').modal('show');

        }
    </script>
@endsection
