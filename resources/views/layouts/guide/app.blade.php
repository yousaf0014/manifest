<!doctype html>
<html lang="en">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
    <meta name="author" content="">
    <?php
        if (isset($description_for_layout)) {
            echo "<meta name='description' content='" . $description_for_layout . "' />";
        }
        if (isset($keywords_for_layout)) {
            echo "<meta name='keywords' content='" . $keywords_for_layout . "' />";
        }
        if(isset($meta_title_content)) { ?>
            <meta property="og:title" content="<?php echo $meta_title_content; ?>"/>
    <?php } ?>
    <title><?php echo !empty($title_for_layout) ? $title_for_layout:'Manifest'; ?></title>
    <link href="{!! asset('vendor/fontawesome-free/css/all.min.css')!!}" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link rel="icon" href="{!! asset('img/fav.png')!!}" type="image/gif" sizes="16x16">
    
    <!-- Custom styles for this template-->
    <link href="{!! asset('css/sb-admin-2.min.css')!!}" rel="stylesheet">
    <link rel="stylesheet" href="{!! asset('plugin/toastr/toastr.css')!!}">
    <style type="text/css">
      .invalid-feedback{
        display: block !important;
      }
    </style>
</head>
<body id="page-top">
    <input id="crf_token" value="{{ csrf_token() }}" type="hidden">
    <div id="wrapper">
    @include('layouts.guide.sidebar')

      <div id="content-wrapper" class="d-flex flex-column">
        <!-- Main Content -->
        <div id="content">
          @include('layouts.guide.header')
          <!-- Begin Page Content -->
          <div class="container-fluid">
            @yield('content')
          </div>
          <!-- /.container-fluid -->
        </div>
        <!-- Footer -->
        @include('layouts.guide.footer')      
      <!-- End of Footer -->

      </div>
    </div>  
    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
      <i class="fas fa-angle-up"></i>
    </a>
<!-- End wrapper-->

<!-- Bootstrap core JavaScript-->
<script src="{!! asset('vendor/jquery/jquery.min.js')!!}"></script>
<script src="{!! asset('vendor/bootstrap/js/bootstrap.bundle.min.js')!!}"></script>

<!-- Core plugin JavaScript-->
<script src="{!! asset('vendor/jquery-easing/jquery.easing.min.js')!!}"></script>

<!-- Custom scripts for all pages-->
<script src="{!! asset('js/sb-admin-2.min.js')!!}"></script>

<script src="{!! asset('plugin/toastr/toastr.min.js') !!}"></script>

<script type="text/javascript">
    $('document').ready(function(){
        <?php  if(session()->pull('flash_message_level') == 'success'){ ?>
            toastr["success"]("<?php echo session()->pull('flash_message'); ?>", "Success");
        <?php }else if(session()->pull('flash_message_level') == 'error'){ ?>
            toastr["error"]("<?php echo session()->pull('flash_message'); ?>", "Error");
        <?php }else if(session()->pull('flash_message_level') == 'warning'){?>
            toastr["warning"]("<?php echo session()->pull('flash_message'); ?>", "Warning");
        <?php }else if(session()->pull('flash_message_level') == 'info'){ ?>
            toastr["info"]("<?php echo session()->pull('flash_message'); ?>", "Info"); 
        <?php }?>
        toastr.options = {
          "closeButton": false,
          "debug": false,
          "newestOnTop": false,
          "progressBar": false,
          "rtl": false,
          "positionClass": "toast-top-right",
          "preventDuplicates": false,
          "onclick": null,
          "showDuration": 300,
          "hideDuration": 1000,
          "timeOut": 5000,
          "extendedTimeOut": 1000,
          "showEasing": "swing",
          "hideEasing": "linear",
          "showMethod": "fadeIn",
          "hideMethod": "fadeOut"
        }   
    });
</script>
@section('scripts')
@show
</body>
</html>
