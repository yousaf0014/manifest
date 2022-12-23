@extends('layouts.admin.app')
@section('content')
<div class="row small-spacing">
    <div class="col-lg-12 col-xs-12">
        <div class="box-content">
            <div class="pull-left">
                <h2 class="box-title">Edit Category</h2>
            </div>
            <div class="pull-right" style="margin-right: 40px;">
                <a href="{{ url('/categories') }}" class="btn btn-danger btn-icon-split">
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
                            <form class="form-horizontal" method="post" action="{{url('/categories/update/'.$category->id)}}" enctype="multipart/form-data">
                                @csrf
                                <div class="form-group">
                                    <label for="name" class="col-sm-2 control-label">Topics</label>
                                    <div class="col-sm-10">
                                        <select name="topics[]" required="" class="selectpicker form-control @error('title') is-invalid @enderror" multiple data-live-search="true">
                                            <option value="" disabled selected>Choose your option</option>
                                            <?php foreach($topics as $topID=>$topName){?>
                                                <option <?php echo  in_array($topID,$selectedArr) ? 'selected':'';?> value="<?php echo $topID;?>"><?php echo $topName;?></option>
                                            <?php } ?>
                                        </select>

                                        @error('topics')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="inputEmail3" class="col-sm-2 control-label">Title</label>
                                    <div class="col-sm-10">
                                        <input type="text" required="" value="<?php echo $category->title; ?>" name="title" class="form-control" id="title" placeholder="title">
                                        @error('title')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="inputEmail3" class="col-sm-2 control-label">Description</label>
                                    <div class="col-sm-10">
                                        <textarea  name="description" class="form-control" id="description" placeholder="description"><?php echo $category->description; ?></textarea>
                                        @error('description')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
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
                                        <a href="{{ url('/categories') }}" class="btn btn-danger btn-icon-split">
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
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>
<script type="text/javascript">

</script>
@endsection