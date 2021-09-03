<?php echo view('admin/_partials/header'); ?>
<?php echo view('admin/_partials/sidebar'); ?>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Data Leader Group</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Leader Group</li>
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

                      <!-- select -->
                      <!-- <div class="form-group">
                        <label>Custom Select</label>
                        <select class="form-control" id="selectByPosition">
                        <option>--Select--</option>
                          <option value="programming">Programming</option>
                          <option value="javascript">Senior Javascript Developer</option>
                          <option value="accountant">Accaountant</option>
                        </select>
                    </div> -->

                <div class="form-group">
              <label for="">List Tags</label>
              <?php
                echo form_dropdown('leader_id', $leader, [], ['class'=>'form-control leader']);
              ?>
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
                                <table id="example" class="table table-bordered table-hovered">
                                    <thead>
                                        <tr>
                                            <th width="50px"><input type="checkbox" id="all"></th>
                                            <th width="20px">No</th>
                                            <th>Nama Telesales</th>
                                            <th>Nama Leader</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!--  -->
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
//table

//checklist
    jQuery(function($) {
    $('body').on('click', '#all', function() {
        $('.singlechkbox').prop('checked', this.checked);
    });

    $('body').on('click', '.singlechkbox', function() {
        var tsr_id = $(this).val();
        var leader_id = $('.leader').val();
        if(!$(this).is(':checked'))
        {
            leader_id = 0;
        }
        console.log(leader_id);
        $.ajax({
            url: "<?= base_url('admin/leadergroup/update');?>",
            method: "post",
            data: {tsr_id:tsr_id, leader_id:leader_id},
            success: function(result){
                tempAlert(result.message);
            },error: function(XMLHttpRequest, textStatus, errorThrown) { 
                var result = "Status: " + textStatus + "<br>Error: " + errorThrown;
                tempAlert(result , true);
            }
        });
        if($('.singlechkbox').length == $('.singlechkbox:checked').length) {
            $('#all').prop('checked', true);
        } else {
            $("#all").prop('checked', false);
        }
    });
    var table;
    initTable();
    function initTable(){
        table = $('#example').DataTable({
            'searching': true,
            'lengthChange': true,
            'sort': false,
            'processing': true,
            // 'serverSide': true,
            'serverMethod': 'post',
            'ajax': {
                'url':"<?= base_url('admin/leadergroup/tsr');?>",
                type : "post"
            }
        });
    }
    $('.leader').on('change', function(){
        var leader_id = $(this).val();
        $.ajax({
            url: "<?= base_url('admin/leadergroup/leaderid');?>",
            method: 'post',
            data: {leader_id:leader_id},
            success: function(result){
                table.destroy();
                initTable();
            }
        });
    });
});

function tempAlert(msg, err = false)
{
    var el = document.createElement("div");
    if(err){
        el.setAttribute("style","min-width: 250px;margin-left: -125px;background-color: #F35;color: #fff;text-align: center;border-radius: 2px;padding: 16px;position: fixed;z-index: 1;left: 50%;bottom: 15%;font-size: 17px;font-weight: bold;");
    }else{
        el.setAttribute("style","min-width: 250px;margin-left: -125px;background-color: #333;color: #fff;text-align: center;border-radius: 2px;padding: 16px;position: fixed;z-index: 1;left: 50%;bottom: 15%;font-size: 17px;font-weight: bold;");
    }
    el.innerHTML = msg;
    setTimeout(function(){
        el.parentNode.removeChild(el);
    }, 4000);
    document.body.appendChild(el);
}

//filterautoselect
$(document).ready(function() {

});
</script>
