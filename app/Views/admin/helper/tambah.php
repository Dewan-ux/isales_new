<?php echo view('admin/_partials/header'); ?>
<?php echo view('admin/_partials/sidebar'); ?>

<div class="content-wrapper">
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">Tambah Pdf Script sales</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">Edit Pdf Script sales</li>
          </ol>
        </div>
      </div>
    </div>
  </div>
  <div class="content">
    <div class="container-fluid">
      <div class="row">
          <div class="col-md-12">
			<?= form_open_multipart(base_url('admin/helper/add')); ?>
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
                    <label for="pdf">Pdf Script Sales</label>
                    <?php
                      $pdf = [
                              'name'     => 'pdf',
                              'id'       => 'pdf',
                              'class'    => 'form-control',
                              'required' => TRUE,
                              'accept'   => 'application/pdf,application/vnd.ms-excel/*'
                              ];
                      echo form_upload($pdf);
                    ?>
					</div>
          <div class="form-group">
              <label for="pdf">Pdf Script Handling Objection</label>
              <?php
                $pdf = [
                        'name'     => 'pdf_ho',
                        'id'       => 'pdf_ho',
                        'class'    => 'form-control',
                        'required' => TRUE,
                        'accept'   => 'application/pdf,application/vnd.ms-excel/*'
                        ];
                echo form_upload($pdf);
              ?>
					</div>
          <div class="form-group">
                    <label for="pdf">Pdf FAQ</label>
                    <?php
                      $pdf = [
                              'name'     => 'pdf_faq',
                              'id'       => 'pdf_faq',
                              'class'    => 'form-control',
                              'required' => TRUE,
                              'accept'   => 'application/pdf,application/vnd.ms-excel/*'
                              ];
                      echo form_upload($pdf);
                    ?>
					</div>

          <div class="form-group">
                    <label for="pdf">Pdf Plan</label>
                    <?php
                      $pdf = [
                              'name'     => 'pdf_plan',
                              'id'       => 'pdf_plan',
                              'class'    => 'form-control',
                              'required' => TRUE,
                              'accept'   => 'application/pdf,application/vnd.ms-excel/*'
                              ];
                      echo form_upload($pdf);
                    ?>
          </div>

          <div class="form-group">
                    <label for="pdf">Pdf Dumb Basic</label>
                    <?php
                      $pdf = [
                              'name'     => 'pdf_dumb',
                              'id'       => 'pdf_dumb',
                              'class'    => 'form-control',
                              'required' => TRUE,
                              'accept'   => 'application/pdf,application/vnd.ms-excel/*'
                              ];
                      echo form_upload($pdf);
                    ?>
					</div>


          <div class="form-group">
                    <label for="pdf">Pdf Kantor Cabang</label>
                    <?php
                      $pdf = [
                              'name'     => 'pdf_kantor',
                              'id'       => 'pdf_kantor',
                              'class'    => 'form-control',
                              'required' => TRUE,
                              'accept'   => 'application/pdf,application/vnd.ms-excel/*'
                              ];
                      echo form_upload($pdf);
                    ?>
          </div>

                <div class="card-footer">
                    <a href="<?php echo base_url('admin/helper'); ?>" class="btn btn-outline-info">Back</a>
                    <button type="submit" class="btn btn-primary float-right">Simpan</button>
                </div>
              </div>
            <?= form_close() ?>
          </div>
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
