<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Masuk - Admin iSales</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="<?php echo base_url('public/themes/plugins'); ?>/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="<?php echo base_url('public/themes/dist'); ?>/css/adminlte.min.css">
</head>
<body class="hold-transition login-page">
  <div class="login-box">
    <div class="login-logo">
      <a href="<?php echo base_url('auth/login'); ?>"><b>ISALES</b>ADMIN</a>
    </div>
    <div class="card">
      <div class="card-body login-card-body">
        <p class="login-box-msg">Sign in to start your session</p>
        <?php $errors = session()->getFlashdata('errors');
        if(!empty($errors)){ ?>
          <div class="alert alert-danger" role="alert">
            Whoops! Ada kesalahan saat input data, yaitu:
            <ul>
              <?php foreach ($errors as $error) { ?>
              <li><?php echo esc($error); ?></li>
              <?php } ?>
            </ul>
          </div>
        <?php } ?>
        <?php 
        $error_login = session()->getFlashdata('error_login');
        if(!empty($error_login)){ ?>
          <div class="row">
            <div class="col-md-12">
              <div class="alert alert-danger text-center">
                <?php echo $error_login; ?>
              </div>
            </div>
          </div>
        <?php } ?>
        <?php if($success_register = session()->getFlashdata('success_register')){ ?>
          <div class="row">
            <div class="col-md-12">
              <div class="alert alert-success text-center">
                <?php echo $success_register; ?>
              </div>
            </div>
          </div>
        <?php 
        } 
        $inputs = session()->getFlashdata('inputs'); 
         echo form_open_multipart(base_url('admin/doLogin'));
        ?>
        <div class="input-group mb-3">
          <?php
          $email = [
            'type'  => 'text',
            'name'  => 'username',
            'id'    => 'username',
            'value' => isset($inputs['username']) ? $inputs['username'] : "",
            'class' => 'form-control',
            'placeholder' => 'Username'
          ];
          echo form_input($email); 
          ?>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-user"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <?php
          $password = [
            'type'  => 'password',
            'name'  => 'password',
            'id'    => 'password',
            'value' => isset($inputs['password']) ? $inputs['password'] : "",
            'class' => 'form-control',
            'placeholder' => 'Password'
          ];
          echo form_input($password); 
          ?>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-8">
          </div>
          <div class="col-4">
            <button type="submit" class="btn btn-primary btn-block btn-flat">Sign In</button>
          </div>
        </div>
      <?php echo form_close(); ?>
      </div>
    </div>
  </div>
<script src="<?php echo base_url('public/themes/plugins'); ?>/jquery/jquery.min.js"></script>
<script src="<?php echo base_url('public/themes/plugins'); ?>/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="<?php echo base_url('public/themes/dist'); ?>/js/adminlte.min.js"></script>
</body>
</html>