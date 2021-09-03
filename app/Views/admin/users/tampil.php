<?php echo view('admin/_partials/header'); ?>
<?php echo view('admin/_partials/sidebar'); ?>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Users</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Users</li>
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
                            List User
                            <a href="<?php echo base_url('admin/user/create'); ?>" class="btn btn-primary float-right">Tambah</a>
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
                                <table id="user" class="table table-bordered table-hovered">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Foto</th>
                                            <th>Nama</th>
                                            <th>Username</th>
                                            <th>JK</th>
                                            <th>Status Masuk</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($users as $key => $row){ ?>
                                        <tr>
                                            <td><?php echo $key+1; ?></td>
                                            <td><img src="data:image/jpeg;base64,<?php echo $row['foto'] ?>" width="50px"></td>
                                            <td><?php echo $row['nama']; ?></td>
                                            <td><?php echo $row['username']; ?></td>
                                            <td><?php echo $row['jk']; ?></td>
                                            <td><center><?php echo $row['logged_in'] == '1' ? '<span class="badge badge-success">Yes</span>' : '<span class="badge badge-danger">No</span>' ?></center></td>
                                            <td>
                                                <div class="btn-group">
                                                    <a href="<?php echo base_url('admin/user/edit/'.$row['id']); ?>" class="btn btn-sm btn-success">
                                                        <i class="fa fa-edit"></i>
                                                    </a>
                                                    <a href="<?php echo base_url('admin/user/delete/'.$row['id']); ?>" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus user <?php echo $row['nama']?>?');">
                                                        <i class="fa fa-trash-alt"></i>
                                                    </a>
                                                    <button data-target="#forceModal" data-id="<?= $row['id']?>" data-nama="<?= $row['nama']?>" class="btn btn-sm btn-warning force_logout" >
                                                        <i class="fas fa-sign-out-alt"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                            <?= form_open(base_url('admin/user/forcelogout')); ?>
                                <div class="modal" id="forceModal" tabindex="-1" role="dialog" role="dialog" aria-labelledby="forceLogoutModal" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Force Logout</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <input type="hidden" name="id" id="id_user" />
                                                <div class="form-group">
                                                    <label for="confirm_password">Silahkan isi konfirmasi password Anda</label>
                                                    <input required type="password" class="form-control" name="confirm_password" id="confirm_password" />
                                                </div>
                                                <!-- <div class="form-group">
                                                    <label for="confirm_password">Silahkan Recaptcha</label>
                                                    <div class="g-recaptcha" data-sitekey="6LcNKFEaAAAAAHPzuTHcZblUCFgZAowVDBf9ZPvD" data-callback="verifyRecaptchaCallback" data-expired-callback="expiredRecaptchaCallback"></div>
                                                        <input class="form-control d-none" data-recaptcha="true" data-error="Please complete the Captcha">
                                                        <div class="help-block with-errors"></div>
                                                    </div>
                                                </div> -->
                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-primary">Konfirmasi</button>
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?= form_close() ?>
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
    initTable();
  function initTable(aktif){
    var filename = $("#title").text();
    table_categories = $("#categories").DataTable({
        "autoWidth": true,
        "responsive": true,
        "paging": true,
        "processing": true,
        "serverSide": true,
        "ajax" : {
          url : "users/get_users.php",
          type : "post",
          data:{aktif:aktif}
        },
        dom: 'Bfrtip',
        buttons:  [{
                extend: 'pdfHtml5',
                title: filename,
                exportOptions: {
                    columns: "thead th:not(.noExport)"
                }
            },{
                extend: 'excelHtml5',
                title: filename,
                exportOptions: {
                    columns: "thead th:not(.noExport)"
                }
            }, {
                extend: 'csvHtml5',
                title: filename,
                exportOptions: {
                    columns: "thead th:not(.noExport)"
                }
            }
        ]
    });
  }
  $("#user").DataTable({
      "responsive": true,
      "autoWidth": false,
    });
    $(document).on('click','.force_logout', (function(event){
        event.stopPropagation();
        event.stopImmediatePropagation();
        var nama = $(this).data('nama');
        console.log(nama);
        if(confirm('Apakah Anda yakin ingin mengeluarkan user '+nama)){
            $('#id_user').val($(this).data('id'));
            $('#forceModal').modal('show');
        }
    }));
    
</script>
