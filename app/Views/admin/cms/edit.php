<?php echo view('admin/_partials/header'); ?>
<?php echo view('admin/_partials/sidebar'); ?>

<div class="content-wrapper">
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">Edit Script Sales</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">Edit Script Sales</li>
          </ol>
        </div>
      </div>
    </div>
  </div>

  <div class="content">
    <div class="container-fluid">
      <div class="row">
          <div class="col-md-12">
			<?= form_open_multipart(base_url('admin/landingpage/cms/update')); ?>
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
                    'value' => isset($sales['id']) ? $sales['id'] : "",
                  ];
                  echo form_hidden('id',isset($sales['id']) ? $sales['id'] : "");
                ?>
                   <div class="form-group">
                    <label for="pdf">Foto Brosur</label>
                    <?php
                      $foto_brosur = [
                              'name'  => 'foto_brosur',
                              'id'    => 'foto_brosur',
                              'class' => 'form-control',
                              'accept' => 'image/*'
                              ];
                      echo form_upload($foto_brosur);

                      if(isset($cms['foto_brosur']) || !empty($cms['foto_brosur'])){ ?>
                        <span>Foto Brosur saat Ini<br>
                          <img src="data:image/jpeg;base64, <?= $cms['foto_brosur']; ?>" alt="" height="250px"></a>
                        </span>
                      <?php } ?>
                    
                  </div>
                  
                  <div class="form-group">
                    <label for="pdf">Foto Banner</label>
                    <?php
                      $foto_banner = [
                              'name'  => 'foto_banner',
                              'id'    => 'foto_banner',
                              'class' => 'form-control',
                              'accept' => 'image/*'
                              ];
                      echo form_upload($foto_banner);
                      
                      if(isset($cms['foto_brosur']) || !empty($cms['foto_brosur'])){ ?>
                        <span>Foto Banner saat Ini<br>
                          <img src="data:image/jpeg;base64, <?= $cms['foto_banner']; ?>" alt="" height="250px"></a>
                        </span>
                    <?php } ?>

				        	</div>
                  
                </div>
                <div class="card-footer">
                    <a href="<?php echo base_url('admin/landingpage/cms'); ?>" class="btn btn-outline-info">Back</a>
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