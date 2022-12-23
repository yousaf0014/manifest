@extends('layouts.admin.app')
@section('content')

<!-- Page Heading -->
<h1 class="h3 mb-2 text-gray-800">Topics</h1>
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
          <div class="form-group p-1">
              <a href="{{ url('topics/create') }}" class="btn btn-primary btn-icon-split">
                  <span class="icon text-white-50">
                      <i class="fa fa-plus-circle"></i>
                  </span>
                  <span class="text">Add Topic</span>
              </a>
          </div>
      </div>
    </form>
  </div>
</div>
<div class="clearfix"></div>
<div class="card shadow mb-4">
  <div class="card-header py-3">
    <h6 class="m-0 font-weight-bold text-primary">Topic List</h6>
  </div>
  <div class="card-body">
    <div class="table-responsive">
      <table class="table table-bordered table" >
        <thead class="thead-dark">
            <tr>
              <th scope="col">#</th>
              <th scope="col">Title</th>
              <th scope="col">Description</th>
              <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($topics as $index=>$top){ ?>
            <tr>
                <th scope="row"><?php echo $index+1; ?></th>
                <td><?php echo $top->title; ?></td>
                <td><?php echo $top->description; ?></td>
                <td>
                    <a title="Edit" href="{{ url('topics/edit/'.$top->id)}}">
                        <i class="fa fa-edit"></i>
                    </a>&nbsp;
                    <a title="View" href="{{ url('topics/show/'.$top->id)}}">
                        <i class="fa fa-eye"></i>
                    </a>&nbsp;
                    <a title="Delete record" href="{{ url('topics/delete/'.$top->id)}}" onclick="return confirm('Are you sure? you want to delete this record!')">
                        <i class="fa fa-trash"></i>
                    </a>
                </td>
            </tr>
            <?php } ?>                                
        </tbody>
      </table>
    {!! $topics->render() !!}
    </div>
  </div>
</div>
@endsection
@section('scripts')
<script>
</script>
@endsection