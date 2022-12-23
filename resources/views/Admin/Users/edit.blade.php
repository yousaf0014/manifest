@extends('layouts.admin.app')
@section('content')
<div class="row small-spacing">
    <div class="col-lg-12 col-xs-12">
        <div class="box-content">
            <div class="pull-left">
                <h2 class="box-title">Edit User</h2>
            </div>
            <div class="pull-right" style="margin-right: 40px;">
                <a href="{{ url('/users') }}" class="btn btn-danger btn-icon-split">
                    <span class="icon text-white-50">
                        <i class="fa fa-arrow-circle-left"></i>
                    </span>
                    <span class="text">Back</span>
                </a>
            </div>
            <div class="clearfix"></div>
            <div style="padding-top: 30px;">
                <div class="col-lg-12 col-xs-12">
                    <div class="box-content card white">                        
                        <!-- /.box-title -->
                        <div class="card-content">
                            <form class="form-horizontal" method="post" action="{{url('/users/update/'.$user->id)}}" enctype="multipart/form-data">
                                @csrf
                                <div class="form-group">
                                    <label for="inputEmail3" class="col-sm-2 control-label">first Name</label>
                                    <div class="col-sm-10">
                                        <input type="text" required="" value="<?php echo $user->first_name; ?>" name="first_name" class="form-control" id="first_name" placeholder="First Name">
                                        @error('first_name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="inputEmail3" class="col-sm-2 control-label">Last Name</label>
                                    <div class="col-sm-10">
                                        <input type="text" required="" value="<?php echo $user->last_name; ?>" name="last_name" class="form-control" id="last name" placeholder="Last Name">
                                        @error('last_name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="inputEmail3" class="col-sm-2 control-label">Email</label>
                                    <div class="col-sm-10">
                                        <input type="text" required="" value="<?php echo $user->email; ?>" name="email" class="form-control" id="email" placeholder="email">
                                        @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="inputEmail3" class="col-sm-2 control-label">Password</label>
                                    <div class="col-sm-10">
                                        <input type="password" value="" name="password" class="form-control" id="password" placeholder="Password">
                                        @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="name" class="col-sm-2 control-label">Confirm Password</label>
                                    <div class="col-sm-10">
                                        <input type="password" placeholder="{{ __('Confirm Password') }}" class="form-control " id="password-confirm" name="password_confirmation" autocomplete="new-password">
                                    </div>
                                </div>
                                <div class="form-group">
                                <label for="name" class="col-sm-2 control-label">Pic</label>
                                <div class="col-sm-10">
                                    <input type="file" placeholder="{{ __('pic') }}" class="form-control " id="pic" name="pic" >
                                    @error('pic')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    <?php if(!empty($user->pic)){?>
                                        <img src="{{Storage::disk('public')->url('uploads/'.$user->pic)}}" width="200px">
                                    <?php } ?>
                                    
                                </div>
                            </div>
                                <div class="form-group margin-bottom-0">
                                    <div class="col-sm-offset-2 col-sm-5">
                                        <button type="submit" class="btn btn-primary btn-icon-split">
                                            <span class="icon text-white-50">
                                                <i class="fa fa-save"></i>
                                            </span>
                                            <span class="text">Update</span>
                                        </button>
                                        <a href="{{ url('/users') }}" class="btn btn-danger btn-icon-split">
                                            <span class="icon text-white-50">
                                                <i class="fa fa-arrow-circle-left"></i>
                                            </span>
                                            <span class="text">Cancel</span>
                                        </a>
                                    </div>                                
                                </div>
                            </form>
                        </div>
                        <!-- /.card-content -->
                    </div>
                    <!-- /.box-content -->
                </div>
            </div>
            <!-- /.box-title -->            
            <!-- /.dropdown js__dropdown -->            
        </div>
        <!-- /.box-content -->
    </div>    
    <!-- /.col-lg-6 col-xs-12 -->
</div>

@endsection
@section('css')
    
@endsection
@section('scripts')
<script type="text/javascript">

</script>
@endsection