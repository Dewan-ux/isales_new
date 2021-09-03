<?php echo view('admin/_partials/header'); ?>
<?php echo view('admin/_partials/sidebar'); ?>

<div class="content-wrapper">
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">Edit Premi Perlindungan Kecelakaan</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">Edit Perlindungan Kecelakaan</li>
          </ol>
        </div>
      </div>
    </div>
  </div>

  <div class="content">
    <div class="container-fluid">
      <div class="row">
          <div class="col-md-12">
			<?= form_open_multipart(base_url('admin/premi/updated')); ?>
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
                    'value' => isset($premi['id']) ? $premi['id'] : "",
                  ];
                  echo form_hidden('id',isset($premi['id']) ? $premi['id'] : "");
                ?>
                  <div class="form-group">
                      <label for="nama">Nominal</label>
                      <?php
                        $email = [
                            'type'  => 'text',
                            'name'  => 'nominal',
                            'id'    => 'nominal',
                            'value' => isset($premi['nominal']) ? $premi['nominal'] : "",
                            'class' => 'form-control',
                            'placeholder' => 'Name'
                          ];
                          echo form_input($email);
                      ?>
                  </div>
                  <div class="form-group">
                    <label for="satuan">Satuan</label>
                    <?php
                        $satuan = [
                            'Bulanan' => "Bulanan",
                            'Tahunan' => "Tahunan"
                        ];

                        echo form_dropdown('satuan', $satuan, isset($premi['satuan']) ? $premi['satuan'] : "", ['class'=>'form-control', 'id'=>'satuan']);
                      ?>
                  </div>
                  <div class="form-group">
                    <label for="id_produk_pa_car">List Produk</label>
                    <?php
                        echo form_dropdown('id_produk_pa_car', $produk, isset($premi['id_produk_pa_car']) ?  $premi['id_produk_pa_car'] : "",
                         ['class'=>'form-control', 'id'=>'id_produk_pa_car']);
                      ?>
                  </div>
                  <div class="form-group">
                      <label for="nama">Manfaat</label>
                      <?php
                        $email = [
                            'type'  => 'text',
                            'name'  => 'manfaat',
                            'id'    => 'manfaat',
                            'value' => isset($premi['manfaat']) ? $premi['manfaat'] : "",
                            'class' => 'form-control',
                            'placeholder' => 'Manfaat'
                          ];
                          echo form_input($email);
                      ?>
                  </div>
                  <div class="form-group">
                      <label for="nama">Uang Pertanggungan</label>
                      <?php
                        $email = [
                            'type'  => 'text',
                            'name'  => 'up',
                            'id'    => 'up',
                            'value' => isset($premi['up']) ? $premi['up'] : "",
                            'class' => 'form-control',
                            'placeholder' => 'Uang Pertanggungan'
                          ];
                          echo form_input($email);
                      ?>
                  </div>
                </div>
                <div class="card-footer">
                    <a href="<?php echo base_url('admin/premi/index'); ?>" class="btn btn-outline-info">Back</a>
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