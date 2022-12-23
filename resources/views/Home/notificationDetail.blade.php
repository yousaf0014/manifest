@extends('layouts.admin.app')
@section('content')
<style type="text/css">
    .redc{
        border-color: red; 
    }
</style>
<!-- Page Heading -->
<h1 class="h3 mb-2 text-gray-800">Meditation Details</h1>
<p class="mb-4">
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
                <th scope="col">Type</th>
                <td scope="col" style="text-transform:capitalize;"><?php echo $record->type;?></td>
            </tr>
            <tr>
                <th scope="col">Title</th>
                <td scope="col"><?php echo $record->title;?></td>
            </tr>
            <tr>
                <th scope="col">Description</th>
                <td scope="col"><?php echo $record->description;?></td>
            </tr>
            <tr>
                <th scope="col">Status</th>
                <td scope="col"><?php echo $record->status;?></td>
            </tr>
            <tr>
                <th scope="col">Free</th>
                <td scope="col"><?php echo $record->is_free == 1 ? 'Yes':'No';?></td>
            </tr>
            <tr>
                <th scope="col">Views</th>
                <td scope="col"><?php echo $record->views;?></td>
            </tr>
            <?php if(!empty($user->id)){?>
                <tr>
                    <td>User</td>
                    <td>{{$user->first_name.' '.$user->last_name}}</td>
                </tr>
            <?php } ?>
            <?php if(!empty($record->category_id)){?>
                <tr>
                    <td>Category</td>
                    <td>{{$selected->title}}</td>
                </tr>
            <?php } ?>
            <?php $schedule = '';
            if($record->status == 'approved'){
                $schedule = date('m/d/Y H:i',strtotime($record->schedule));
             ?>
            <tr>
                <th scope="col">Posted on</th>
                <td scope="col"><?php echo date('F d,Y H:i',strtotime($record->schedule));?></td>
            </tr>

        <?php }else if($record->status == 'not_approved'){?>
                <th scope="col">Declined on</th>
                <td scope="col"><?php echo date('F d,Y H:i',strtotime($record->updated_at));?></td>
        <?php } ?>
            <?php if(!empty($record->pic)){?>
            <tr>
                <th scope="col">Pic</th>
                <td scope="col">
                      <img src="{{Storage::disk('public')->url('uploads/'.$record->pic)}}" width="200px;">
                </td>
            </tr>
        <?php } if(!empty($record->path)){?>
            <tr>
                <th scope="col">Audio</th>
                <td scope="col">
                   <video controls autoplay>
                    <source src="{{url('/readfile/'.$record->path)}}" type="video/mp4">
                    <source src="{{url('/readfile/'.$record->path)}}" type="video/ogg">
                    Your browser does not support the video tag.
                  </video>
                </td>
            </tr>
        <?php } ?>
            <tr>
                <td>&nbsp;</td>
                <td>
                    <form method="post" action="{{ url('/meditation_approve/'.$record->id) }}" id="acceptform">
                        @csrf
                        <div class="form-group">
                            <label for="inputEmail3" class="col-sm-2 control-label">Schedule</label>
                            <div class="col-sm-10">
                                <div class="input-append date form_datetime">

                                    <input name="schedule" value="<?php echo $schedule;?>" required="" type="text" required="" class="datepic btn-icon-split" value="" readonly>
                                    <span class="add-on"><i class="fa fa-calendar "></i></span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputEmail3" class="col-sm-2 control-label">Schedule</label>
                            <div class="col-sm-10">
                                <select name="category_id" required="" class="" id="category_id">
                                    <option value="">--Category--</option>
                                    <?php foreach($categorires as $cat){?>
                                        <option value="{{$cat->id}}" <?php echo $record->category_id == $cat->id? 'selected="selected"':'';?>>{{$cat->title}}</option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div> 
                        <div class="form-group">
                            <label for="inputEmail3" class="col-sm-2 control-label">Free</label>
                            <div class="col-sm-10">
                                <input type="checkbox" name="is_free" value="1" <?php echo $record->is_free == 1 ? 'checked="checked"':'';?>>
                            </div>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary btn-icon-split">                                        
                                <span class="icon text-white-50">
                                    <i class="fa fa-check"></i>
                                </span>
                                <span class="text">Approve</span>
                            </button>

                            <a href="{{ url('/meditation_decline/'.$record->id) }}" class="btn btn-danger btn-icon-split">
                                <span class="icon text-white-50">
                                    <i class="fa fa-times"></i>
                                </span>
                                <span class="text">Decline</span>
                            </a>
                        </div>      
                    </form>
                </td>
            </tr>
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection
@section('scripts')
<link href="{!! asset('css/bootstrap-datetimepicker.css')!!}" rel="stylesheet" media="screen">
<script type="text/javascript" src="{!! asset('js/bootstrap-datetimepicker.js')!!}" charset="UTF-8"></script>
<script type="text/javascript">
        function submitForm(){
            if($('.datepic').val()!=''){
                $('#acceptform').submit().addClass('invalid-feedback');
            }else{
                $('.datepic').addClass('redc');
                alert('Please provide the schedule');
            }
        }
        $(".datepic").datetimepicker({
        format: "mm/dd/yyyy hh:ii",
        autoclose: true,
        todayBtn: true,
        pickerPosition: "bottom-left"
    });
</script>
@endsection