@extends('layouts.guide.app')
@section('content')
<div class="row small-spacing">
    <div class="col-lg-12 col-xs-12">
        <div class="box-content">
            <div class="pull-left">
                <h2 class="box-title">Add Affirmation</h2>
            </div>
            <div class="pull-right" style="margin-right: 40px;">
                <a href="{{ url('/affirmations') }}" class="btn btn-danger btn-icon-split">
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
                        <form class="form-horizontal" method="post" action="{{url('/affirmations/store')}}" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label for="name" class="col-sm-2 control-label">Title</label>
                                <div class="col-sm-10">
                                    <input class="form-control @error('title') is-invalid @enderror" name="title" value="{{ old('title') }}" required autocomplete="title" autofocus type="text" placeholder="{{ __('Title') }}">
                                    @error('title')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="name" class="col-sm-2 control-label">Description</label>
                                <div class="col-sm-10">
                                    <textarea class="form-control @error('description') is-invalid @enderror" name="description" required autocomplete="description" autofocus placeholder="{{ __('Description') }}">{{ old('description') }}</textarea>
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
                                        <span class="text">Save</span>
                                    </button>

                                    <a href="{{ url('/affirmations') }}" class="btn btn-danger btn-icon-split">
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