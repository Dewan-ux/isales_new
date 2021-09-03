<?php echo view('landingpage/_partial/header'); ?>

<style>
.coba {
  position: relative;
  max-width: 400px;
  height: auto;
  margin-left: auto;
  margin-right: auto;
}
.coba .btn {
    position: absolute;
    top: 40px;
    left: 62%;
    transform: translate(-50%, -50%);
    -ms-transform: translate(-30%, -50%);
    background-color: #0783c9;
    color: white;
    font-size: 13px;
    padding:0.3em 1.2em;
    border-radius: 80px;
    box-sizing: border-box;
    line-height: 0.5cm;
    text-decoration: none;
    display: block;
    width: 69%;

  }

.coba .btn:hover {
  background-color: #00a9e9;
}

</style>
<div class="page-wrapper bg-blue p-t-50 p-b-50 font-robo">
        <div class="wrapper">
            <div class="coba">
        <img src="<?php echo base_url ("public/themes/landingpage/images/".$foto_brosur); ?>"  width="100%"/>
        <a href="<?php echo base_url('landingpage/'.$page.'/create');?>">
        <button class="btn" >"Ayoo klik dan Dapatkan
        voucher gopay untuk 100 pengisi pertama"</button></a>
        </div>
    </div>
    </div>
    <?php echo view('landingpage/_partial/footer'); ?>
