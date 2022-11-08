@extends('admin.layout')
@section('content')
    <div class="adminx-content">
        <div class="adminx-main-content">
            <div class="container-fluid">
                <div class="card">
                    <div class="row">
                        <div class="col">
                            <div class="float-left">
                                <h3 class="h-p-bold-gray">Offers</h3>
                            </div>
                            <div class="float-right">
                                <!-- Button trigger modal -->
                                <button type="button" class="btn btn-primary" onclick="addOffer()">
                                    Add Offer
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
                                            <th scope="col">Offer Name</th>
                                            <th scope="col">Banner</th>
                                            <th scope="col">Add to Slider?</th>
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
                                                <td>{{ $data->title }}</td>
                                                <td > 
                                                @if(!empty($data->media))    
                                                <img class="preview-image" width="30" height="30"
                                                        src="{{!empty($data->media) ? $data->media->getUrl() :'' }}"></td>
                                                @endif

                                                <td><a @if ($data->is_slider == 1) href="{{ url('admin/add-to-slider/' . $data->id) }}" class="btn btn-success btn-sm">Added
                                                @else
                                                     href="{{ url('admin/add-to-slider/' . $data->id) }}" class="btn btn-secondary btn-sm">Not Added @endif
                                                        </a></td>
                                                <td>
                                                    <a href="javascript:void(0)" class="btn btn-sm btn-primary"
                                                        onclick="editOffer({{ $data->id }})">Edit</a>
                                                    <a href="{{ route('admin-offers.destroy', $data->id) }}"
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

    <!-- Add Offer Modal -->
    <div class="modal fade" id="offerModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title h-bold-gray" id="exampleModalLabel">OFFER : <span id="title_view"></span></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ url('admin/offers') }}" method="post" id="offerForm" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="title">Offer Title <span class="required_symbol">*</span></label>
                                    <input type="hidden" class="form-control form-control-sm" name="id" id="id">
                                    <input type="text" class="form-control form-control-sm" name="title" id="title">
                                    <p class="error" id="title_error"></p>
                                </div>
                                <div class="form-group">
                                    <label for="date_valid_from">Date Valid From <span
                                            class="required_symbol">*</span></label>
                                    <input type="date" class="form-control form-control-sm" name="date_valid_from"
                                        id="date_valid_from">
                                    <p class="error" id="date_valid_from_error"></p>
                                </div>
                                <div class="form-group">
                                    <label for="date_valid_to">Date Valid To <span class="required_symbol">*</span></label>
                                    <input type="date" class="form-control form-control-sm" name="date_valid_to"
                                        id="date_valid_to">
                                    <p class="error" id="date_valid_to_error"></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="code">Code <span class="required_symbol">*</span></label>
                                    <input type="text" class="form-control form-control-sm" name="code" id="code">
                                    <p class="error" id="code_error"></p>
                                </div>

                                <div class="form-group">
                                    <label for="time_valid_from">Time Valid From <span
                                            class="required_symbol">*</span></label>
                                    <input type="time" class="form-control form-control-sm" name="time_valid_from"
                                        id="time_valid_from">
                                    <p class="error" id="time_valid_from_error"></p>
                                </div>

                                <div class="form-group">
                                    <label for="time_valid_to">Time Valid To <span class="required_symbol">*</span></label>
                                    <input type="time" class="form-control form-control-sm" name="time_valid_to"
                                        id="time_valid_to">
                                    <p class="error" id="time_valid_to_error"></p>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="amount">Offer Amount</label>
                                    <input type="text" class="form-control form-control-sm" name="amount" id="amount">
                                </div>
                                <div class="form-group">
                                    <label for="amount_type">Offer Type</label>
                                    <select class="form-control form-control-sm" name="amount_type" id="amount_type">
                                        <option value="">--SELECT--</option>
                                        @foreach ($discountTypes as $key => $discountType)
                                            <option value={{ $discountType->id }}>
                                                {{ Str::ucfirst($discountType->name) }}
                                            </option>
                                        @endforeach
                                    </select>
                                    {{-- <input type="text" class="form-control form-control-sm" name="amount_type" id="amount_type"> --}}
                                </div>
                                <div class="form-group">
                                    <label for="name"><small>Image dimension : 300 x 450</small></label>
                                    <div id="image_preview_section">
                                        <img id="user_img"
                                            height="130"
                                            width="130"
                                            style="border:solid" />
                                    </div>
                                    <input type="file" class="form-control" name="file" id="file" accept="image/*" onchange="validateSlider(this)">
                                
                                    <!-- <input class="form-control form-control-sm" type="file" name="file" id="file"> -->
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="days">Days</label>
                                    @for ($day = 0; $day < 7; $day++)
                                        <br><input type="checkbox" name="days[]"
                                            id="days_{{ strtolower('' . date('l', mktime(0, 0, 0, 8, $day, 2011))) }}"
                                            value="{{ strtolower('' . date('l', mktime(0, 0, 0, 8, $day, 2011))) }}">{{ ucfirst('' . date('l', mktime(0, 0, 0, 8, $day, 2011))) }}
                                    @endfor
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="table_type">Offer On:</label>
                                    <select class="form-control form-control-sm" name="table_type" id="table_type">
                                        <option value="">--Select--</option>
                                        <option value="brands">Brand</option>
                                        <option value="item_categories">Category</option>
                                        <option value="item_sub_categories">Sub-Category</option>
                                        <option value="product_categories">Product-Category</option>
                                        <option value="items">Item</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="table_id">Offer Prefrence</label>
                                    <select class="form-control form-control-sm" name="table_id" id="table_id">
                                        <option value="">--Select--</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="is_slider">Is Slider ?</label>
                                    <select class="form-control form-control-sm" name="is_slider" id="is_slider">
                                        <option value="1">Yes</option>
                                        <option value="0">No</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea type="text" class="form-control form-control-sm" name="description"
                                id="description"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary" onclick="return formSubmit()"><span
                                id="submitValue"></span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    {{-- Add & Update Form --}}
    <script>
        function addOffer() {
            $('#offerForm').trigger("reset");
            $('#offerModal').modal('show');
            $("#submitValue").html("Create");
            $("#title_view").html("Add");
            $("#id").val("");
        }

        function editOffer(id) {
            $('#offerForm').trigger("reset");
            title_error = false;
            code_error = false;
            date_valid_from_error = false;
            date_valid_to_error = false;
            time_valid_from_error = false;
            time_valid_to_error = false;
            $("#title").removeClass('is-invalid');
            $("#title_error").html('');
            $("#code").removeClass('is-invalid');
            $("#code_error").html('');
            $("#date_valid_from").removeClass('is-invalid');
            $("#date_valid_from_error").html('');
            $("#date_valid_to").removeClass('is-invalid');
            $("#date_valid_to_error").html('');
            $("#time_valid_from").removeClass('is-invalid');
            $("#time_valid_from_error").html('');
            $("#time_valid_to").removeClass('is-invalid');
            $("#time_valid_to_error").html('');
            $("#id").val(id);
            $("#submitValue").html("Submit");
            $("#title_view").html("Edit");

            $.ajax({
                url: "{{ url('admin/offers') }}" + "/" + id,
                method: "GET",
                success: function(response) {
                    $('#title').val(response.data.title);
                    $('#code').val(response.data.code);
                    $('#date_valid_from').val(response.data.date_valid_from);
                    $('#date_valid_to').val(response.data.date_valid_to);
                    $('#time_valid_from').val(response.data.time_valid_from);
                    $('#time_valid_to').val(response.data.time_valid_to);
                    response.data.days.forEach((item, index) => {
                        $(`#days_${item}`).prop('checked', true);
                    });
                    $('#table_type').val(response.data.table_type).change();
                    setTimeout(() => {
                        $('#table_id').val(response.data.table_id);
                    }, 1500);
                    $('#days').val(response.data.days);
                    $('#amount').val(response.data.amount);
                    $('#amount_type').val(response.data.amount_type);
                    $('#description').val(response.data.description);
                }
            });
            $('#offerModal').modal('show');

        }
    </script>

    {{-- Add/Update Offer Validation --}}
    <script>
        var title_error = true;
        var code_error = true;
        var date_valid_from_error = true;
        var date_valid_to_error = true;
        var time_valid_from_error = true;
        var time_valid_to_error = true;

        $(document).ready(function() {
            $(document).on('change', '#table_type', async function() {
                let dropDown = '<option value="">--Select--</option>';
                if (this.value != "") {
                    responseData = await fetchData("{{ url('admin/offer-prefrence') }}/" + this
                        .value);
                    responseData.forEach(data => {
                        let dropDownSegment =
                            `<option value="${data.id}">${data.name}</option>`;
                        dropDown += dropDownSegment;
                    });
                }
                $('#table_id').html(dropDown);
            });

            $(document).on("change", "#title", function(e) {
                titleValidate();
            });
        });
        $(document).ready(function() {
            $(document).on("change", "#code", function(e) {
                codeValidate();
            });
        });
        $(document).ready(function() {
            $(document).on("change", "#date_valid_from", function(e) {
                dateValidFromValidate();
            });
        });
        $(document).ready(function() {
            $(document).on("change", "#date_valid_to", function(e) {
                dateValidToValidate();
            });
        });
        $(document).ready(function() {
            $(document).on("change", "#time_valid_from", function(e) {
                timeValidFromValidate();
            });
        });
        $(document).ready(function() {
            $(document).on("change", "#time_valid_to", function(e) {
                timeValidToValidate();
            });
        });

        //Offer Title Validation
        function titleValidate() {
            let title = $.trim($("#title").val());
            if (!validate.requiredCheck(title)) {
                title_error = true;
                $("#title").addClass('is-invalid');
                return $("#title_error").html('Offer Title can not be empty');
            }
            title_error = false;
            $("#title").removeClass('is-invalid');
            $("#title_error").html('');
        }

        //Offer Code Validation
        function codeValidate() {
            let code = $.trim($("#code").val());
            if (!validate.requiredCheck(code)) {
                code_error = true;
                $("#code").addClass('is-invalid');
                return $("#code_error").html('Code can not be empty');
            }
            code_error = false;
            $("#code").removeClass('is-invalid');
            $("#code_error").html('');
        }

        //Date Valid From Validation
        function dateValidFromValidate() {
            let dateValidFrom = $.trim($("#date_valid_from").val());
            if (!validate.requiredCheck(dateValidFrom)) {
                date_valid_from_error = true;
                $("#date_valid_from").addClass('is-invalid');
                return $("#date_valid_from_error").html('Date Valid From can not be empty');
            }
            date_valid_from_error = false;
            $("#date_valid_from").removeClass('is-invalid');
            $("#date_valid_from_error").html('');
        }

        //Date Valid To Validation
        function dateValidToValidate() {
            let dateValidTo = $.trim($("#date_valid_to").val());
            if (!validate.requiredCheck(dateValidTo)) {
                date_valid_to_error = true;
                $("#date_valid_to").addClass('is-invalid');
                return $("#date_valid_to_error").html('Date Valid To can not be empty');
            }
            date_valid_to_error = false;
            $("#date_valid_to").removeClass('is-invalid');
            $("#date_valid_to_error").html('');
        }

        //Time Valid From Validation
        function timeValidFromValidate() {
            let timeValidFrom = $.trim($("#time_valid_from").val());
            if (!validate.requiredCheck(timeValidFrom)) {
                time_valid_from_error = true;
                $("#time_valid_from").addClass('is-invalid');
                return $("#time_valid_from_error").html('Time Valid From can not be empty');
            }
            time_valid_from_error = false;
            $("#time_valid_from").removeClass('is-invalid');
            $("#time_valid_from_error").html('');
        }

        //Time Valid To Validate Validation
        function timeValidToValidate() {
            let timeValidTo = $.trim($("#time_valid_to").val());
            if (!validate.requiredCheck(timeValidTo)) {
                time_valid_to_error = true;
                $("#time_valid_to").addClass('is-invalid');
                return $("#time_valid_to_error").html('Time Valid To Validate can not be empty');
            }
            time_valid_to_error = false;
            $("#time_valid_to").removeClass('is-invalid');
            $("#time_valid_to_error").html('');
        }

        function formSubmit() {

            titleValidate();
            codeValidate();
            dateValidFromValidate();
            dateValidToValidate();
            timeValidFromValidate();
            timeValidToValidate();

            if (title_error ||
                code_error ||
                date_valid_from_error ||
                date_valid_to_error ||
                time_valid_from_error ||
                time_valid_to_error
            ) {
                alert("Please Fill Carefully", 'error');
                return false;
            } else {
                return true;
            }
        }
    </script>
@endsection
