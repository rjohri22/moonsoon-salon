@extends('admin.layout')
@section('content')
<style>
    .symbol-eyes {
        font-size: 15px;
        display: -webkit-box;
        display: -webkit-flex;
        display: -moz-box;
        display: -ms-flexbox;
        display: flex;
        padding-top: 13px;
        position: absolute;
        border-radius: 25px;
        bottom: 3.5px;
        right: 11%;
        height: 100%;
        color: #666;
        -webkit-transition: all 0.4s;
        -o-transition: all 0.4s;
        -moz-transition: all 0.4s;
        transition: all 0.4s;
    }

    @media only screen and (max-width: 600px) {
        .symbol-eyes {
            right: 14%;
        }
    }
</style>
<div class="adminx-content" style="margin-bottom: -765px;">
    <div class="adminx-main-content">
        <div class="container-fluid">
            <div style="height:55"></div>
            <div class="card">
                <h3 class="text-center h-bold-gray">Password Update</h3>
            </div>
            <div class="card">
                <div style="height: 40px;;"></div>
                <form action="{{url('admin/password-update')}}" method="post">
                    @csrf()
                    <div class="row col-md-12">
                        <div class="col-md-4">
                        </div>
                        <div class="col-md-4">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="usr">Password:</label>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" id="password" minlength="6" required>
                                            <span class="symbol-eyes">
                                                <i id="pass-status" class="fa fa-eye" aria-hidden="true" onclick="viewPassword()" style="cursor: pointer;"></i>
                                            </span>
                                            <i class="fa fa-info-circle fa-sm font-italic" aria-hidden="true" style="color:#A9A9A9; "> <span class=" font-weight-bold" style="font-size:13px;">Password must be of 6 characters</span></i>
                                        </div>
                                    </div>
                                    @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="usr">Confirm Password:</label>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <input type="password" name="password_confirmation" class="form-control" minlength="6" id="password_confirm" required>

                                            <span class="symbol-eyes">
                                                <i id="pass-status_Confirm" class="fa fa-eye" aria-hidden="true" onclick="viewPasswordConfirm()" style="cursor: pointer;"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-12 text-center">
                                            <button type="submit" style="margin-top:5px;" class="btn btn-primary">Submit</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label></label>
                                    <div class="row">
                                        <div class="col-md-12">

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    function viewPassword() {
        var passwordInput = document.getElementById('password');
        var passStatus = document.getElementById('pass-status');

        if (passwordInput.type == 'password') {
            passwordInput.type = 'text';
            passStatus.className = 'fa fa-eye-slash';

        } else {
            passwordInput.type = 'password';
            passStatus.className = 'fa fa-eye';
        }
    }

    function viewPasswordConfirm() {
        var passwordInput_confirm = document.getElementById('password_confirm');
        var passStatus = document.getElementById('pass-status_Confirm');

        if (passwordInput_confirm.type == 'password') {
            passwordInput_confirm.type = 'text';
            passStatus.className = 'fa fa-eye-slash';

        } else {
            passwordInput_confirm.type = 'password';
            passStatus.className = 'fa fa-eye';
        }
    }
</script>