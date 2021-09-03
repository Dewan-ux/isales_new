<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>iSales Admin</title>
    <style type="text/css"  id="debugbar_dynamic_style"></style>
    <link rel="stylesheet" href="<?php echo base_url('public/themes/dist'); ?>/css/adminlte.min.css">
    <link rel="stylesheet" href="<?php echo base_url('public/themes/dist'); ?>/css/adminlte.css">
    <link rel="stylesheet" href="<?php echo base_url('public/themes/plugins'); ?>/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="<?php echo base_url('public/themes/plugins'); ?>/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="<?php echo base_url('public/themes/plugins'); ?>/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="<?php echo base_url('public/themes/plugins'); ?>/summernote/summernote-bs4.css">
    <link rel="stylesheet" href="<?php echo base_url('public/themes/plugins'); ?>/daterangepicker/daterangepicker.css">
    <link rel="stylesheet" href="<?php echo base_url('public/themes/plugins'); ?>/sweetalert2/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="<?php echo base_url('public/themes/plugins'); ?>/toastr/toastr.min.css">
    <style>
    @media (min-width: 768px){
        .dl-horizontal dt {
            float: left;
            width: 160px;
            overflow: hidden;
            clear: left;
            text-align: right;
            margin-right: 15px;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
    }
    </style>
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">

<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
        </li>
    </ul>
    <ul class="navbar-nav ml-auto">
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
                <i class="far fa-user"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                <a href="<?php echo base_url('admin/logout'); ?>" class="dropdown-item logout">
                    Logout
                </a>
            </div>
        </li>
    </ul>
</nav>