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
                    <h1 class="m-0 text-dark">Data Campaign</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Data Campaign</li>
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
                            Upload Campaign
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md">
                                    <?= form_open_multipart(base_url('admin/campaign/uploading')); ?>
                                        <div class="form-group">
                                        <?php
                                            echo form_dropdown('id_campaign', $campaign, [], ['class'=>'form-control', 'id'=>'id_campaign', 'required'=>'required']);
                                        ?>
                                            <div class="pt-1">
                                                <span class="badge badge-info">Pilih Campaign Jika Sudah Ada</span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="campaign">New Campaign</label>
                                            <?php
                                                $campaign = [
                                                    'type'  => 'text',
                                                    'name'  => 'campaign',
                                                    'id'    => 'campaign',
                                                    'value' =>  "",
                                                    'class' => 'form-control',
                                                    // 'disabled' => 'disabled',
                                                    'required'    => TRUE,
                                                    'placeholder' => 'Campaign Name'
                                                ];
                                                echo form_input($campaign);
                                            ?>
                                            <div class="pt-1">
                                                <span class="badge badge-info pt-1">Tambah Campaign Baru</span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="campaign_file">Batch Campaign</label>
                                            <input type="file" name="campaign_file" id="campaign_file" class="form-control" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel"/>
                                        </div>
                                        <div class="form-group">
                                            <a href="<?php echo base_url('admin/user'); ?>" class="btn btn-outline-info">Back</a>
                                            <button type="submit" class="btn btn-primary float-right">Simpan</button>
                                        </div>
                                    <?= form_close() ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            Log Upload Campaign
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
                                <table id="log_upload" class="table table-bordered table-hovered">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Campaign</th>
                                            <th>Total Upload Campaign</th>
                                            <th>Tanggal Upload </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach($log_upload_campaign as $key => $row){ ?>
                                        <tr>
                                            <td><?php echo ($key+1); ?></td>
                                            <td><?php echo $row['campaign']; ?></td>
                                            <td><?php echo $row['total']; ?></td>
                                            <td><?php echo $row['created_at']; ?></td>
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
    var table1;
    var table2;
    initTable();
    function initTable(){
        table1 = $('#log_share').DataTable({
            'searching': true,
            'lengthChange': false,
            'sort': false,
        });
        table2 = $('#log_upload').DataTable({
            'searching': true,
            'lengthChange': false,
            'sort': false,
        });
    }

    $('#id_campaign').on('change', function(e){
        var val = $(this).val();
        if(val == ''){
            $("#campaign").removeAttr('disabled');
            $('#campaign').attr('required','required');   
        } else {
            $('#campaign').attr('disabled','disabled');   
            $("#campaign").val('');
            $("#campaign").removeAttr('required');
        }
    });

    $('#campaign').on('keyup', function(e){
        e.preventDefault();
        if($(this).val().length > 0){
            $('#id_campaign').attr('disabled','disabled');   
            $("#id_campaign").val('');
            $("#id_campaign").removeAttr('required');
        } else {
            $('#id_campaign').attr('required','required');   
            $("#id_campaign").val('');
            $("#id_campaign").removeAttr('disabled');
        }
    });
});

</script>
