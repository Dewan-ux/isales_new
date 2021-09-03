<?php echo view('admin/_partials/header'); ?>
<?php echo view('admin/_partials/sidebar'); ?>

<div class="content-wrapper">
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">Edit Berita</h1>
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
			<?= form_open_multipart(base_url('admin/berita/update')); ?>
              <div class="card">
                <div class="card-body">
                  <?php 
                  $errors = session()->getFlashdata('errors');
                  if(session()->getFlashdata('inputs')) {
                    $berita = session()->getFlashdata('inputs');
                    $tags = session()->getFlashdata('tags');
                  }
                  if(!empty($errors)){ 
                    
                    ?>
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
                    'value' => isset($berita['id']) ? $berita['id'] : "",
                  ];
                  echo form_hidden('id',isset($berita['id']) ? $berita['id'] : "");
                ?>
                  <div class="form-group">
                      <label for="judul">Judul</label>
                      <?php
                        $email = [
                            'type'  => 'text',
                            'name'  => 'judul',
                            'id'    => 'judul',
                            'value' => isset($berita['judul']) ? $berita['judul'] : "",
                            'class' => 'form-control',
                            'placeholder' => 'Berita'
                          ];
                          echo form_input($email);
                      ?>
                  </div>
                  <div class="form-group">
                   <label for="isi">Isi Berita</label>
                   <?php
                  echo form_textarea('isi',isset($berita['isi']) ? $berita['isi'] : "",['class'=>'ket','id'=>'isi','required']);
                  ?>
                   </div>
  
                  <div class="form-group">
                    <label for="id_tags">List Tags</label>
                    <?php
                        echo form_dropdown('id_tags', $tags, isset($berita['id_tags']) ?  $berita['id_tags'] : "",
                         ['class'=>'form-control', 'id'=>'id_tags']);
                      ?>
                  </div>
                  <div class="form-group">
                      <label for="kategori">Kategori</label>
                      <?php
					  	$kategori1 = array(
							'name' => 'kategori',
							'value' => '1',
							'id' => 'kategori1',
							'class' => 'form-group',
							'checked' => $berita['kategori'] == '1' ? TRUE : FALSE,
            );
						$kategori2 = array(
							'name' => 'kategori',
							'value' => '2',
							'class' => 'form-group',
							'checked' => $berita['kategori'] == '2' ? TRUE : FALSE,
						);
						$kategori3 = array(
							'name' => 'kategori',
							'value' => '3',
							'class' => 'form-group',
							'checked' => $berita['kategori'] == '3' ? TRUE : FALSE,
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
                <div class="card-footer">
                    <a href="<?php echo base_url('admin/premi'); ?>" class="btn btn-outline-info">Back</a>
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