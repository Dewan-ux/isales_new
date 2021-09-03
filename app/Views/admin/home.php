<?php echo view('admin/_partials/header'); ?>
<?php echo view('admin/_partials/sidebar'); ?>
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Dashboard</h1>
            </div>
            <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active">Dashboard</li>
            </ol>
            </div>
        </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3><?php //echo $total_transaction; ?></h3>

                            <p>Data Product</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-dollar-sign"></i>
                        </div>
                        <a href="<?php echo base_url('admin/produk'); ?>" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3><?php //echo $total_product; ?></h3>

                            <p>Premi</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-cart-plus"></i>
                        </div>
                        <a href="<?php echo base_url('admin/premi'); ?>" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3><?php //echo $total_category; ?></h3>
                            <p>Payment Method</p>
                        </div>
                        <div class="icon">
                            <i class="fa fa-tags"></i>
                        </div>
                        <a href="<?php echo base_url('admin/payment_method'); ?>" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-danger">
                        <div class="inner">
                            <h3><?php //echo $total_user; ?></h3>
                            <p>Users</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <a href="<?php echo base_url('admin/user'); ?>" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <div class="col-md-12">
            <!-- AREA CHART -->
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Visitor Registered Chart</h3>

                <div class="card-tools">
                  <div class="form-group">
                    <div class="input-group">
                      <button type="button" class="btn btn-default float-right" id="date_range">
                        <i class="far fa-calendar-alt"></i>
                        <span></span>
                        <i class="fas fa-caret-down"></i>
                      </button>
                    </div>
                  </div>
                  <!-- <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                  </button>
                  <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i></button> -->
                </div>
              </div>
              <div class="card-body">
                <div class="chart">
                     <canvas id="areaChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
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
  $(function () {
    var default_start_date = moment().subtract(1, 'week');
    var default_end_date = moment();
    function cb(start, end) {
        $('#date_range span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
        getData(start.format('YYYY-MM-DD'),end.format('YYYY-MM-DD'));
    }
    $('#date_range').daterangepicker({
      startDate: default_start_date,
      endDate: default_end_date,
      maxDate:  default_end_date
      },
      function(start, end, label){
        cb(start, end)
    });
    cb(default_start_date, default_end_date);
    function getData(start_date, end_date){
      $.ajax({
          url: "<?= base_url('admin/visitors');?>",
          type: "POST",
          dataType: "JSON",
          data: {start_date:start_date, end_date:end_date},
          success: function(result){
            var arr_date = [];
            var arr_value = [];
            $.each(result.data,function(index, value){
              arr_date.push(value.Date);
              arr_value.push(value.total);
            });
            initChart(arr_date, arr_value);
          }
      });
    }
    // initChart(labels, data);
    function initChart (labels, data){
      var areaChartCanvas = $('#areaChart').get(0).getContext('2d')
      var areaChartData = {
        labels  : labels,
        datasets: [
          {
            label               : "# Entry",
            backgroundColor     : '#FF6347',
            pointRadius         : true,
            pointColor          : 'rgba(210, 214, 222, 1)',
            pointStrokeColor    : '#c1c7d1',
            pointHighlightFill  : '#fff',
            pointHighlightStroke: 'rgba(220,220,220,1)',
            data                : data
          },
        ]
      }

      var areaChartOptions = {
        maintainAspectRatio : false,
        responsive : true,
        legend: {
          display: false
        },
        scales: {
          xAxes: [{
            gridLines : {
              display : false,
            }
          }],
          yAxes: [{
            gridLines : {
              display : false,
            }
          }]
        }
      }

      // This will get the first returned node in the jQuery collection.
      var areaChart       = new Chart(areaChartCanvas, { 
        type: 'line',
        data: areaChartData, 
        options: areaChartOptions
      })
    }
  })
</script>