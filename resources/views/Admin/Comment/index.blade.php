@extends('layouts.admin.app')
@section('content')

<!-- Page Heading -->
<h1 class="h3 mb-2 text-gray-800">Meditation & Comments</h1>
<div class="mb-4">
  <div class="pull-right">
    <form action="" method="" class="form-inline pull-right" id="searchForm">
      <div class="form-row">
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
    <h6 class="m-0 font-weight-bold text-primary">Meditation Details</h6>
  </div>
  <div class="card-body">
    <div class="table-responsive">
      <table class="table table-bordered table" >
        <tbody>
            <tr>
                <th scope="col">Type</th>
                <td scope="col" style="text-transform:capitalize;"><?php echo $meditation->type;?></td>
            </tr>
            <tr>
                <th scope="col">Title</th>
                <td scope="col"><?php echo $meditation->title;?></td>
            </tr>
            <tr>
                <th scope="col">Description</th>
                <td scope="col"><?php echo $meditation->description;?></td>
            </tr>
            <tr>
                <th scope="col">Status</th>
                <td scope="col"><?php echo $meditation->status;?></td>
            </tr>
            <tr>
                <th scope="col">Free</th>
                <td scope="col"><?php echo $meditation->is_free == 1 ? 'Yes':'No';?></td>
            </tr>
            <tr>
                <th scope="col">Views</th>
                <td scope="col"><?php echo $meditation->views;?></td>
            </tr>
            <?php if(!empty($user->id)){?>
                <tr>
                    <td>User</td>
                    <td>{{$user->first_name.' '.$user->last_name}}</td>
                </tr>
            <?php } ?>
            <?php if(!empty($meditation->category_id)){?>
                <tr>
                    <td>Category</td>
                    <td>{{$selected->title}}</td>
                </tr>
            <?php } ?>
            <?php $schedule = '';
            if($meditation->status == 'approved'){
                $schedule = date('m/d/Y H:i',strtotime($meditation->schedule));
             ?>
            <tr>
                <th scope="col">Posted on</th>
                <td scope="col"><?php echo date('F d,Y H:i',strtotime($meditation->schedule));?></td>
            </tr>

        <?php }else if($meditation->status == 'not_approved'){?>
                <th scope="col">Declined on</th>
                <td scope="col"><?php echo date('F d,Y H:i',strtotime($meditation->updated_at));?></td>
        <?php } ?>
            <?php if(!empty($meditation->pic)){?>
            <tr>
                <th scope="col">Pic</th>
                <td scope="col">
                      <img src="{{Storage::disk('public')->url('uploads/'.$meditation->pic)}}" width="200px;">
                </td>
            </tr>
        <?php } if(!empty($meditation->path)){?>
            <tr>
                <th scope="col">Audio</th>
                <td scope="col">
                   <video controls autoplay>
                    <source src="{{url('/readfile/'.$meditation->path)}}" type="video/mp4">
                    <source src="{{url('/readfile/'.$meditation->path)}}" type="video/ogg">
                    Your browser does not support the video tag.
                  </video>
                </td>
            </tr>
        <?php } ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
<div class="clearfix"></div>

<div class="card shadow mb-4">
  <div class="card-header py-3">
    <h6 class="m-0 font-weight-bold text-primary">Comments</h6>
  </div>
  <div class="card-body">
    <div class="table-responsive">
      <table class="table table-bordered table" >
        <thead class="thead-dark">
            <tr>
              <th scope="col">#</th>
              <th scope="col">Comment</th>
              <th scope="col">User Name</th>
              <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php $index =0; 
            foreach($comments as $comment){ ?>
            <tr>
                <th scope="row"><?php echo $index+1; ?></th>
                <td><?php echo $comment->pivot->comments; ?></td>
                <td><?php echo $comment->first_name.' '.$comment->last_name; ?></td>
                <td>
                    <a title="Delete record" href="{{ url('comments/delete/'.$meditation->id.'/'.$comment->id)}}" onclick="return confirm('Are you sure? you want to delete this record!')">
                        <i class="fa fa-trash"></i>
                    </a>
                </td>
            </tr>
            <?php } ?>                                
        </tbody>
      </table>
    {!! $comments->render() !!}
    </div>
  </div>
</div>
@endsection
@section('scripts')
<script>
</script>
@endsection