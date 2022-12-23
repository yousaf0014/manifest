<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
    <meta name="description" content="">
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
    <title><?php echo !empty($title_for_layout) ? $title_for_layout:'Login'; ?></title>
    <link href="{!! asset('vendor/fontawesome-free/css/all.min.css')!!}" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <!-- Custom styles for this template-->
    <link href="{!! asset('css/sb-admin-2.min.css')!!}" rel="stylesheet">
    <link rel="icon" href="{!! asset('img/fav.png')!!}" type="image/gif" sizes="16x16">
</head>
<body class="bg-gradient-primary">
    <div class="container">
        @yield('content')  
    </div>    
    <script src="{!! asset('vendor/jquery/jquery.min.js')!!}"></script>
    <script src="{!! asset('vendor/bootstrap/js/bootstrap.bundle.min.js')!!}"></script>
    <script src="{!! asset('vendor/jquery-easing/jquery.easing.min.js')!!}"></script>
    <script src="{!! asset('js/sb-admin-2.min.js')!!}"></script>
    <script type="text/javascript">
    </script>
    @section('scripts')
    @show
</body>
</html>
