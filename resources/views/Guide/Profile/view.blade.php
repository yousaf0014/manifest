@extends('layouts.admin.app')
@section('content')
<!-- Page Heading -->
<h1 class="h3 mb-2 text-gray-800">User Details</h1>
<p class="mb-4">
    <div class="pull-right" style="margin-right: 40px;">
        <a href="{{ url('/users') }}" class="btn btn-danger btn-icon-split">
            <span class="icon text-white-50">
                <i class="fa fa-arrow-circle-left"></i>
            </span>
            <span class="text">Back</span>
        </a>
    </div>
</p>
<div class="clearfix"></div>
<div class="card shadow mb-4">
  <div class="card-header py-3">
    <h6 class="m-0 font-weight-bold text-primary">Users Details</h6>
  </div>
  <div class="card-body">
      <form  method="post" action="{{url('/updateprofile')}}" enctype="multipart/form-data">
        @csrf
        <div class="row">
          <h4 class="info-text"></h4>
          <div class="col-sm-6 col-sm-offset-1">
             <div class="picture-container">
                  <div class="picture">
                      <img src="{{Storage::disk('public')->url('uploads/'.$user->pic)}}" class="picture-src" width="300px" id="wizardPicturePreview" title=""/>
                      <input type="file"name="pic" id="wizard-picture">
                  </div>
                  <h6>Choose Picture</h6>
              </div>
          </div>
          <div class="col-sm-6">
              <div class="form-group">
                <label>First Name</label>
                <input type="text" required="" value="<?php echo $user->first_name; ?>" name="first_name" class="form-control" id="first_name" placeholder="First Name">
                  @error('first_name')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                  @enderror
              </div>
              <div class="form-group">
                <label>Last Name</label>
                <input type="text" required="" value="<?php echo $user->last_name; ?>" name="last_name" class="form-control" id="last name" placeholder="Last Name">
                @error('last_name')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
              </div>
          </div>
          <div class="col-sm-10 col-sm-offset-1">
              <div class="form-group">
                  <label>Email</label>
                  <input type="text" required="" value="<?php echo $user->email; ?>" name="email" class="form-control" id="email" placeholder="email">
                  @error('email')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                  @enderror
              </div>
          </div>
          <div class="col-sm-10 col-sm-offset-1">
              <div class="form-group">
                  <label>Password</label>
                  <input type="password" value="" name="password" class="form-control" id="password" placeholder="Password">
                  @error('password')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                  @enderror
              </div>
          </div>
          <div class="col-sm-10 col-sm-offset-1">
              <div class="form-group">
                  <label>Confirm Password</label>
                  <input type="password" placeholder="{{ __('Confirm Password') }}" class="form-control " id="password-confirm" name="password_confirmation" autocomplete="new-password">
              </div>
          </div>
          <div class="col-sm-10 col-sm-offset-1">
              <div class="form-group">
                  <button type="submit" class="btn btn-primary btn-icon-split">
                      <span class="icon text-white-50">
                          <i class="fa fa-save"></i>
                      </span>
                      <span class="text">Update</span>
                  </button>
              </div>
          </div>
        </div>
      </form>
  </div>
</div>
@endsection
@section('scripts')
<script>
</script>
@endsection