<?php echo view('admin/_partials/header'); ?>
<?php echo view('admin/_partials/sidebar'); ?>

<div class="content-wrapper">
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
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
                            Data log campaign
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="produk" class="table row-border">
                                    <tbody>
                                    <tr>
                                    <td style="text-align: left;">Nama leader</td>
                                    <td><?=$campaign['nama']?></td>
                                    </tr>
                                    <tr>
                                    <td style="text-align: left;">Nama campaign</td>
                                    <td><?=$campaign['campaign']?></td>
                                    </tr>
                                    <td style="text-align: left;">Sisa Total Campaign</td>
                                    <td><?=$sisa?></td>
                                    </tr>
                                    <tr>
                                    <td style="text-align: left;">Total campaign yang berhasil dishare </td>
                                    <td><?=$campaign['total']?></td>
                                    </tr>
                                    <td style="text-align: left;">No telepon yang sudah dishare</td>
                                    <td>
                                    <ol>
                                    <?php if (!empty(json_decode($campaign['log_gagal']))){foreach(json_decode($campaign['log_gagal']) as $key){ ?>
                                     <li><?php echo $key; ?></li>
                                      <?php }} ?>
                                    </ol>
                                      </td>
                                    </tr>
                                    <td style="text-align: left;">No telepon yang berhasil dishare </td>
                                    <td>
                                    <ol>
                                    <?php if (!empty(json_decode($campaign['log_kirim'])))foreach(json_decode($campaign['log_kirim']) as $key){ ?>
                                     <li><?php echo $key; ?></li>
                                      <?php } ?>
                                    </ol>
                                      </td>
                                    </tr>
                                   
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
  // $("#produk").DataTable({
  //     "responsive": true,
  //     "autoWidth": false,
  //   });
</script>
