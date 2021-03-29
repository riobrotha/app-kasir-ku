<!doctype html>
<html class="no-js" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Login - KasirKu</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- style.php -->
    <?php $this->load->view('layouts/_style'); ?>
    <!-- end _style.php -->
</head>

<body>
   
    <!-- preloader area start -->
    <div id="preloader">
        <div class="loader"></div>
    </div>
    <!-- preloader area end -->
    
    <!-- login area start -->
    <?php $this->load->view($page); ?>
    <!-- login area end -->

    <!-- _script.php -->
    <?php $this->load->view('layouts/_script'); ?>
    <!-- end _script.php -->
</body>

</html>