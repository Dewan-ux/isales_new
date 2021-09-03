<?php echo view('admin/_partials/header'); ?>
<?php echo view('admin/_partials/sidebar'); ?>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Daftar Recording</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Sales</li>
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
                           
                <!-- Date range -->
                <div class="form-group">
                  <label>Date range:</label>
                  <div class="input-group">
                     <input type="hidden" name="start_date" value=""/>
                     <input type="hidden" name="end_date" value=""/>
                    <div class="input-group-prepend">
                      <label class="input-group-text"><i class="fa fa-calendar"></i></label>
                    </div>
                    <input type="text" class="form-control float-right" id="reservation" onchange="initTable()">
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
                             <input type="hidden" name="recording_file" value=""/>
                            <div class="table-responsive">
                                <table id="recording" class="table table-bordered table-hovered">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama File</th>
                                            <th>Audio</th>
                                            <th>Size</th>
                                            <th>DATE＠</th>
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
   // Datapicker 
    $('#reservation').daterangepicker({
        opens: 'left'
    }, function(start, end, label){

        $('input[name="start_date"]').val(start.format('YYYY-MM-DD'));
        $('input[name="end_date"]').val(end.format('YYYY-MM-DD'));
    });

    $('input[name="start_date"]').val(($('#reservation').data('daterangepicker').startDate.format('YYYY-MM-DD')));
    $('input[name="end_date"]').val(($('#reservation').data('daterangepicker').endDate.format('YYYY-MM-DD')));

   // DataTable
initTable();
function initTable(aktif){
var filename = $("#title").text();
   var dataTable = $('#recording').DataTable({
     'processing': true,
     // 'serverSide': true,
     'paging': true,
     'searching': true, // Set false to Remove default Search Control
     'destroy': true,
     'lengthMenu': [[10, 20, 50, -1], [10, 20, 50, "All"]],
      "oLanguage": {
                // "sLengthMenu": "Tampilkan _MENU_ data per halaman",
                // "sSearch": "Pencarian: ",
                "sZeroRecords": "Maaf, tidak ada data yang ditemukan",
                "sInfo": "Menampilkan _START_ s/d _END_ dari _TOTAL_ data",
                "sInfoEmpty": "Menampilkan 0 s/d 0 dari 0 data",
                "sInfoFiltered": "(di filter dari _MAX_ total data)",
                "oPaginate": {
                    "sFirst": "<<",
                    "sLast": ">>",
                    "sPrevious": "◂",
                    "sNext": "▸"
               }
            },
   
     'ajax': {
       'url': '<?= base_url('admin/recording/play');?>',
       'type' : 'POST',
       'data': function(data){
          // Read values
          var start =  $('input[name="start_date"]').val();
          var end =  $('input[name="end_date"]').val();
          var recording =  $('input[name="recording_filter"]').val();

          // Append to data
          data.start_date = start;
          data.end_date = end;
          data.recording_filter = recording;

       }
     },
     
  });
};
</script>
