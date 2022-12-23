@extends('layouts.guide.app')
@section('content')
<div class="row small-spacing">
    <div class="col-lg-12 col-xs-12">
        <div class="box-content">
            <div class="pull-left">
                <h2 class="box-title">Edit Meditation</h2>
            </div>
            <div class="pull-right" style="margin-right: 40px;">
                <a href="{{ url('/meditations') }}" class="btn btn-danger btn-icon-split">
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
                            <form class="form-horizontal" method="post" action="{{url('/meditations/update/'.$md->id)}}" enctype="multipart/form-data">
                                @csrf
                                <div class="form-group">
                                    <label for="inputEmail3" class="col-sm-2 control-label">Title</label>
                                    <div class="col-sm-10">
                                        <input type="text" required="" value="<?php echo $md->title; ?>" name="title" class="form-control" id="title" placeholder="Title">
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
                                        <textarea required="" name="description" class="form-control" id="description" placeholder="description"><?php echo $md->description; ?></textarea>
                                        @error('description')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="name" class="col-sm-2 control-label">Audio</label>
                                    <div class="col-sm-10">
                                        <input accept="audio/*" type="file" placeholder="{{ __('audio') }}" class="form-control " id="audio" name="audio" >
                                        @error('audio')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                        <?php if(!empty($md->path)){?>
                                            <video controls autoplay>
                                                <source src="{{url('/readfile/'.$md->path)}}" type="video/mp4">
                                                <source src="{{url('/readfile/'.$md->path)}}" type="video/ogg">
                                                Your browser does not support the video tag.
                                              </video>
                                        <?php } ?>
                                        
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="name" class="col-sm-2 control-label">Pic</label>
                                    <div class="col-sm-10">
                                        <input accept="image/*" type="file" placeholder="{{ __('pic') }}" class="form-control " id="pic" name="pic" >
                                        @error('pic')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                        <?php if(!empty($md->pic)){?>
                                            <img src="{{Storage::disk('public')->url('uploads/'.$md->pic)}}" width="200px">
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
                                        <a href="{{ url('/meditations') }}" class="btn btn-danger btn-icon-split">
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