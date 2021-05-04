<!doctype html>
<html class="no-js" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title><?= $page_title; ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- style -->
    <?php $this->load->view('layouts/doctor/_style'); ?>
    <!-- end style -->
</head>

<body class="body-bg" data-url="<?= base_url(); ?>">
    <!--[if lt IE 8]>
            <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->
    <!-- preloader area start -->
    <div id="preloader">
        <div class="loader"></div>
    </div>
    <!-- preloader area end -->
    <!-- main wrapper start -->
    <div class="horizontal-main-wrapper">

        <!-- main header area start -->
        <?php $this->load->view('layouts/doctor/_header'); ?>
        <!-- main header area end -->

        <!-- header area start (navbar) -->
        <?php $this->load->view('layouts/doctor/_navbar'); ?>
        <!-- header area end -->

        <!-- page title area end -->
        <?php $this->load->view($page); ?>
        <!-- main content area end -->


        <!-- start modal -->
        <?php $this->load->view('layouts/doctor/_modal'); ?>
        <!-- modal end -->

        <!-- footer area start-->
        <?php $this->load->view('layouts/doctor/_footer'); ?>
        <!-- footer area end-->

    </div>
    <!-- main wrapper start -->

    <!-- offset area start -->
    <?php $this->load->view('layouts/doctor/_offset'); ?>
    <!-- offset area end -->

    <!-- script -->
    <?php $this->load->view('layouts/doctor/_script'); ?>
    <!-- end script -->
</body>

</html>