@extends('layouts.admin.app')
@section('content')
<!-- Page Heading -->
<h1 class="h3 mb-2 text-gray-800">User Details</h1>
<p class="mb-4">
    <div class="pull-right" style="margin-right: 40px;">
        <a href="{{ url('/appusers') }}" class="btn btn-danger btn-icon-split">
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
    <div class="table-responsive">
      <table class="table table-bordered table" >
        <tbody>
            <tr>
                <th scope="col">First Name</th>
                <td scope="col"><?php echo $user->first_name;?></td>
            </tr>
            <tr>
                <th scope="col">Last Name</th>
                <td scope="col"><?php echo $user->last_name;?></td>
            </tr>
            <tr>
                <th scope="col">Email</th>
                <td scope="col"><?php echo $user->email;?></td>
            </tr>
            <?php if($user->is_paid){ ?>
              <tr>
                  <th scope="col">Paid</th>
                  <td scope="col">
                    <?php echo !empty($user->is_paid) ? 'Yes':'No'; ?>
                  </td>
              </tr>
            <?php } ?>
            <?php if($user->trial_period){ ?>
              <tr>
                  <th scope="col">Trail Period</th>
                  <td scope="col">
                    <?php echo !empty($user->trial_period) ? 'Yes':'No'; ?>
                  </td>
              </tr>
            <?php } ?>
            
            <?php if($user->login_type != 'web'){ ?>
              <tr>
                  <th scope="col">Signup With</th>
                  <td scope="col">
                    {{$user->login_type}}
                  </td>
              </tr>
              <tr>
                  <th scope="col">Pic</th>
                  <td scope="col">
                    <?php if(!empty($user->pic)){?>
                        <img src="{{$user->pic}}" width="200px;">
                    <?php }else echo 'N/A'; ?>
                  </td>
              </tr>
            <?php }else if(!empty($user->pic)){ ?>
              <tr>
                  <th scope="col">Pic</th>
                  <td scope="col">
                    <?php if(!empty($user->pic)){?>
                        <img src="{{Storage::disk('public')->url('uploads/'.$user->pic)}}" width="200px;">
                    <?php }else echo 'N/A'; ?>
                  </td>
              </tr>
          <?php } ?>
                                         
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection
@section('scripts')
<script>
</script>
@endsection