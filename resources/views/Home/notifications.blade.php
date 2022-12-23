<?php if(!empty($notifications[0])){
    foreach($notifications as $noti){ ?>
<a class="dropdown-item d-flex align-items-center" href="{{url('/notification_detail/meditation/'.$noti->id)}}">
    <div class="mr-3">
        <div class="icon-circle bg-primary">
            <i class="fas fa-file-alt text-white"></i>
        </div>
    </div>
    <div>
        <div class="small text-gray-500"><?php echo date('F d,Y',strtotime($noti->created_at)); ?></div>
        <span class="font-weight-bold">{{$noti->title}}</span>
    </div>
</a>
<?php }
}else{ ?>
    <a class="dropdown-item d-flex align-items-center" href="#">
    <div class="mr-3">
        <div class="icon-circle bg-primary">
            <i class="fas fa-file-alt text-white"></i>
        </div>
    </div>
    <div>
        <div class="small text-gray-500">&nbsp</div>
        <span class="font-weight-bold">{{__('no_new_notifications')}}</span>
    </div>
</a>
<?php } ?>