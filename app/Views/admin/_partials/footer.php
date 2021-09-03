<?php
if(isset($grafik)){
foreach($grafik as $data){
    $total[] = $data['total'];
    $month[] = $data['month'];
}
}
?>
<aside class="control-sidebar control-sidebar-dark">
    <div class="p-3">
        <h5>Title</h5>
        <p>Sidebar content</p>
    </div>
</aside>

<footer class="main-footer">
    <div class="float-right d-none d-sm-inline">
    </div>
    <strong>Copyright &copy; 2014-2019 <a href="<?php echo base_url('/'); ?>">iSales Admin Page</a>.</strong> All rights reserved.
</footer>
</div>
<script src="<?php echo base_url('public/themes/plugins'); ?>/jquery/jquery.min.js"></script>
<script src="<?php echo base_url('public/themes/plugins'); ?>/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="<?php echo base_url('public/themes/dist'); ?>/js/adminlte.min.js"></script>
<!-- <script src="<?php echo base_url('public/themes/dist'); ?>/js/filter.js"></script> -->
<script src="<?php echo base_url('public/themes/plugins'); ?>/datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url('public/themes/plugins'); ?>/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="<?php echo base_url('public/themes/plugins'); ?>/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="<?php echo base_url('public/themes/plugins'); ?>/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="<?php echo base_url('public/themes/plugins'); ?>/summernote/summernote-bs4.min.js"></script>
<script src="<?php echo base_url('public/themes/plugins'); ?>/chart.js/Chart.min.js"></script>
<script src="<?php echo base_url('public/themes/plugins'); ?>/moment/moment.js"></script>
<script src="<?php echo base_url('public/themes/plugins'); ?>/inputmask/min/jquery.inputmask.bundle.min.js"></script>
<script src="<?php echo base_url('public/themes/plugins'); ?>/daterangepicker/daterangepicker.js"></script>
<script src="<?php echo base_url('public/themes/plugins'); ?>/sweetalert2/dist/sweetalert2.all.min.js"></script>
<script src="<?php echo base_url('public/themes/plugins'); ?>/toastr/toastr.min.js"></script>
<?php if(isset($grafik)){?>
<script>
var chart = document.getElementById("#areaChart").getContext('2d');
var areaChart = new Chart(chart, {
  type: 'bar',
  data: {
    labels: <?php //echo json_encode($month); ?>,
    datasets: [
      {
        label: "Grafik Penjualan",
        data: <?php //echo json_encode($total); ?>,
        backgroundColor: [
          'rgba(255, 99, 132, 0.2)',
          'rgba(54, 162, 253, 0.2)',
          'rgba(255, 206, 86, 0.2)',
          'rgba(75, 192, 192, 0.2)',
          'rgba(153, 255, 255, 0.2)',
          'rgba(255, 159, 64, 0.2)',
        ],
        borderColor: [
          'rgba(255, 99, 132, 1)',
          'rgba(54, 162, 253, 1)',
          'rgba(255, 206, 86, 1)',
          'rgba(75, 192, 192, 1)',
          'rgba(153, 255, 255, 1)',
          'rgba(255, 159, 64, 1)',
        ],
        borderWidth: 1
      }
    ]
  },
  options: {
    scales: {
      yAxes: [
        {
          ticks: {
            beginZero: true
          }
        }
      ]
    }
  }
});
</script>
<?php } ?>
</body>
</html>