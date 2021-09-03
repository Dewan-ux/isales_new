<?php echo view('admin/_partials/header'); ?>
<?php echo view('admin/_partials/sidebar'); ?>

<div class="content-wrapper">
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">Tambah Berita</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">Edit Berita</li>
          </ol>
        </div>
      </div>
    </div>
  </div>

  <div class="content">
    <div class="container-fluid">
      <div class="row">
          <div class="col-md-12">
			<?= form_open_multipart(base_url('admin/berita/add')); ?>
              <div class="card">
                <div class="card-body">
                  <?php 
                  $errors = session()->getFlashdata('errors');
                  if(session()->getFlashdata('inputs')) {
                    $inputs = session()->getFlashdata('inputs');
                    $tags = session()->getFlashdata('tags');
                  }
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
                  <label for="judul">Judul Berita</label>
                  <?php
                    $email = [
                        'type'  => 'text',
                        'name'  => 'judul',
                        'id'    => 'judul',
                        'value' => isset($inputs['judul']) ? $inputs['judul'] : "",
                        'class' => 'form-control',
                        'required' => TRUE,
                        'placeholder' => 'Judul Berita'
                      ];
                      echo form_input($email);
                  ?>
              </div>
              <div class="form-group">
                   <label for="isi">Isi Berita</label>
                   <?php
                  echo form_textarea('isi',isset($inputs['isi']) ? $inputs['isi'] : "",['class'=>'ket','id'=>'isi','required']);
                  ?>
              </div>
				      <div class="form-group">
                   <label for="kategori">Kategori</label>
					    </div>
              <?php
                  $kategori1 = array(
                  'name' => 'kategori',
                  'value' => '1',
                  'id' => 'kategori1',
                  'class' => 'form-group',
                  'checked' => TRUE,
                );
                $kategori2 = array(
                  'name' => 'kategori',
                  'value' => '2',
                  'class' => 'form-group',
                );
                $kategori3 = array(
                  'name' => 'kategori',
                  'value' => '3',
                  'class' => 'form-group',
                );
                echo '<div clas="form-group">';
                echo '<div class="form-check form-check-inline">';
                echo form_radio($kategori1);
                echo '<label class="form-check-label" for="kategori1">25-30</label> &nbsp;';
                echo '</div>';
                echo '<div class="form-check form-check-inline">';
                echo form_radio($kategori2);
                echo '<label class="form-check-label" for="kategori2">30-45</label> &nbsp;';
                echo '</div>';
                echo '<div class="form-check form-check-inline">';
                echo form_radio($kategori3);
                echo '<label class="form-check-label" for="kategori3">>45</label> &nbsp;';
                echo '</div>';
                echo '</div>';
                          ?>
                  
				  <div class="form-group">
              <label for="id_tags">List Tags</label>
              <?php
                echo form_dropdown('id_tags', $tags, [], ['class'=>'form-control', 'required' => TRUE, 'id'=>'id_tags']);
              ?>
                  </div>
                    <div class="form-group">
                    <label for="foto">Foto</label>
                    <?php
                      $foto = [
                                      'name'  => 'foto',
                                      'id'    => 'foto',
                        'class' => 'form-control',
                        'required' => TRUE,
                        'accept' => 'image/*'
                      ];
                      echo form_upload($foto);
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