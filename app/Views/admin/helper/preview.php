<?php echo view('admin/_partials/header'); ?>
<?php echo view('admin/_partials/sidebar'); ?>

<div class="content-wrapper">
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">Script Sales</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">Script Sales</li>
          </ol>
        </div>
      </div>
    </div>
  </div>

  <div class="content">
    <div class="container-fluid">
      <div class="row">
          <div class="col-md-12">
              <div class="card">
                <div class="card-body">
                <div class="container-fluid">
                <div class="row">

                  <?php if(!empty($sales['pdf'])){?>
                  <div class="col-md-6">
                  <div class="card-header">
                    <h3 class="card-title">Script PDF Sales</h3>
                  </div>
                  <iframe
                    src='data:application/pdf;base64,<?=$sales['pdf']?>'
                    width="500"
                    height="678">
                    <p>This browser does not support PDF!</p>
                  </iframe>
                  </div>
                  <?php } ?>

                  <?php if(!empty($sales['pdf_ho'])){?>
                  <div class="col-md-6">
                  <div class="card-header">
                    <h3 class="card-title">Script PDF Handling Objection</h3>
                  </div>
                  <iframe
                    src='data:application/pdf;base64,<?=$sales['pdf_ho']?>'
                    width="500"
                    height="678">
                    <p>This browser does not support PDF!</p>
                  </iframe>
                  </div>
                  <?php } ?>

                  <?php if(!empty($sales['pdf_faq'])){?>
                  <div class="col-md-6">
                  <div class="card-header">
                    <h3 class="card-title">Script PDF FAQ</h3>
                  </div>
                  <iframe
                    src='data:application/pdf;base64,<?=$sales['pdf_faq']?>'
                    width="500"
                    height="678">
                  <p>This browser does not support PDF!</p>
                  </iframe>
                  </div>
                  <?php } ?>

                  <?php if(!empty($sales['pdf_plan'])){?>
                  <div class="col-md-6">
                  <div class="card-header">
                    <h3 class="card-title">PDF plan</h3>
                  </div>
                  <iframe
                    src='data:application/pdf;base64,<?=$sales['pdf_plan']?>'
                    width="500"
                    height="678">
                  <p>This browser does not support PDF!</p>
                  </iframe>
                  </div>
                  <?php } ?>

                  <?php if(!empty($sales['pdf_dumb'])){?>
                  <div class="col-md-6">
                  <div class="card-header">
                    <h3 class="card-title">PDF dumb</h3>
                  </div>
                  <iframe
                    src='data:application/pdf;base64,<?=$sales['pdf_dumb']?>'
                    width="500"
                    height="678">
                  <p>This browser does not support PDF!</p>
                  </iframe>
                  </div>
                  <?php } ?>

                  <?php if(!empty($sales['pdf_kantor'])){?>
                  <div class="col-md-6">
                  <div class="card-header">
                    <h3 class="card-title">PDF Kantor Cabang</h3>
                  </div>
                  <iframe
                    src='data:application/pdf;base64,<?=$sales['pdf_kantor']?>'
                    width="500"
                    height="678">
                  <p>This browser does not support PDF!</p>
                  </iframe>
                  </div>
                  <?php } ?>
                </div>

                <div class="card-footer">
                    <a href="<?php echo base_url('admin/helper'); ?>" class="btn btn-outline-info">Back</a>
                </div>
              </div>
          </div>
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
