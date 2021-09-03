<?php echo view('admin/_partials/header'); ?>
<?php echo view('admin/_partials/sidebar'); ?>

<div class="content-wrapper">
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">Edit User</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">Edit User</li>
          </ol>
        </div>
      </div>
    </div>
  </div>

  <div class="content">
    <div class="container-fluid">
      <div class="row">
          <div class="col-md-12">
			<?= form_open_multipart(base_url('admin/user/update')); ?>
              <div class="card">
                <div class="card-body">
                  <?php 
                  $errors = session()->getFlashdata('errors');
                  $inputs = session()->getFlashdata('inputs');
                  if(!empty($errors)){ ?>
                  <div class="alert alert-danger" role="alert">
                    Whoops! Ada kesalahan saat input data, yaitu:
                    <ul>
                    <?php foreach ($errors as $error) : ?>
                        <li><?= esc($error) ?></li>
                    <?php endforeach ?>
                    </ul>
                  </div>
                  <?php } ?>
                  <?php
                    echo form_hidden('id_user', isset($user['id_user']) ? $user['id_user'] : "");
                    echo form_hidden('id', isset($user['id']) ? $user['id'] : "");
                ?>
                  <div class="form-group">
                      <label for="nama">Nama</label>
                      <?php
                        $email = [
                            'type'  => 'text',
                            'name'  => 'nama',
                            'id'    => 'nama',
                            'value' => isset($user['nama']) ? $user['nama'] : "",
                            'class' => 'form-control',
                            'placeholder' => 'Name'
                          ];
                          echo form_input($email);
                      ?>
                  </div>
                  <div class="form-group">
                    <label for="jk">Jenis Kelamin</label>
                    <?php
                        $jk = [
                            'L' => "Laki - Laki",
                            'P' => "Perempuan"
                        ];
                        echo form_dropdown('jk', $jk, $user['jk'], ['class'=>'form-control', 'id'=>'jk']);
                      ?>
                  </div>
                  <div class="form-group">
                      <label for="username">Username</label>
                      <?php
                        $email = [
                            'type'  => 'text',
                            'name'  => 'username',
                            'id'    => 'username',
                            'value' => isset($user['username']) ? $user['username'] : "",
                            'class' => 'form-control',
                            'placeholder' => 'Username'
                          ];
                          echo form_input($email);
                      ?>
                  </div>
                  <div class="form-group">
                      <label for="password">Password</label>
                      <?php
                        $password = [
                            'type'  => 'password',
                            'name'  => 'password',
                            'id'    => 'password',
                            'value' =>  "",
                            'class' => 'form-control',
                            'placeholder' => 'Password'
                          ];
                          echo form_input($password);
                      ?>
                      <span>Kosongkan jika tidak diubah</span>
                  </div>
                  <div class="form-group">
                      <label for="">Role</label>
                      <?php
                        $roles = [
                            '0' => "Pilih Role",
                            '2' => "Manager/Leader",
                            '3' => "Telesales",
                            '4' => "QA",
                            '5' => "CAR Admin"
                        ];
                        echo form_dropdown('role', $roles, $user['role'], ['class'=>'form-control', 'id'=>'role']);
                      ?>
                  </div>
                  <div class="form-group">
                    <label for="foto">Foto</label>
                    <?php
                      $foto = [
                                      'name'  => 'foto',
                                      'id'    => 'foto',
                        'class' => 'form-control',
                        'accept' => 'image/*'
                      ];
                      echo form_upload($foto);
                    ?>
					        </div>
                </div>
                <div class="card-footer">
                    <a href="<?php echo base_url('admin/user'); ?>" class="btn btn-outline-info">Back</a>
                    <button type="submit" class="btn btn-primary float-right">Update</button>
                </div>
              </div>
            <?= form_close() ?>
          </div>
      </div>
    </div>
  </div>

</div>
<?php echo view('admin/_partials/footer'); ?>