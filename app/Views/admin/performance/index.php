<?php echo view('admin/_partials/header'); ?>
<?php echo view('admin/_partials/sidebar'); ?>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Data Performance</h1>
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
                        <div class="card-header">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-md-4">
                            <input type="hidden" name="start_date" value=""/>
                            <input type="hidden" name="end_date" value=""/>
                                <button type="submit" class="btn btn-costum btn-lg btn-block" id="btndpr">
                                <i class="nav-icon fas fa-download"></i>Daily Production Calltracking
                                </a>
                            </div>
                            <div class="col-md-4">
                            <button type="button" class="btn btn-costum btn-lg btn-block" id="btnapr">
                            <i class="nav-icon fas fa-download"></i>Agent Production Report
                            </button>
                            <input type="hidden" name="start_date" value=""/>
                            <input type="hidden" name="end_date" value=""/>
                    </div>
                    <div class="col-md-4">
                            <button type="submit" class="btn btn-costum btn-lg btn-block" id="btnreporting">
                            <i class="nav-icon fas fa-download"></i>Submission Report
                            </button>
                            <input type="hidden" name="start_date" value=""/>
                            <input type="hidden" name="end_date" value=""/>
                    </div>
                    </div>
                    </div>
                </div>
                <div class="card-body">
                <!-- Date range -->
             <div class="row">
                <div class="col">
                            <div class="form-group">
                              <label>Date range:</label>
                              <div class="input-group">
                                <div class="input-group-prepend">
                                  <span class="input-group-text">
                                    <i class="far fa-calendar-alt"></i>
                                  </span>
                                </div>
                                <input type="text" class="form-control float-right" id="reservation">
                              </div>
                              </div>
                    
                </div>
                    <div class="col">
                    <div class="form-group">
                          <label for="exampleSelectBorder">Data source:</label>
                          
                           <?php echo form_dropdown('id_campaign', $campaign, [], 
                            ['class'=>'form-control', 'id'=>'id_campaign', 'required'=>'required']); ?>
                    </div>
                    </div>
                    <div class="col">
                    <div class="form-group">
                          <label for="exampleSelectBorder">Data Produk:</label>
                          
                          <?php echo form_dropdown('id_produk', $produk, [], 
                            ['class'=>'form-control', 'id'=>'id_produk', 'required'=>'required']); ?>
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
                                            <th width="50px"><input type="checkbox" id="all"></th>
                                            <th>No</th>
                                            <th>Nama</th>
                                            <th>Jumlah Call</th>
                                            <th>Jumlah Leads</th>
                                            <th>Jumlah Case</th>
                                            <th>Call Follow Up</th>
                                            <th>Take Up Rate</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach($dashboard as $key => $row){ ?>
                                        <tr>
                                        <td><input type="checkbox" value="<?=$row['id']?>" class="singlechkbox" name="id"/></td>
                                            <td><?php echo $key + 1; ?></td>
                                            <td><?php echo $row['nama']; ?></td>
                                            <td><?php echo $row['jumlah_call']; ?></td>
                                            <td><?php echo $row['jumlah_leads']; ?></td>
                                            <td><?php echo $row['jumlah_case']; ?></td>
                                            <td><?php echo $row['call_follow_up']; ?></td>
                                            <td><?php echo $row['takeup_rate']; ?></td>
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

    $('#reservation').daterangepicker({
        opens: 'left'
    }, function(start, end, label){

        $('input[name="start_date"]').val(start.format('YYYY-MM-DD'));
        $('input[name="end_date"]').val(end.format('YYYY-MM-DD'));
    });
    $('input[name="start_date"]').val(($('#reservation').data('daterangepicker').startDate.format('YYYY-MM-DD')));
    $('input[name="end_date"]').val(($('#reservation').data('daterangepicker').endDate.format('YYYY-MM-DD')));

    $('#btnapr').click(function (e){
        var SlectedList = [];
        $("input.singlechkbox:checked").each(function() {
            SlectedList.push($(this).val());
        });
        var start_date =  $('input[name="start_date"]').val();
        var end_date =  $('input[name="end_date"]').val();
        var id_campaign = $('select[name="id_campaign"]').val();
        var id_produk = $('select[name="id_produk"]').val();
        var data = {
            id_campaign:id_campaign,
            id_produk:id_produk,
            tsr_ids:SlectedList, 
            start_date:start_date, 
            end_date:end_date
        }
        exportData("<?= base_url('report/export/apr');?>", data);
    });

    $('#btndpr').click(function (e){
        var start_date =  $('input[name="start_date"]').val();
        var end_date =  $('input[name="end_date"]').val();
        var id_campaign = $('select[name="id_campaign"]').val();
        var id_produk = $('select[name="id_produk"]').val();
        var data = {
            id_campaign:id_campaign,
            start_date:start_date, 
            id_produk:id_produk,    
            end_date:end_date
        }
        exportData("<?= base_url('report/export/dpr');?>", data);
    });

    $('#btnreporting').click(function (e){
        var start_date =  $('input[name="start_date"]').val();
        var end_date =  $('input[name="end_date"]').val();
        var data = {
            start_date:start_date, 
            end_date:end_date
        }
        exportData("<?= base_url('report/export/reporting');?>", data);
    });


    function exportData(url, data){
        var { value : foo = false } = Swal.fire({
            title: 'Sedang Unduh...!',
            html: 'Tunggu Sebentar Yaa...!',
            timerProgressBar: true,

            didOpen: () => {
                Swal.showLoading()
                $.ajax({
                    url: url,
                    method: "post",
                    dataType: 'json',
                    data: data,
                    success: function(result){
                        if(result.error)
                        {
                            Swal.fire(
                                'Error',
                                result.message,
                                'error'
                            );
                        } else {
                            success = true;
                            var $a = $("<a>");
                            $a.attr("href",result.file);
                            $("body").append($a);
                            $a.attr("download",result.filename+"."+result.type);
                            $a[0].click();
                            $a.remove();
                            Swal.close();

                            Swal.fire(
                                'Sukses',
                                'Download Sukses',
                                'success'
                            );
                        }
                        
                    }, done : function(result) {
                        resolve();
                    }
                });
            }
        });

        if(foo){
            Swal.fire('Downloaded Success');
        }
    }
    $('body').on('click', '#all', function() {
        $('.singlechkbox').prop('checked', this.checked);
    });


});
//filterautoselect
$(document).ready(function() {

});
</script>
