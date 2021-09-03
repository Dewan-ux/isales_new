<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css"
        integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" href="<?= base_url("public/themes/landingpage/css/style.css")?> ">
    <script src='https://www.google.com/recaptcha/api.js'></script>
    <title><?= $title;?></title>
    
    <style>
        .voucer-row1-col2 {
            position: relative;
            background-image: url("data:image/svg+xml,%3csvg width='100%25' height='100%25' xmlns='http://www.w3.org/2000/svg'%3e%3crect width='100%25' height='100%25' fill='none' stroke='<?=$bordercol;?>' stroke-width='6' stroke-dasharray='13%2c 12%2c 16%2c 12' stroke-dashoffset='0' stroke-linecap='square'/%3e%3c/svg%3e");
            border-radius: 5px;
        }
    </style>
</head>

<body>
    
    <div class="container-fluid  layer-1" style="background-image: url('<?php echo base_url('public/themes/landingpage/images/Assets/'.$dir.'/'.$foto)?>');">
        <img class="tagline" src="<?php echo base_url("/public/themes/landingpage/images/Assets/$dir/$tag_line") ?>">
    </div>

      <?php

            echo form_open(base_url('landingpage/'.$page.'/add'), ['id'=>'landingpage-form']);
        ?>

    <div class="container-fluid layer-2">
        <div class="row  ">
            <div class="col-xl-6 col-md-8 col-sm-10 col-12  mx-auto voucer">
                <div class="row  voucer-row1 ">
                    <div class="col-xl-4 col-lg-3 col-md-12 col-sm-12 col-12  p-0 voucer-row1-col1">
                        <img class="gopay-logo" src="<?php echo base_url("/public/themes/landingpage/images/Assets/$dir/$voucher") ?>" alt="img">
                    </div>
                    <div class="col-xl-8 col-lg-9 col-md-12 col-sm-12 col-12  py-2 ">
                        <div class="voucer-row1-col2 p-3">
                            <div><?= $pertanyaan[0]?></div>
                            <div class="w-100 ">
                                <div class="mt-1 ml-2 radio-container">
                                    <div>
                                       <label class="ml-2" htmlFor="radioA"><input required type="radio" name="jumlah_kopi" value="0" /><span> <?php echo $option[0][0] ?></span></label>
                                    <label class="ml-3" htmlFor="radioB"><input required type="radio" name="jumlah_kopi" value="1" /><span> <?php echo $option[0][1] ?></span></label>
                                    <label class="ml-3" htmlFor="radioC"><input required type="radio" name="jumlah_kopi" value="2" /><span> <?php echo $option[0][2] ?></span></label>

                                    </div>
                                </div>
                            </div>
                            <div class="mt-3"><?php echo $pertanyaan[1]?></div>
                            <div class="w-100 ">
                                <div class="mt-1 ml-2 radio-container">
                                    <div>
                                         <label class="ml-1" htmlFor="radioA"><input required type="radio" name="budget_kopi" value="0" /> <?php echo $option[1][0] ?></label>
                                    <label class="ml-1" htmlFor="radioB"><input required type="radio" name="budget_kopi" value="1" /> <?php echo $option[1][1] ?></label>
                                    <label class="ml-1" htmlFor="radioC"><input required type="radio" name="budget_kopi" value="2" /> <?php echo $option[1][2] ?></label>

                                    </div>
                                </div>
                            </div>

                            <div class="mt-2 ">
                                <b>
                                    <?php echo $pertanyaan[2] ?>
                                </b>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="row  text-center pt-1 " style="background-color: rgba(224,224,224 ,1);">
                    <div class="col-lg-8 col-md-9 col-sm-9 col-9 mx-auto  pt-3 pb-5 text-left">
                        <div>
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

                            <input class="w-100" type="text" id="fname" name="nama" placeholder="Nama Lengkap *">

                        </div>
                        <div>
                            <input class="w-100" type="text" id="fname" name="telepon" placeholder="No Handphone *">
                        </div>
                        <div class="mt-3">
                            *Apakah Anda sudah memiliki polis Asuransi Jiwa?
                        </div>
                        <div class="mt-2 ml-2 " style="display: flex;">
                            <div>
                                <input type="radio" name="asuransi" required value="1" /> Ya

                            </div>
                            <div class="ml-3">
                                <input type="radio" name="asuransi" required value="0" /> Tidak
                            </div>
                        </div>
                            <div class="mt-2 ml-2 " style="display: flex;">
                             <input className="mr-2" type="radio" required name="bersedia" id="bersedia" value="accept" /> *Saya bersedia dihubungi untuk keterangan lebih lanjut 
                        </div>
                        <div>
                             <div class="g-recaptcha" data-sitekey="6LcNKFEaAAAAAHPzuTHcZblUCFgZAowVDBf9ZPvD" data-callback="verifyRecaptchaCallback" data-expired-callback="expiredRecaptchaCallback"></div>
                                            <input class="form-control d-none" data-recaptcha="true" data-error="Please complete the Captcha">
                                            <div class="help-block with-errors"></div>
                        </div>
                        <div class="mt-1">

                            <button type="submit">Submit</button>
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
<script src='https://www.google.com/recaptcha/api.js'></script>

</html>