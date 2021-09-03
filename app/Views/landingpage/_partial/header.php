<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags-->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Colorlib Templates">
    <meta name="author" content="Colorlib">
    <meta name="keywords" content="Colorlib Templates">
    <title>
     <?php if (!empty($page_title)) echo $page_title;?>
    </title>

<!--===============================================================================================-->
<link rel="icon" type="image/png" href="/favicon.ico"/>
<!--===============================================================================================-->
<link rel="stylesheet" href="<?php echo base_url('/public/themes/landingpage/vendor'); ?>/mdi-font/css/material-design-iconic-font.min.css">
<!--===============================================================================================-->
<link rel="stylesheet" type="text/css" href="<?php echo base_url("public/themes/landingpage/vendor/font-awesome-4.7/css/font-awesome.min.css"); ?>">
<!--===============================================================================================-->
<link href="https://fonts.googleapis.com/css?family=Roboto:100,100i,300,300i,400,400i,500,500i,700,700i,900,900i" rel="stylesheet">
<!--===============================================================================================-->
<link rel="stylesheet" type="text/css" href="<?php echo base_url("public/themes/landingpage/vendor/select2/select2.min.css"); ?>">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="<?php echo base_url("public/themes/landingpage/vendor/datepicker/daterangepicker.css"); ?>">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="<?php echo base_url("public/themes/landingpage/css/main.css"); ?>">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="<?php echo base_url("public/themes/vendors/bootstrap/dist/css/bootstrap.css"); ?>">
<!--===============================================================================================-->
<style>
        div {
      padding: 0;
      margin: 0;
      outline: none;
      font-family: Roboto, Arial, sans-serif;
      font-size: 14px;
      color: #666;
      line-height: 22px;
      }
      input:hover::placeholder {
      color: #0099ff;
      }
      input[type=radio], input[type=checkbox]  {
      opacity: 0;
      position: absolute;
      }
      .question-answer {
        position: relative;
      }
      label.radio {
      position: relative;
      display: inline-block;
      margin: 5px 20px 15px 0;
      cursor: pointer;
      }
      .question span {
      margin-left: 30px;
      }
      label.radio:before {
      content: "";
      position: absolute;
      left: 0;
      width: 17px;
      height: 17px;
      border-radius: 30%;
      border: 2px solid #ccc;
      }
      input[type=checkbox]:checked  + label:before, input[type=radio]:checked + label:before, label.radio:hover:before {
      border: 2px solid #00b8cf;
      }
      label.radio:after {
      content: "";
      border-radius: inherit;
      position: absolute;
      top: 1px;
      left: 1px;
      border: 0.51em solid #00b8cf;
      opacity: 0;
      }
      input[type=checkbox]:checked + label:after, input[type=radio]:checked + label:after {
      opacity: 1;
      }

    </style>
</head>