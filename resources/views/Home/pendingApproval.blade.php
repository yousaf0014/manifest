@extends('layouts.admin.app')
@section('content')

<!-- Page Heading -->
<h1 class="h3 mb-2 text-gray-800">Meditation</h1>
<div class="mb-4">
  <div class="pull-right">
    <form action="" method="" class="form-inline pull-right" id="searchForm">
      <div class="form-row">
          <div class="form-group p-1">
                <select class="form-control" name="status" onchange="$('#searchForm').submit();">
                    <option value="">--Status--</option>
                    <option <?php echo empty($status) || $status=='pending' ? "selected='selected'":''; ?> value="pending">Pending</option>
                    <option <?php echo $status=='approved' ? "selected='selected'":''; ?> value="approved">approved</option>
                    <option <?php echo $status=='not_approved' ? "selected='selected'":''; ?> value="not_approved">Declined</option>
                </select>
          </div>
          <div class="form-group p-1">
              <input type='text' class="form-control" name="keyword" value="<?php echo $keyword; ?>" placeholder="Title" />
          </div>
          <div class="form-group p-1">              
              <a href="javascript:;" onclick="$('#searchForm').submit();" class="btn btn-primary btn-icon-split">
                  <span class="icon text-white-50">
                      <i class="fa fa-search"></i>
                  </span>
                  <span class="text">Search</span>
              </a>
          </div>
      </div>
    </form>
  </div>
</div>
<div class="clearfix"></div>
<div class="card shadow mb-4">
  <div class="card-header py-3">
    <h6 class="m-0 font-weight-bold text-primary">Meditation List</h6>
  </div>
  <div class="card-body">
    <div class="table-responsive">
      <table class="table table-bordered table" >
        <thead class="thead-dark">
            <tr>
              <th scope="col">#</th>
              <th 
              scope="col">Type</th>
              <th scope="col">Title</th>
              <th scope="col">Description</th>
              <th scope="col">Pic</th>
              <th scope="col">Audio</th>
              <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($meditations as $index=>$md){ ?>
            <tr>
                <th scope="col"><?php echo $index+1; ?></th>
                <td scope="col" style="text-transform:capitalize;"><?php echo $md->type; ?></td>
                <td scope="col"><?php echo $md->title; ?></td>
                <td scope="col"><?php echo $md->description; ?></td>
                <td>
                    <?php if(!empty($md->pic)){ ?>
                    <img src="{{Storage::disk('public')->url('uploads/'.$md->pic)}}" width="200px">
                  <?php } ?>
                </td>
                <td>
                    <?php if(!empty($md->path)){ ?>
                    <video controls autoplay>
                        <source src="{{url('/readfile/'.$md->path)}}" type="video/mp4">
                        <source src="{{url('/readfile/'.$md->path)}}" type="video/ogg">
                        Your browser does not support the video tag.
                    </video>
                    <?php } ?>
                </td>
                <td>
                    <a title="View & Take Action" href="{{ url('notification_detail/meditation/'.$md->id)}}">
                        <i class="fa fa-eye"></i>
                    </a>&nbsp;
                    <?php if($md->posted == 1){?>
                      <a title="View comments" href="{{ url('comments/'.$md->id)}}">
                          <i class="fa fa-comments"></i>
                      </a>&nbsp;
                      <a title="View Ratings" href="{{ url('ratings/'.$md->id)}}">
                          <i class="fa fa-star"></i>
                      </a>&nbsp;
                      
                  <?php } ?>
                </td>
            </tr>
            <?php } ?>                                
        </tbody>
      </table>
    {!! $meditations->render() !!}
    </div>
  </div>
</div>
@endsection
@section('scripts')
<script>
</script>
@endsection