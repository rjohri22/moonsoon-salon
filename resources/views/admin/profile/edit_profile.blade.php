@extends('admin.layout')
@section('content')
<div class="adminx-content" style="margin-bottom: -765px;">
    <div class="adminx-main-content">
        <div class="container-fluid">
            <div style="height:55"></div>
            <div class="card">
                <h3 class="text-center h-bold-gray">Profile Update</h3>
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
                                    <label for="usr">Name:</label>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <input type="text" name="name" value="{{Auth::user()->name}}" class="form-control capitalize" id="usr" required>
                                        </div>
                                    </div>
                                    @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="usr">Email:</label>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <input type="text" name="email" value="{{Auth::user()->email}}" class="form-control" id="usr" required>

                                        </div>
                                    </div>
                                    @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="usr">Username:</label>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <input type="text" name="username" value="{{Auth::user()->username}}" class="form-control" id="usr" required>
                                        </div>
                                    </div>
                                    @error('username')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-12 text-center">
                                            <button type="submit" class="btn btn-primary">Submit</button>
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