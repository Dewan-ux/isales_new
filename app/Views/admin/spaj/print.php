<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>SPAJ</title>
    <!-- <link rel="stylesheet" href="<?php echo base_url('/public/print'); ?>/pdf/style.css" media="screen" /> -->
    <style>
      table {
              width: 100%;
              border-collapse: collapse;
              border-spacing: 0;
              margin-bottom: 20px;
            }

        table th,
        table td {
          padding: 10px;
          /* background: #EEEEEE; */
          /* text-align: right; */
          border-bottom: 1px solid #FFFFFF;
        }

        table tr {
          white-space: nowrap;        
          font-weight: normal;
          text-align: left;
        }

        table td {
          text-align: right;
        }

        table td h3{
          color: #57B223;
          font-size: 1.2em;
          font-weight: normal;
          margin: 0 0 0.2em 0;
        }
    </style>
  </head>
  <body>
    <!-- <header class="clearfix"> -->
      <table border="2" cellspacing="2" cellpadding="6">
      <thead>
      <tr>
              <td style="text-align: left;">Jenis Asuransi</td>
              <td><?=ucwords($jns_asuransi[intval($spaj['jns_asuransi'])])?></td>


            </tr>
            <tr>
              <td style="text-align: left;">No. Proposal</td>
              <td><?=$spaj['no_proposal']?></td>
            </tr>

                       <tr>
              <td style="text-align: left;">No. SPAJ</td>
              <td><?=$spaj['no_spaj']?></td>
            </tr>

            <tr>
              <td style="text-align: left;">NIK</td>
              <td>
                 <?=ucwords($spaj['NIK'])?>
              </td>
            </tr>

             <tr>
              <td style="text-align: left;">NPWP</td>
              <td>
                <?=ucwords($spaj['NPWP'])?></td>
            </tr>

            <tr>
              <td style="text-align: left;">Nama</td>
              <td><?=ucwords($spaj['nama'])?></td>
            </tr>

            <tr>
              <td style="text-align: left;">Tempat Lahir</td>
              <td><?=ucwords($spaj['tempat_lahir'])?></td>
            </tr>

            <tr>
              <td style="text-align: left;">Tanggal Lahir</td>
              <td><?=ucwords($spaj['tgl_lahir'])?></td>
            </tr>

            <tr>
              <td style="text-align: left;">Alamat</td>
              <td><?=ucwords($spaj['alamat'])?></td>
            </tr>

            <tr>
              <td style="text-align: left;">Kota</td>
              <td><?=ucwords($spaj['kota'])?></td>
            </tr>

            <tr>
              <td style="text-align: left;">Provinsi</td>
              <td><?=ucwords($spaj['provinsi'])?></td>
            </tr>

            <tr>
              <td style="text-align: left;">Kode POS</td>
              <td><?=ucwords($spaj['pos'])?></td>
            </tr>

            <tr>
              <td style="text-align: left;">Jenis Kelamin</td>
              <td><?=ucwords($gender[$spaj['jk']])?></td>
            </tr>

            <tr>
              <td style="text-align: left;">Agama</td>
              <td><?=ucwords($spaj['agama'])?></td>
            </tr>

            <tr>
              <td style="text-align: left;">Telepon</td>
              <td><?=$spaj['telp1']?><?php if(!empty($spaj['telp2']))echo "/$spaj[telp2]";?></td>
            </tr>

            <tr>
              <td style="text-align: left;">Pekerjaan</td>
              <td><?=ucwords($spaj['pekerjaan'])?></td>
            </tr>

             <tr>
              <td style="text-align: left;">Bank</td>
              <td><?=ucwords($spaj['bank'])?></td>
            </tr>

             <tr>
              <td style="text-align: left;">Card Number</td>
              <td><?=ucwords($spaj['card_number'])?></td>
            </tr>

             <tr>
              <td style="text-align: left;">Expired Date</td>
              <td><?=ucwords($spaj['expired_date'])?></td>
            </tr>

            <tr>
              <td style="text-align: left;">Produk</td>
              <td><?=$spaj['nama_produk']?></td>
            </tr>

            <tr>
              <td style="text-align: left;">Premi</td>
              <td><?="Rp. ".number_format($spaj['nominal'],2,",",".")?></td>
            </tr>

            <tr>
              <td style="text-align: left;">Satuan</td>
              <td><?=$spaj['satuan']?></td>
            </tr>

<!-- wali pertama -->
            <tr>
              <td style="text-align: left;">Nama Wali Ke-1</td>
              <td><?=ucwords($spaj['wali_nama'])?></td>
            </tr>

            <tr>
              <td style="text-align: left;">Hubungan Wali</td>
              <td>
                <?php if(!empty($spaj['wali_hubungan']))echo $wali_hubungan[$spaj['wali_hubungan']];?>
              </td>
            </tr>

            <tr>
              <td style="text-align: left;">Tempat Lahir Wali</td>
              <td><?=ucwords($spaj['wali_tempat_lahir'])?></td>
            </tr>

            <tr>
              <td style="text-align: left;">Tanggal Lahir Wali</td>
              <td><?=ucwords($spaj['wali_tgl_lahir'])?></td>
            </tr>

            <tr>
              <td style="text-align: left;">Jenis Kelamin Wali</td>
              <td>
                <?php if(!empty($spaj['wali_jk']))echo $gender[$spaj['wali_jk']];?>        
              </td>
            </tr>

            <tr>
              <td style="text-align: left;">Wali NIK</td>
              <td><?=ucwords($spaj['wali_NIK'])?></td>
            </tr>

            <tr>
              <td style="text-align: left;">Telepon Wali</td>
              <td><?=$spaj['wali_telp1']?><?php if(!empty($spaj['wali_telp2']))echo "/$spaj[wali_telp2]";?></td>
            </tr>

            <tr>
              <td style="text-align: left;">Status Pernikahan Wali</td>
              <td>
                <?php if($spaj['wali_status'] !='')echo $wali_status[$spaj['wali_status']];
                ?>

              </td>
            </tr>

<!-- wali ke2 -->
            <tr>
              <td style="text-align: left;">Nama Wali Ke-2</td>
              <td><?=ucwords($spaj['wali_nama2'])?></td>
            </tr>

            <tr>
              <td style="text-align: left;">Hubungan Wali</td>
              <td>
                <?php if(!empty($spaj['wali_hubungan2']))echo $wali_hubungan[$spaj['wali_hubungan2']];?>                
              </td>
            </tr>

            <tr>
              <td style="text-align: left;">Tempat Lahir Wali</td>
              <td><?=ucwords($spaj['wali_tempat_lahir2'])?></td>
            </tr>

            <tr>
              <td style="text-align: left;">Tanggal Lahir Wali</td>
              <td><?=ucwords($spaj['wali_tgl_lahir2'])?></td>
            </tr>

            <tr>
              <td style="text-align: left;">Jenis Kelamin Wali</td>
              <td>               
                <?php if(!empty($spaj['wali_jk2']))echo $gender[$spaj['wali_jk2']];?>        
            </tr>

            <tr>
              <td style="text-align: left;">NIK Wali</td>
              <td><?=ucwords($spaj['wali_NIK2'])?></td>
            </tr>

            <tr>
              <td style="text-align: left;">Telepon Wali</td>
              <td><?=$spaj['wali2_telp1']?><?php if(!empty($spaj['wali2_telp2']))echo "/$spaj[wali2_telp2]";?></td>
            </tr>

            <tr>
              <td style="text-align: left;">Status Pernikahan Wali</td>
              <td>
                <?php if($spaj['wali_status2'] !='')echo $wali_status[$spaj['wali_status2']];
                 ?>
              </td>
            </tr>

<!-- wali ke3 -->
<tr>
              <td style="text-align: left;">Nama Wali Ke-3</td>
              <td><?=ucwords($spaj['wali_nama'])?></td>
            </tr>

            <tr>
              <td style="text-align: left;">Hubungan Wali</td>
              <td>
                <?php if(!empty($spaj['wali_hubungan3']))echo $wali_hubungan[$spaj['wali_hubungan3']];?>                
              </td>
            </tr>

            <tr>
              <td style="text-align: left;">Tempat Lahir Wali</td>
              <td><?=ucwords($spaj['wali_tempat_lahir3'])?></td>
            </tr>

            <tr>
              <td style="text-align: left;">Tanggal Lahir Wali</td>
              <td><?=ucwords($spaj['wali_tgl_lahir3'])?></td>
            </tr>

            <tr>
              <td style="text-align: left;">Jenis Kelamin Wali</td>
              <td>
                <?php if(!empty($spaj['wali_jk3']))echo $gender[$spaj['wali_jk3']];?>        
                </td>
            </tr>

            <tr>
              <td style="text-align: left;">NIK Wali</td>
              <td><?=ucwords($spaj['wali_NIK3'])?></td>
            </tr>

            <tr>
              <td style="text-align: left;">Telepon Wali</td>
              <td><?=$spaj['wali3_telp1']?><?php if(!empty($spaj['wali3_telp2']))echo "/$spaj[wali3_telp2]";?></td>
            </tr>

            <tr>
              <td style="text-align: left;">Status Wali</td>
              <td>
              <?php if($spaj['wali_status3'] != '')echo $wali_status[$spaj['wali_status3']];?></td>
            </tr>
            <!--  -->
          </thead>
</table>
<!-- </header> -->
</body>
</html>