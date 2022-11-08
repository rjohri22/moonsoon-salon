</div>
<style>
    footer.sticky-footer {
        padding: 2rem 0;
        flex-shrink: 0;
    }

    .bg-white {
        background-color: #fff !important;
    }

    footer.sticky-footer .copyright {
        line-height: 1;
        font-size: 0.8rem;
        color: #858796;
    }

</style>
<footer class="sticky-footer bg-white">
    <div class="container my-auto">
        <div class="copyright text-center my-auto">
            <span><b>Monsoon Salon © {{ date('Y') }}. All Rights Reserved.</b></span>
        </div>
    </div>
</footer>

<!-- If you prefer jQuery these are the required scripts -->
<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js"></script>
<script src="{{ asset('/admin_asset/js/vendor.js') }}"></script>
<script src="{{ asset('/admin_asset/js/adminx.js') }}"></script>
<script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.16/js/dataTables.bootstrap4.min.js"></script>


{{-- SweetAlert --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"
integrity="sha512-AA1Bzp5Q0K1KanKKmvN/4d3IRKVlv9PYgwFPvm32nPO6QS8yH1HO7LbgB1pgiOxPtfeg5zEn2ba64MUcqJx6CA=="
crossorigin="anonymous" referrerpolicy="no-referrer"></script>

{{-- For Form Validation --}}
<script src="{{ asset('website/js/validation.js') }}"></script>

{{-- CREATE/UPDATE SWAL --}}
@if (Session::has('success'))
    <script>
        swal("Success!", "{!! Session::get('success') !!}", "success", {
            button: "OK",
        });
    </script>
    <?php session()->forget('success'); ?>
@endif

{{-- DELETE SWAL --}}
@if (Session::has('delete'))
    <script>
        swal("", "{!! Session::get('delete') !!}", "delete", {
            button: "OK",
            icon: "error",
        });
    </script>
    <?php session()->forget('delete'); ?>
@endif
<script>



    function validateimg(ctrl) { 
        var fileUpload = ctrl;
        var regex = new RegExp("([a-zA-Z0-9\s_\\.\-:])+(.jpg|.PNG|.JPG|.jpeg|.png)$");
        if (regex.test(fileUpload.value.toLowerCase())) {
            if (typeof (fileUpload.files) != "undefined") {
                var reader = new FileReader();
                reader.readAsDataURL(fileUpload.files[0]);
                reader.onload = function (e) {
                    var image = new Image();
                    image.src = e.target.result;
                    image.onload = function () {
                        var height = this.height;
                        var width = this.width;
                        if (height < 500 || width < 500) {
                            alert("At least you can upload a 500*500 photo size.");
                            return false;
                        }else{
                            // alert("Uploaded image has valid Height and Width.");
                            var validExtensions = ['jpg','png','jpeg','PNG','JPG']; //array of valid extensions
                            var fileName = fileUpload.files[0].name;
                            var fileNameExt = fileName.substr(fileName.lastIndexOf('.') + 1);
                            if ($.inArray(fileNameExt, validExtensions) == -1) {
                                fileUpload.type = ''
                                fileUpload.type = 'file'
                                $('#user_img').attr('src',"");
                                // fileUpload.val()
                                alert("Only these file types are accepted : "+validExtensions.join(', '));
                            }
                            else
                            {
                            if (fileUpload.files || fileUpload.files[0]) {
                                var filerdr = new FileReader();
                                filerdr.onload = function (e) {
                                    $('#user_img').attr('src', e.target.result);
                                }
                                filerdr.readAsDataURL(fileUpload.files[0]);
                            }
                            }
                            return true;
                        }
                    };
                }
            } else {
                alert("This browser does not support HTML5.");
                return false;
            }
        } else {
            alert("Please select a valid Image file.");
            return false;
        }
    }
    function validateSlider(ctrl) { 
        var fileUpload = ctrl;
        var regex = new RegExp("([a-zA-Z0-9\s_\\.\-:])+(.jpg|.PNG|.JPG|.jpeg|.png)$");
        if (regex.test(fileUpload.value.toLowerCase())) {
            if (typeof (fileUpload.files) != "undefined") {
                var reader = new FileReader();
                reader.readAsDataURL(fileUpload.files[0]);
                reader.onload = function (e) {
                    var image = new Image();
                    image.src = e.target.result;
                    image.onload = function () {
                        var height = this.height;
                        var width = this.width;
                        if (height < 300 || width < 450) {
                            alert("At least you can upload a 700*500 photo size.");
                            return false;
                        }else{
                            // alert("Uploaded image has valid Height and Width.");
                            var validExtensions = ['jpg','png','jpeg','PNG','JPG']; //array of valid extensions
                            var fileName = fileUpload.files[0].name;
                            var fileNameExt = fileName.substr(fileName.lastIndexOf('.') + 1);
                            if ($.inArray(fileNameExt, validExtensions) == -1) {
                                fileUpload.type = ''
                                fileUpload.type = 'file'
                                $('#user_img').attr('src',"");
                                // fileUpload.val()
                                alert("Only these file types are accepted : "+validExtensions.join(', '));
                            }
                            else
                            {
                            if (fileUpload.files || fileUpload.files[0]) {
                                var filerdr = new FileReader();
                                filerdr.onload = function (e) {
                                    $('#user_img').attr('src', e.target.result);
                                }
                                filerdr.readAsDataURL(fileUpload.files[0]);
                            }
                            }
                            return true;
                        }
                    };
                }
            } else {
                alert("This browser does not support HTML5.");
                return false;
            }
        } else {
            alert("Please select a valid Image file.");
            return false;
        }
    }
</script>
{{-- DELETE Confirmation --}}
<script>
    async function fetchData(url) {
        try {
            let res = await fetch(url);
            return await (res.json());
        } catch (error) {
            console.log(error);
        }
    }
    $('.show-confirm').on('click', function(event) {
        event.preventDefault();
        const url = $(this).attr('href');
        swal({
            title: 'Are you sure?',
            text: 'Record will be permanantly deleted!',
            icon: 'warning',
            buttons: ["Cancel", "DELETE"],
        }).then(function(value) {
            if (value) {
                $.ajax({
                    url: url,
                    type: 'DELETE',
                    data: {
                        _token: $("input[name=_token]").val(),
                        method: "DELETE"
                    },
                    success: function(response) {
                        console.log(response);
                        // if(response.data.message)
                        // {
                        //     alert(response.data.message);
                        // }
                        // $("pId" + id).remove();
                        location.reload();
                    }
                });
                // window.location.href = url;
            }
        });
    });
    function getMinutesOption(selectedValue)
    {
        let toAppend="";
        for(let i=0;i<=9;i++)
        {
                if(selectedValue=="0"+i)
                {
                    toAppend +=
                    `<option selected value="0` +
                    i+
                    `">0` +
                    i+
                    `</option>`;
                }else
                {
                    toAppend +=
                `<option value="0` +
                i+
                `">0` +
                i+
                `</option>`;
                }
        }
        for(let i=10;i<=59;i++)
        {
            if(selectedValue==+i)
            {
                toAppend +=
                `<option selected value="` +
                i+
                `">` +
                i+
                `</option>`;
            }else
            {
                toAppend +=
                `<option value="` +
                i+
                `">` +
                i+
                `</option>`;
            }
        }
        return toAppend;
    }
</script>
</body>

</html>
