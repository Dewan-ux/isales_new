<?php echo view('admin/_partials/header'); ?>
<?php echo view('admin/_partials/sidebar'); ?>

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
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            Share Campaign
                        </div>
                        <div class="card-body">
                            <div class="row pb-3">
                                <div class="col-sm">
                                    <span class="btn btn-info disabled">Available <span class="badge badge-danger available"><?= $available?></span></span>
                                </div>
                                <div class="col-sm-6">
                                    <?php
                                        echo form_dropdown('id_campaign', $campaign, [], ['class'=>'form-control total', 
                                        'id'=>'id_campaign', 'required'=>'required', 'onchange'=>'updateTot()']);
                                    ?>
                                </div>
                                <div class="col-sm">
                                    <!-- <button id="split" data-total="<?= $available?>" class="btn btn-info float-right">Split <span class="badge badge-danger available"><?= $available?></span></button> -->
                                    <button id="split" class="btn btn-info float-right">Split <span class="badge badge-danger available" id="totalCamp">...</span></button>
                                </div>
                            </div>   
                            <div class="table-responsive">
                                <table id="leaders" class="table table-bordered table-hovered">
                                    <thead>
                                        <tr>
                                            <th width="5px">No</th>
                                            <th width="250px">Leader</th>
                                            <th>Share</th>
                                            <th>Action </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach($leaders as $key => $row){ ?>
                                        <tr>
                                            <td>
                                                <?php echo ($key+1); ?>
                                            </td>
                                            <td><?php echo $row['nama']; ?></td>
                                            <td>
                                                <?php
                                                $attr = ['name="myform"'];
                                                // echo form_open(base_url('admin/campaign/sharing'), $attr); ?>

                                                <input type="hidden" class="form-control" name="id_login" id="id_<?=$key?>" min="0" value="<?=$row['id']?>" />
                                                <input type="hidden" class="form-control" name="nama_tmp" id="nama_tmp_<?=$key?>" min="0" value="<?=$row['nama']?>" />
                                                <input type="number" class="form-control limit" required name="limit" id="limit_<?=$key?>" min="0" value="0" />
                                                <div class="pt-1">
                                                    <span class="badge badge-danger"></span>
                                                </div>
                                                <?php // echo form_close() ?>
                                            </td>
                                            <td>
                                                <button data-total="<?= $available?>" class="btn btn-danger float-right share"><i class="fas fa-share"></i></button>
                                            </td>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan=4><button class="btn btn-block btn-lg btn-primary share-all">Share All <i class="fas fa-share"></i></button></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            Log Share Campaign
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
                                <table id="log_share" class="table table-bordered table-hovered">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Leader</th>
                                            <th>Total Share</th>
                                            <th>Nama Campaign</th>
                                            <th>Detail Campaign</th>
                                            <th>Tanggal Share</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach($log_share_campaign as $key => $row){ ?>
                                        <tr>
                                            <td><?php echo ($key+1); ?></td>
                                            <td><?php echo $row['nama']; ?></td>
                                            <td><?php echo $row['total']; ?></td>
                                            <td><?php echo $campaign[$row['id_campaign']]; ?></td>
                                            <td><a href="<?php echo base_url('admin/campaign/preview/'.$row['id']); ?>" class="btn btn-sm btn-primary">
                                                        <i class="fa fa-file"></i>
                                                    </a>
                                            </td>
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
const data = <?=json_encode($camplst)?>;
console.log(data);

function updateTot() {
  var x = document.getElementById("id_campaign").value;
  var sel = data[x];

  console.log(sel+"-"+x);
  if(isNaN(sel)){
    document.getElementById("totalCamp").innerHTML = "&nbsp;";
  }else{
    document.getElementById("totalCamp").innerHTML = sel;
  }
}

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
        table2 = $('#leaders').DataTable({
            'searching': true,
            'lengthChange': false,
            'sort': false,
        });
    }

    $('#split').on('click', function(e){
        var val = parseInt($(this).data().total);
        if(val === 0){
           
        } else {
           var length = $('.limit').length;
           var split = Math.floor(val/length);
           $('.limit').each(function(index){
            $('#limit_'+index).val(split);
           });
        }
    });
    var i = 0;
    $('.share').on('click', function(event){
        event.preventDefault();
        var row = $(this).parents("tr").first();
        var nama = row.find('input[name="nama_tmp"]');
        var id_login = row.find('input[name="id_login"]');
        var log_gagal = row.find('input[name="log_gagal"]');
        var limit = row.find('input[name="limit"]');
        var id_campaign = $('#id_campaign').val();
        var badge = row.find('span');
        console.log(limit.val());
        if(id_campaign == "" || id_campaign == undefined)
        {
            toastr.info('Pilih campaign yang tersedia');
            return false;
        }

        if(limit.val() == 0)
        {
            badge.text('Harus terisi 1');
        } else {
            sent(nama, limit, id_login, badge, id_campaign, log_gagal);
        }
    });

    function sent(nama, limit, id_login, badge, id_campaign, log_gagal)
    {
        $.ajax({
            url: "<?= base_url('admin/campaign/sharing');?>",
            method: 'post',
            dataType:"json",
            data: {id_login:id_login.val(), limit:limit.val(), id_campaign:id_campaign},
            success: function(result){
                console.log(result);
                if(result.error)
                {
                    toastr.error(result.message);
                    $('.available').text(result.data.available);
                    limit.val('0');
                    badge.text('');
                } else {
                    toastr.success('Share '+limit.val()+' ke '+nama.val());
                    $('.available').text(result.data.available);
                    limit.val('0');
                    badge.text('');
                    window.location.reload();
                }
                
            }
        });
    }

    $('.share-all').on('click', function(event){
        event.preventDefault();
        $('.share').each(function(index){
            var row = $(this).parents("tr").first();
            var form = row.find('form');
            var nama = row.find('input[name="nama_tmp"]');
            var id_login = row.find('input[name="id_login"]');
            var limit = row.find('input[name="limit"]');
            var badge = row.find('span');
            var id_campaign = $('#id_campaign').val();
            
            if(limit.val() == 0 || limit.val() === undefined)
            {
                toastr.error('Share Limit harus terisi satu di '+nama.val());
                badge.text('Harus terisi 1');
                return false;
            } else {
                sent(nama, limit, id_login, badge, id_campaign);
                window.location.reload();
            }
        });
    });
});

</script>
