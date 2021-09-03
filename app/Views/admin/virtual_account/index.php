<?php echo view('admin/_partials/header'); ?>
<?php echo view('admin/_partials/sidebar'); ?>
<style>
    form .upload-container {
        position: relative;
        width: 100%;
        height: 100%;
        overflow: hidden;
        cursor: pointer;
    }

    .file-upload {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        opacity: 0;
        cursor: pointer;
        z-index: 10;
    }

    .btn-upload {
        display: block;
        position: absolute;
        width: 100%;
        height: 100%;
        cursor: pointer;
    }

</style>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Data Virtual Account</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Virtual Account</li>
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
                        <div class="card-header">
                            <!-- <div class="row">
                                <div class="col-md-12">
                                    <form action="<?php //echo base_url('admin/virtual_account/upload') ?>" method="POST" enctype="multipart/form-data">
                                        <div class="upload-container">
                                            <input type="file" class="file-upload"/>
                                            <button class="btn btn-app btn-lg btn-block btn-upload" > 
                                                <i class="nav-icon fas fa-upload"></i>Import Batch Virtual Account 
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div> -->
                            <div class="container-fluid">
                                <div class="row">
                                    <!-- <div class="col-md-6">
                                        <a href="<?php echo base_url('admin/excel/export') ?>" class="btn btn-app btn-lg btn-block">
                                        <i class="nav-icon fas fa-download"></i>Export DPR
                                        </a>
                                    </div> -->
                                    <div class="col-md">
			                            <?= form_open_multipart(base_url('admin/virtual_account/upload')); ?>
                                            <div class="upload-container">
                                                <input type="file" name="va_file" class="file-upload" onchange="form.submit()" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel"/>
                                                <button class="btn btn-app btn-lg btn-block" > 
                                                    <i class="nav-icon fas fa-upload"></i><h5>Import Batch Virtual Account</h5> 
                                                </button>
                                            </div>
                                        <?= form_close() ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <?php
                            if(!empty(session()->getFlashdata('success'))){ ?>
                            <div class="alert alert-success">
                                <?php echo session()->getFlashdata('success');?>
                            </div>
                            <?php } ?>
                            <?php if(!empty(session()->getFlashdata('info'))){ ?>
                            <div class="alert alert-info">
                                <?php echo session()->getFlashdata('info');?>
                            </div>
                            <?php } ?>
                            <?php if(!empty(session()->getFlashdata('warning'))){ ?>
                            <div class="alert alert-warning">
                                <?php echo session()->getFlashdata('warning');?>
                            </div>
                            <?php } ?>
                            <div class="table-responsive">
                                <table id="produk" class="table table-bordered table-hovered">
                                    <thead>
                                        <tr>
                                            <th>Batch Number</th>
                                            <th>Type SPAJ</th>
                                            <th>Virtual Account</th>
                                            <th>Nomor SPAJ </th>
                                            <th>Available</th>
                                            <th>Tanggal Generate</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach($virtual_account as $key => $row){ ?>
                                        <tr>
                                            <td><?php echo $row['batch_number']; ?></td>
                                            <td><?php echo $row['type_spaj']; ?></td>
                                            <td><?php echo $row['virtual_account']; ?></td>
                                            <td><?php echo $row['no_spaj']; ?></td>
                                            <td><?php echo $row['used']; ?></td>
                                            <td><?php echo $row['generated_at']; ?></td>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
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
//checklist
jQuery(function($) {
    var table;
    initTable();
    function initTable(){
        table = $('#produk').DataTable({
            'searching': true,
            'lengthChange': false,
            'sort': false,
        });
    }
});

</script>
