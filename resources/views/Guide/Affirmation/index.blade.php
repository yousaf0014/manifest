@extends('layouts.guide.app')
@section('content')

<!-- Page Heading -->
<h1 class="h3 mb-2 text-gray-800">Affirmations</h1>
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
          <div class="form-group p-1">
              <a href="{{ url('affirmations/create') }}" class="btn btn-primary btn-icon-split">
                  <span class="icon text-white-50">
                      <i class="fa fa-plus-circle"></i>
                  </span>
                  <span class="text">Add Affirmation</span>
              </a>
          </div>
      </div>
    </form>
  </div>
</div>
<div class="clearfix"></div>
<div class="card shadow mb-4">
  <div class="card-header py-3">
    <h6 class="m-0 font-weight-bold text-primary">Affirmation List</h6>
  </div>
  <div class="card-body">
    <div class="table-responsive">
      <table class="table table-bordered table" >
        <thead class="thead-dark">
            <tr>
              <th scope="col">#</th>
              <th scope="col">Title</th>
              <th scope="col">Description</th>
              <th scope="col">Status</th>
              <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($affirmations as $index=>$md){ ?>
            <tr>
                <th scope="row"><?php echo $index+1; ?></th>
                <td><?php echo $md->title; ?></td>
                <td><?php echo $md->description; ?></td>
                <td><?php echo $md->status; ?></td>
                <td>
                    <a title="Edit" href="{{ url('affirmations/edit/'.$md->id)}}">
                        <i class="fa fa-edit"></i>
                    </a>&nbsp;
                    <a title="View" href="{{ url('affirmations/show/'.$md->id)}}">
                        <i class="fa fa-eye"></i>
                    </a>&nbsp;
                    <a title="Delete record" href="{{ url('affirmations/delete/'.$md->id)}}" onclick="return confirm('Are you sure? you want to delete this record!')">
                        <i class="fa fa-trash"></i>
                    </a>
                </td>
            </tr>
            <?php } ?>                                
        </tbody>
      </table>
    {!! $affirmations->render() !!}
    </div>
  </div>
</div>
@endsection
@section('scripts')
<script>
</script>
@endsection