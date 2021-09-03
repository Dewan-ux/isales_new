<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" href="<?= base_url("/public/themes/landingpage/css/style.css")?> ">
    <script src='https://www.google.com/recaptcha/api.js'></script>
    <title><?= $title;?></title>
</head>

<body>
    <div class="landing1 m-0 p-0">
        <div class="row row-1 m-0 p-0"
        style="background: url('<?php echo base_url('public/themes/landingpage/images/Assets/'.$dir.'/'.$foto)?>') center center/cover no-repeat;">
            <div  class="col col-md-12 col-xs-12 col-lg-12" >
                <img src="<?php echo base_url("/public/themes/landingpage/images/Assets/$dir/$tag_line") ?>"  alt="alternatetext" />
            </div>
        </div>
        <?php
                             
            echo form_open(base_url('landingpage/'.$page.'/add'), ['id'=>'landingpage-form']); 
        ?>
        <div class="row row-2 m-0 p-0 ">
            <div class="row1-col1 col-lg-7 col-12 mx-auto  col-md-12 col-sm-12 pb-3 " style="background-image: url('<?php echo base_url('public/themes/landingpage/images/Assets/'.$dir.'/'.$vocher)?>');">
                <div  class=" container fluid" >
                    <div class="row w-100">
                    
                        <div  class="col-sm-4 col-md-4 col-lg-4  col-4">

                        </div>

                       
                        <div  class=" col-sm-8 col-md-8 col-lg-7 col-8  form-container" >
                            <div class="voucer-gopay-row1-col2 m-0">
                                <span><?= $pertanyaan[0]?></span>
                                <div>
                                    <label class="ml-2" htmlFor="radioA"><input required type="radio" name="jumlah_kopi" value="0" /><span> <?php echo $option[0][0] ?></span></label>
                                    <label class="ml-3" htmlFor="radioB"><input required type="radio" name="jumlah_kopi" value="1" /><span> <?php echo $option[0][1] ?></span></label>
                                    <label class="ml-3" htmlFor="radioC"><input required type="radio" name="jumlah_kopi" value="2" /><span> <?php echo $option[0][2] ?></span></label>
                                </div>
                                <span><?php echo $pertanyaan[1]?></span>
                                <div>
                                    <label class="ml-1" htmlFor="radioA"><input required type="radio" name="budget_kopi" value="0" /> <?php echo $option[1][0] ?></label>
                                    <label class="ml-1" htmlFor="radioB"><input required type="radio" name="budget_kopi" value="1" /> <?php echo $option[1][1] ?></label>
                                    <label class="ml-1" htmlFor="radioC"><input required type="radio" name="budget_kopi" value="2" /> <?php echo $option[1][2] ?></label>
                                </div>
                                <div style=' width:80%'>
                                    <span><b><?php echo $pertanyaan[2] ?></b></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="    " >
                        <div class="row " >
                            <div class="col-lg-6 col-md-9 col-7 mx-auto mt-3 form-bottom" style="font-size: 12px;">
                                <?php 
                                $inputs = session()->getFlashdata('inputs');
                                $errors = session()->getFlashdata('errors');
                                if(!empty($errors)){ ?>
                                    <div class="alert alert-danger" role="alert">
                                        <ul>
                                        <?php foreach ($errors as $error) : ?>
                                            <li><?= esc($error) ?></li>
                                        <?php endforeach ?>
                                        </ul>
                                    </div>
                                <?php } ?>
                                <div class="form-group">
                                      <input type="text" class="form-control" required id="exampleInputName1" name="nama"  placeholder="Nama Lengkap*">
                                    </div>
                                    <div class="form-group">
                                      <input type="text" class="form-control" required id="exampleInputPassword1" name="telepon" placeholder="No. Handphone*">
                                    </div>
                                    <!-- <div class="form-group">
                                        <input type="email" class="form-control" aria-describedby="emailHelp" name="email" id="exampleEmail1" placeholder="Email*">
                                      </div> -->
                                    <div class="form-group form-check">
                                        <p>*Apakah anda sudah memiliki Asuransi?</p>
                                        <label  htmlFor="radioA"><input type="radio" name="asuransi" required value="1" /> YA </label>
                                        <label  htmlFor="radioA"><input type="radio" name="asuransi" required value="0" /> Tidak </label>
                                    </div>
                                    <div class="form-group form-check">
                                        <div>
                                            <label className="ml-2" htmlFor="radioA"><input className="mr-2" type="radio" required name="bersedia" id="bersedia" value="accept" /> *Saya bersedia dihubungi oleh tim telemarketing </label>
                                        </div>
                                    </div>
                                        <div class="form-group">
                                            <div class="g-recaptcha" data-sitekey="6LcNKFEaAAAAAHPzuTHcZblUCFgZAowVDBf9ZPvD" data-callback="verifyRecaptchaCallback" data-expired-callback="expiredRecaptchaCallback"></div>
                                            <input class="form-control d-none" data-recaptcha="true" required data-error="Please complete the Captcha">
                                            <div class="help-block with-errors"></div>
                                        </div>
                                    <button type="submit" class="btn btn-primary m-1" style="background-color: orangered;border: none;">Submit</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php form_close();?>

        </div>
    </div>
</body>
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
    integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN"
    crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"
    integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"
    crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"
    integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"
    crossorigin="anonymous"></script>
<!-- <script src="https://www.google.com/recaptcha/api.js?render=6LfFc1AaAAAAAKUNqzN_fTevOWGX-EOTMFWPw-v5"></script> -->
<script src="<?= base_url('/public/themes/landingpage/js/validator.js') ?>"></script>
<!-- <script src="<?= base_url('/public/themes/landingpage/js/landingpage.js') ?>"></script> -->
</html>