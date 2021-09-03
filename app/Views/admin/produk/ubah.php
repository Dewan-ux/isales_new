<?php echo view('admin/_partials/header'); ?>
<?php echo view('admin/_partials/sidebar'); ?>

<div class="content-wrapper">
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">Edit Produk Perlindungan Kecelakaan</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">Edit Produk Perlindungan Kecelakaan</li>
          </ol>
        </div>
      </div>
    </div>
  </div>

  <div class="content">
    <div class="container-fluid">
      <div class="row">
          <div class="col-md-12">
			<?= form_open_multipart(base_url('admin/produksmile/update')); ?>
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
                  $id = [
                    'name'  => 'id',
                    'id'    => 'id',
                    'value' => isset($produk['id']) ? $produk['id'] : "",
                  ];
                  echo form_hidden('id',isset($produk['id']) ? $produk['id'] : "");
                ?>
                  <div class="form-group">
                      <label for="nama_produk">Nama Produk</label>
                      <?php
                        $email = [
                            'type'  => 'text',
                            'name'  => 'nama_produk',
                            'id'    => 'nama_produk',
                            'value' => isset($produk['nama_produk']) ? $produk['nama_produk'] : "",
                            'class' => 'form-control',
                            'placeholder' => 'Name'
                          ];
                          echo form_input($email);
                      ?>
                  </div>
                 
                </div>
                <div class="card-footer">
                    <a href="<?php echo base_url('admin/produk/index'); ?>" class="btn btn-outline-info">Back</a>
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
<script>
$('.ket').summernote({
  toolbar: [
    // [groupName, [list of button]]
    ['style', ['bold', 'italic', 'underline', 'clear']],
    ['font', ['strikethrough', 'superscript', 'subscript']],
    ['fontsize', ['fontsize']],
    ['color', ['color']],
    ['para', ['ul', 'ol', 'paragraph']],
    ['height', ['height']]
  ]
});
</script>