<?php echo view('admin/_partials/header'); ?>
<?php echo view('admin/_partials/sidebar'); ?>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Daftar SPAJ</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">SPAJ</li>
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
                            List SPAJ
                            <!-- <a href="<?php echo base_url('admin/sales/create'); ?>" class="btn btn-primary float-right">Tambah</a> -->
							<a href="<?php echo base_url('/eksport'); ?>" class="btn btn-danger">Eksport</a>
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
                                            <th>No</th>
                                            <th>No Proposal</th>
                                            <th>Jenis Asuransi</th>
                                            <th>Nama</th>
                                            <th>Telepon</th>
                                            <th>Nama Produk</th>
                                            <th>Premi</th>
                                            <th>Telesales</th>
                                            <th>Tanggal</th>
                                            <th>action</th>
                                        </tr>
                                    </thead>
                                    <tbody>  
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
initTable();
  function initTable(aktif){
    var filename = $("#title").text();
    table_categories = $("#produk").DataTable({
        "autoWidth": true,
        "responsive": true,
        "paging": true,
        "processing": true,
        "lengthMenu": [[10, 20, 50, -1], [10, 20, 50, "All"]],
        "oLanguage": {
                // "sLengthMenu": "Tampilkan _MENU_ data per halaman",
                // "sSearch": "Pencarian: ",
                "sZeroRecords": "Maaf, tidak ada data yang ditemukan",
                "sInfo": "Menampilkan _START_ s/d _END_ dari _TOTAL_ data",
                "sInfoEmpty": "Menampilkan 0 s/d 0 dari 0 data",
                "sInfoFiltered": "(di filter dari _MAX_ total data)",
               //  "oPaginate": {
               //      "sFirst": "<<",
               //      "sLast": ">>",
               //      "sPrevious": "<",
               //      "sNext": ">"
               // }
            },                

        // "serverSide": true,
        "ajax" : {
          url : "<?= base_url('admin/spaj/get');?>",
          type : "post",
        },

        // dom: 'Bfrtip',
        // buttons:  [{
        //         extend: 'pdfHtml5',
        //         title: filename,
        //         exportOptions: {
        //             columns: "thead th:not(.noExport)"
        //         }
        //     },{
        //         extend: 'excelHtml5',
        //         title: filename,
        //         exportOptions: {
        //             columns: "thead th:not(.noExport)"
        //         }
        //     }, {
        //         extend: 'csvHtml5',
        //         title: filename,
        //         exportOptions: {
        //             columns: "thead th:not(.noExport)"
        //         }
        //     }
        // ]
    });
  }
  // $("#produk").DataTable({
  //     "responsive": true,
  //     "autoWidth": false,
  //   });
</script>
