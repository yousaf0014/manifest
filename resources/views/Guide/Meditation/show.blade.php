@extends('layouts.guide.app')
@section('content')
<!-- Page Heading -->
<h1 class="h3 mb-2 text-gray-800">Meditation Details</h1>
<p class="mb-4">
    <div class="pull-right" style="margin-right: 40px;">
        <a href="{{ url('/meditations') }}" class="btn btn-danger btn-icon-split">
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
    <h6 class="m-0 font-weight-bold text-primary">Meditation Details</h6>
  </div>
  <div class="card-body">
    <div class="table-responsive">
      <table class="table table-bordered table" >
        <tbody>
            <tr>
                <th scope="col">Title</th>
                <td scope="col"><?php echo $md->title;?></td>
            </tr>
            <tr>
                <th scope="col">Description</th>
                <td scope="col"><?php echo $md->description;?></td>
            </tr>
            <tr>
                <th scope="col">Status</th>
                <td scope="col"><?php echo $md->status;?></td>
            </tr>
            <tr>
                <th scope="col">Pic</th>
                <td scope="col">
                  <?php if(!empty($md->pic)){?>
                      <img src="{{Storage::disk('public')->url('uploads/'.$md->pic)}}" width="200px;">
                  <?php }else echo 'N/A'; ?>
                                  
                  
                </td>
            </tr>
            <tr>
                <th scope="col">Audio</th>
                <td scope="col">
                   <video controls autoplay>
                    <source src="{{url('/readfile/'.$md->path)}}" type="video/mp4">
                    <source src="{{url('/readfile/'.$md->path)}}" type="video/ogg">
                    Your browser does not support the video tag.
                  </video>
                </td>
            </tr>               
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