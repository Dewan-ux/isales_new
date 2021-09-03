<?php echo view('admin/_partials/header'); ?>
<?php echo view('admin/_partials/sidebar'); ?>

<div class="content-wrapper">
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">Tambah Premi Perlindungan Kecelakaan</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">Tambah Premi Perlindungan Kecelakaan</li>
          </ol>
        </div>
      </div>
    </div>
  </div>
  <div class="content">
    <div class="container-fluid">
      <div class="row">
          <div class="col-md-12">
			<?= form_open_multipart(base_url('admin/premi/tambah')); ?>
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
          <div class="form-group">
              <label for="nominal">Nominal</label>
              <?php
                $email = [
                    'type'        => 'text',
                    'name'        => 'nominal',
                    'id'          => 'nominal',
                    'value'       => isset($inputs['nominal']) ? $inputs['nominal'] : "",
                    'class'       => 'form-control',
                    'required'    => TRUE,
                    'placeholder' => 'Nominal'
                  ];
                  echo form_input($email);
              ?>
          </div>
				  <div class="form-group">
              <label for="satuan">Satuan</label>
              <?php
                $satuan = [
                    '' => "Pilih Satuan",
                    'Bulanan' => "Bulanan",
                    'Tahunan' => "Tahunan",
                ];
                echo form_dropdown('satuan', $satuan, [], ['class'=>'form-control', 'required' => 'required','id'=>'satuan']);
              ?>
          </div>
				
	        <div class="form-group">
                <label for="id_produk_pa_car">List Produk</label>
                <?php
                  echo form_dropdown('id_produk_pa_car', $produk, [], ['class'=>'form-control','required'    => TRUE, 'id'=>'id_produk_pa_car']);
                
                ?>
          </div>

          <div class="form-group">
              <label for="nominal">Manfaat</label>
              <?php
                $email = [
                    'type'        => 'text',
                    'name'        => 'manfaat',
                    'id'          => 'manfaat',
                    'value'       => isset($inputs['manfaat']) ? $inputs['manfaat'] : "",
                    'class'       => 'form-control',
                    'required'    => TRUE,
                    'placeholder' => 'Manfaat'
                  ];
                  echo form_input($email);
              ?>
          </div>
          
           <div class="form-group">
              <label for="nominal">Uang Pertanggungan</label>
              <?php
                $email = [
                    'type'        => 'text',
                    'name'        => 'up',
                    'id'          => 'up',
                    'value'       => isset($inputs['up']) ? $inputs['up'] : "",
                    'class'       => 'form-control',
                    'required'    => TRUE,
                    'placeholder' => 'Uang Pertanggungan'
                  ];
                  echo form_input($email);
              ?>
          </div>
                </div>
                <div class="card-footer">
                    <a href="<?php echo base_url('admin/premi'); ?>" class="btn btn-outline-info">Back</a>
                    <button type="submit" class="btn btn-primary float-right">Simpan</button>
                </div>
              </div>
            <?= form_close() ?>
          </div>
      </div>
    </div>
  </div>
</div>
<?php echo view('admin/_partials/footer'); ?>
<script type="text/javascript">
  const Toast = Swal.mixin({
  toast: true,
  position: 'top-end',
  showConfirmButton: false,
  timer: 3000,
  timerProgressBar: true,
  didOpen: (toast) => {
    toast.addEventListener('mouseenter', Swal.stopTimer)
    toast.addEventListener('mouseleave', Swal.resumeTimer)
  }
})

Toast.fire({
  icon: 'success',
  title: 'Signed in successfully'
})
</script>