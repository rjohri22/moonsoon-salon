@extends('admin.layout')
@section('content')
    <div class="adminx-content">
        <div class="adminx-main-content">
            <div class="container-fluid">
                <div class="card">
                    <div class="row">
                        <div class="col">
                            <div class="float-left">
                                <h3 class="h-p-bold-gray">Orders</h3>
                            </div>
                            <div class="float-right">
                                <!-- Button trigger modal -->
                                <button type="button" class="btn btn-primary" onclick="addOrder()">
                                    Add Order
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
                                            <th scope="col">Order No.</th>
                                            <th scope="col">Customer Name</th>
                                            <th scope="col">Txn. ID</th>
                                            <th scope="col">Txn. Status</th>
                                            <th scope="col">Payment Mode</th>
                                            <!-- <th scope="col">Delivery Address ID</th> -->
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
                                                <td>{{ $data->order_no }}</td>
                                                <td>{{ $data->user->first_name }} {{ $data->user->last_name }}</td>
                                                <td>{{ $data->txn_id }}</td>
                                                <td>{{ $data->txn_status }}</td>
                                                <td>{{ $data->payment_mode }}</td>
                                                <!-- <td>{{ $data->delivery_address_id }}</td> -->
                                                <td>{{ $data->status }}</td>

                                                <td>
                                                    <!-- <a href="javascript:void(0)" class="btn btn-sm btn-primary"
                                                        onclick="editOrder({{ $data->id }})">Edit</a> -->
                                                    <a href="javascript:void(0)" class="btn btn-sm btn-primary"
                                                    onclick="viewOrder({{ $data->id }})">View</a>
                                                    <a href="javascript:void(0)" class="btn btn-sm btn-primary"
                                                    onclick="downloadOrder({{ $data->id }})">Download</a>
                                                    <a href="{{ route('admin-orders.destroy', $data->id) }}"
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

    <!-- Add Order Modal -->
    <div class="modal fade" id="orderModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title h-bold-gray" id="exampleModalLabel">Order : <span id="title_view"></span></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ url('admin/orders') }}" method="post" id="orderForm">
                        @csrf
                        <div class="form-group">
                            <label for="txn_id">Txn. ID<span class="required_symbol">*</span></label>
                            <input type="hidden" class="form-control" name="id" id="id">
                            <input type="text" class="form-control" name="txn_id" id="txn_id">
                            <p class="error" id="name_error"></p>
                        </div>
                        <div class="form-group">
                            <label for="txn_status">Txn. Status<span class="required_symbol">*</span></label>
                            <input type="text" class="form-control" name="txn_status" id="txn_status">
                        </div>
                        <div class="form-group">
                            <label for="payment_mode">Payment Mode<span class="required_symbol">*</span></label>
                            <input type="text" class="form-control" name="payment_mode" id="payment_mode">
                        </div>
                        <div class="form-group">
                            <label for="delivery_address_id">Delivery Address ID<span class="required_symbol">*</span></label>
                            <input type="text" class="form-control" name="delivery_address_id" id="delivery_address_id">
                        </div>
                        <div class="form-group">
                            <label for="user_id">User ID<span class="required_symbol">*</span></label>
                            <input type="text" class="form-control" name="user_id" id="user_id">
                        </div>
                        <div class="form-group">
                            <label for="order_no">Order No<span class="required_symbol">*</span></label>
                            <input type="text" class="form-control" name="order_no" id="order_no">
                        </div>
                        <div class="form-group">
                            <label for="delivery_address">Delivery Address<span class="required_symbol">*</span></label>
                            <input type="text" class="form-control" name="delivery_address" id="delivery_address">
                        </div>
                        <div class="form-group">
                            <label for="sub_total">Sub Total<span class="required_symbol">*</span></label>
                            <input type="text" class="form-control" name="sub_total" id="sub_total">
                        </div>
                        <div class="form-group">
                            <label for="status">Status<span class="required_symbol">*</span></label>
                            <input type="text" class="form-control" name="status" id="status">
                        </div>
                        <div class="form-group">
                            <label for="discount_amount">Discount Amount<span class="required_symbol">*</span></label>
                            <input type="text" class="form-control" name="discount_amount" id="discount_amount">
                        </div>
                        <div class="form-group">
                            <label for="delivery_charge">Delivery Charge<span class="required_symbol">*</span></label>
                            <input type="text" class="form-control" name="delivery_charge" id="delivery_charge">
                        </div>
                        <div class="form-group">
                            <label for="cgst_amount">CGST Amount<span class="required_symbol">*</span></label>
                            <input type="text" class="form-control" name="cgst_amount" id="cgst_amount">
                        </div>
                        <div class="form-group">
                            <label for="sgst_amount">SGST Amount<span class="required_symbol">*</span></label>
                            <input type="text" class="form-control" name="sgst_amount" id="sgst_amount">
                        </div>
                        <div class="form-group">
                            <label for="igst_amount">IGST Amount<span class="required_symbol">*</span></label>
                            <input type="text" class="form-control" name="igst_amount" id="igst_amount">
                        </div>
                        <div class="form-group">
                            <label for="total_amount">Total Amount<span class="required_symbol">*</span></label>
                            <input type="text" class="form-control" name="total_amount" id="total_amount">
                        </div>
                        {{-- <div class="form-group">
                            <input type="file" class="form-control" name="file" id="file">
                        </div> --}}
                        <button type="submit" class="btn btn-primary" onclick="return formSubmit()"><span
                                id="submitValue"></span></button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="serviceViewModel" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog  modal-lg" role="document">
            <div class="modal-content"> 
                <div class="modal-header">
                    <h5 class="modal-title h-bold-gray" id="exampleModalLabel">Order : <span id="title_order_view"></span></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4">
                            <label for="view_order_no">Order No : -</label>
                            <span id="view_order_no"></span>
                        </div>
                        <div class="col-md-4">
                            <label for="view_date_time_display">Booking Date & Time :- </label>
                            <span id="view_date_time_display"></span>
                        </div>
                        <div class="col-md-4">
                            <label for="view_txn_id">Txn Id :-</label>
                            <span id="view_txn_id"></span>
                        </div>
                        <div class="col-md-4">
                            <label for="view_txn_status">Txn Status :-</label>
                            <span id="view_txn_status"></span>
                        </div>
                        <div class="col-md-4">
                            <label for="view_user_name">Customer Name :-</label>
                            <span id="view_user_name"></span>
                        </div>
                        <div class="col-md-4">
                            <label for="view_sub_total">Sub Total :-</label>
                            <span id="view_sub_total"></span>
                        </div>
                        <div class="col-md-4">
                            <label for="view_discount_amount">Discount Amount :-</label>
                            <span id="view_discount_amount"></span>
                        </div>
                        <div class="col-md-4">
                            <label for="view_total_amount">Total Amount :-</label>
                            <span id="view_total_amount"></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <h4>Items</h4>

                            <div class="row">
                                <table class="table">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>Item Name</th>
                                            <th>Rate</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                    <tbody id="service_items"></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    {{-- Add & Update Form --}}
    <script>
        function addOrder() {
            $('#orderForm').trigger("reset");
            $('#orderModal').modal('show');
            $("#submitValue").html("Create");
            $("#title_view").html("Add");
            $("#id").val("");
        }

        function editOrder(id) {
            $('#orderForm').trigger("reset");
            name_error = false;
            $("#name").removeClass('is-invalid');
            $("#name_error").html('');
            $("#id").val(id);
            $("#submitValue").html("Submit");
            $("#title_view").html("Edit");

            $.ajax({
                url: "{{ url('admin/orders') }}" + "/" + id,
                method: "GET",
                success: function(response) {
                    $('#txn_id').val(response.data.txn_id);
                    $('#txn_status').val(response.data.txn_status);
                    $('#payment_mode').val(response.data.payment_mode);
                    $('#delivery_address_id').val(response.data.delivery_address_id);
                }
            });
            $('#orderModal').modal('show');

        }
    </script>
    <script>
         function viewOrder(id) {
            $('#orderForm').trigger("reset");
            $("#title_order_view").html("View Orders");

            $.ajax({
                url: "{{ url('admin/orders') }}" + "/" + id,
                method: "GET",
                success: function(response) {
                    console.log("response",response);
                    $('#view_order_no').html(response.data.order_no);
                    $('#view_date_time_display').html(response.data.order_date_time_display);
                    $('#view_user_name').html(response.data.user_name);
                    $('#view_txn_id').html(response.data.txn_id);
                    $('#view_txn_status').html(response.data.txn_status);
                    $('#view_total_amount').html(response.data.total_amount);
                    $('#view_sub_total').html(response.data.sub_total);
                    $('#view_discount_amount').html(response.data.discount_amount);
                    let toAppend = '';
                    $( response.data.order_items).each(function( index ,value) {
                        // console.log( index + ": " + $( this ).text() );
                        toAppend +=`<tr><td>`+value.item_name+`</td><td>`+value.rate+`</td><td>`+value.total+`</td></tr>`;
                    });
                    $('#service_items').html(toAppend);
                }
            });
            $('#serviceViewModel').modal('show');

        }
        function downloadOrder(id) {

            $.ajax({
                url: "{{ url('admin/genrate-order-invoice') }}" + "?order_id=" + id,
                method: "GET",
                success: function(response) {
                    console.log("response",response);
                    window.open(response.data.link,'_blank');
                }
            });
            // $('#serviceViewModel').modal('show');

        }
    </script>

    {{-- Add/Update Order Validation

    <script>
        var name_error = true;

        $(document).ready(function() {
            $(document).on("change", "#name", function(e) {
                nameValidate();
            });
        });

        //Order Name Validation
        function nameValidate() {
            let name = $.trim($("#name").val());
            if (!validate.requiredCheck(name)) {
                name_error = true;
                $("#name").addClass('is-invalid');
                return $("#name_error").html('Order Name can not be empty');
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
    </script> --}}
@endsection
