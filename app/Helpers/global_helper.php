<?php

use App\Models\LoginModel;
use App\Models\ExtensionPabxModel;
use App\Models\LogCallModel;
use App\Models\LogConfirmInterestModel;

function tokenCheck($req = array())
{
    $auth = new LoginModel;
    if(empty($req['token'])){
        return FALSE;
    }
    $param_cek = array('token' => $req['token']);
    $cek = $auth->getAll($param_cek);

    $res = $cek->getRowArray();

    if(!$cek->getResult())
    {
        $res = FALSE;
    }

    return $res;
}

function penyebut($nilai) {
    $nilai = abs($nilai);
    $huruf = array("", "Satu", "Dua", "Tiga", "Empat", "Lima", "Enam", "Tujuh", "Delapan", "Sembilan", "Sepuluh", "Sebelas");
    $temp = "";
    if ($nilai < 12) {
        $temp = " ". $huruf[$nilai];
    } else if ($nilai <20) {
        $temp = penyebut($nilai - 10). " Belas";
    } else if ($nilai < 100) {
        $temp = penyebut($nilai/10)." Puluh". penyebut($nilai % 10);
    } else if ($nilai < 200) {
        $temp = " seratus" . penyebut($nilai - 100);
    } else if ($nilai < 1000) {
        $temp = penyebut($nilai/100) . " Ratus" . penyebut($nilai % 100);
    } else if ($nilai < 2000) {
        $temp = " seribu" . penyebut($nilai - 1000);
    } else if ($nilai < 1000000) {
        $temp = penyebut($nilai/1000) . " Ribu" . penyebut($nilai % 1000);
    } else if ($nilai < 1000000000) {
        $temp = penyebut($nilai/1000000) . " Juta" . penyebut($nilai % 1000000);
    } else if ($nilai < 1000000000000) {
        $temp = penyebut($nilai/1000000000) . " Milyar" . penyebut(fmod($nilai,1000000000));
    } else if ($nilai < 1000000000000000) {
        $temp = penyebut($nilai/1000000000000) . " Trilyun" . penyebut(fmod($nilai,1000000000000));
    }     
    return ucfirst(strval($temp));
}

function create_demo_login($role, $length, $created_by, $group, $last_id)
{
    $prefix_name = $role == '2' ? 'Leader' : ($role == '3' ? 'TSR' : 'QA');
    $data = [];
    for($i = $last_id; $i < ($length+$last_id); $i++)
    {
        $pss = createPassword($role);
        $data[$i] = [
            'username' => strtolower($prefix_name).sprintf("%02s", ($i)),
            'role' => $role,
            'password' => do_hash($pss),
            'created_by' => $created_by
        ];
        
        if($role == '3' && (isset($group) || $group != '0'))
        {
            $data[$i]['group'] = $group;
        }
    }

    return $data;
}

function create_demo_user($role, $length, $created_by, $first_id, $last_id)
{
    $prefix_name = $role == '2' ? 'Leader' : ($role == '3' ? 'TSR' : 'QA');
    $data = [];
    $id = $first_id;
    for($i = $last_id; $i < ($length + $last_id); $i++)
    {
       
        $data[$i] = [
            'nama' => $prefix_name. ' ' . penyebut($i),
            'jk' => rand(0, 1) == 1 ? 'L' : 'P',
            'created_by' => $created_by,
            'id_login' => $id
        ];
        $id++;
    }
    return $data;
}

function createExtension($rowdata){
    $extension_pabx = new ExtensionPabxModel();
    if($rowdata['role'] == '2' || $rowdata['role'] == '3'){
        $extension = codeNumeric(6);
        $device_owner = $rowdata['username'];
        $secret = codeNumeric(10);
        $data = [
            'extension' => $extension,
            'device_owner' => $device_owner,
            'secret' => $secret,
        ];

        // $url = "https://sip-1.c-icare.cc/apipbx/sip/account";
        $url = PABXURL."sip/account/";
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type:application/json',
            'apisecret: '.PABX_API_KEY
        ));
        
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $res = json_decode(curl_exec($ch), false);
        curl_close($ch);
        if(intval($res->code) == 200)
        {
            $data = [
                'id_login' => $rowdata['id_login'],
                'extension' => strval($res->data->extension),
                'device_owner' => strval($res->data->device_owner),
                'secret' => strval($res->data->secret),
                'created_by' => $rowdata['user_id'],
                'active' => '1'
            ];
            
            $create = $extension_pabx->addNew($data);

            if($create)
            {
                return $data;
            } else {
                return FALSE;
            }
        } else {
            return FALSE;
        }
    } else {
        return FALSE;
    }
}

function hitung_umur($tanggal_lahir){
    $birthDate = new DateTime($tanggal_lahir);
    $today = new DateTime("today");
    if ($birthDate > $today) {
        exit("0 tahun 0 bulan 0 hari");
    }
    $y = $today->diff($birthDate)->y;
    $m = $today->diff($birthDate)->m;
    $d = $today->diff($birthDate)->d;

    return array($y." TAHUN", $m." BULAN", $d." HARI");
}


function sessionCheck()
{
    $result = false;
    if(session()->get('token') != NULL){
        $val = tokenCheck(['token' => session()->get('token')]);
        if($val)
        {
            $request = \Config\Services::request();
            if( $request->uri->getTotalSegments() > 0)
            {
                $active = $request->uri->getSegment($request->uri->getTotalSegments() );
                session()->setFlashdata('active', $active);
                $mainactive = $request->uri->getSegment($request->uri->getTotalSegments()-1 );
                session()->setFlashdata('mainactive', $mainactive);
            }

            setLastActivity();
            $result = true;
        }

        $now = time();
        $last_activity_at = session()->get('last_activity');

        if(round(abs($now - $last_activity_at) / 60, 2) >= 5)
        {
            if($val)
            {
                $auth = new LoginModel();
                $update = $auth->editAble($val['id'], ['logged_in' => '0', 'token' => '']);
                if($update)
                {
                    session()->destroy();
                    $result = false;
                } else {
                    $result = true;
                }
            }
        }
    }
    return $result;
}

function setLastActivity(){
    session()->set('last_activity', time());
}

function codeNumeric($length = 6) {
    $characters = '0123456789';
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
      $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $randomString;
}

function codeAlphaNumeric($prefix, $curCode, $startIndex, $endIndex, $length){
    if(isset($curCode))
    {
        $urutan = (int) substr($curCode, $startIndex, $endIndex);
    } else {
        $urutan = 0;
    }
    $urutan++;
    return $prefix . sprintf('%0'.strval($length).'s', $urutan);
}

function formatPhoneInd($nohp) {
    // kadang ada penulisan no hp 0811 239 345
    $nohp = str_replace(" ","",$nohp);
    // kadang ada penulisan no hp (0274) 778787
    $nohp = str_replace("(","",$nohp);
    // kadang ada penulisan no hp (0274) 778787
    $nohp = str_replace(")","",$nohp);
    // kadang ada penulisan no hp 0811.239.345
    $nohp = str_replace(".","",$nohp);

    // cek apakah no hp mengandung karakter + dan 0-9
    if(!preg_match('/[^+0-9]/',trim($nohp))){
        // cek apakah no hp karakter 1-3 adalah +62
        if(substr(trim($nohp), 0, 3)=='+62'){
            $hp = '0'.substr(trim($nohp), 3);
        }
        // cek apakah no hp karakter 1 adalah 0
        elseif(substr(trim($nohp), 0, 2)=='62')
        {
            $hp = '0'.substr(trim($nohp), 2);
        } else{
            $hp = trim($nohp);
        }
    }
    return $hp;
}

function tokenCallCheck($req = array())
{
    $log_call = new LogCallModel;

    $param_cek = array('token_call' => $req['token_call']);
    $cek = $log_call->getAll($param_cek);
    $res = $cek->getRowArray();
    if(!$cek->getResult())
    {
        $res = FALSE;
    }

    return $res;
}

function tokenCallCi($req = array())
{
    $log_call = new LogConfirmInterestModel();

    $param_cek = array('token_ci' => $req['token_ci']);
    $cek = $log_call->getAll($param_cek);
    $res = $cek->getRowArray();
    if(!$cek->getResult())
    {
        $res = FALSE;
    }

    return $res;
}

function imageCheck($base64Image)
{
    $allowedImageMime = [
        'image/png',
        'image/jpeg',
        'image/gif',
        'image/jpg'
    ];


    $imgdata = base64_decode($base64Image);
    $f = finfo_open();

    $mime_type = finfo_buffer($f, $imgdata, FILEINFO_MIME_TYPE);

    $res = TRUE;
    if(!in_array($mime_type, $allowedImageMime))
    {
        $res = FALSE;
    }

    return $res;
}

function encryptor($action, $string) {
    $output = false;

    $encrypt_method = "AES-256-CBC";
    //pls set your unique hashing key
    $secret_key = 'f8d3b62a9b6d1962c22b4640730167a78220aa2b7e7a9d109553ba12d4fea38426916cd9a05756c09cc22d97eac4f5182391714da743241192ad9df058c27538';
    $secret_iv = 'a58d59548ef2ba75b3438051eb7b4870b25fd59955438fe2082ad0cfe02d3bbd0bc4e84fd1b9c4c364a4b3a1192f08d2a18c8a6ee199b7d0332c26e88a473fdb';

    // hash
    $key = hash('sha512', $secret_key);

    // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
    $iv = substr(hash('sha512', $secret_iv), 0, 16);

    //do the encyption given text/string/number
    if( $action == 'encrypt' ) {
        $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
        $output = base64_encode($output);
    }
    else if( $action == 'decrypt' ){
        //decrypt the given text/string/number
        $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
    }
    return $output;
}



function imageToBase64($image){
    // $path = realpath($image->getTempName());
    $data = file_get_contents($image);
    $base64 = base64_encode($data);

    return $base64;
}

function filterByDateSub($filter){
    if($filter == 1)
    {
        return "= DATE(CURDATE())";
        //daily
    } else if($filter == 2) {
        //weekly
        return ">= DATE(DATE_SUB(CURDATE(), INTERVAL 1 WEEK))";
    } else if($filter == 3) {
        //monthly
        return ">= DATE(DATE_SUB(CURDATE(), INTERVAL 1 MONTH))";
    } else if($filter == 4) {
        //quarter
        return ">= DATE(DATE_SUB(CURDATE(), INTERVAL 1 QUARTER))";
    } else if($filter == 5) {
        //semester
        return ">= DATE(DATE_SUB(CURDATE(), INTERVAL 2 QUARTER))";
    } else {
        //year
        return ">= DATE(DATE_SUB(CURDATE(), INTERVAL 1 YEAR))";
    }
}

function createMessage($role)
{
    if($role == '1')
    {
        $message = 'User Admin Created!';
    }

    if($role == '2')
    {
        $message = 'User Leader Created!';
    }

    if($role == '3')
    {
        $message = 'User TSR/TeleSales Created!';
    }

    if($role == '4')
    {
        $message = 'User QA Created!';
    }

     if($role == '5')
    {
        $message = 'CAR Admin Created!';
    }

    return $message;
}

function createPassword($role)
{
    if($role == '1')
    {
        $password = 'Admin123!';
    }

    if($role == '2')
    {
        $password = 'Leader123!';
    }

    if($role == '3')
    {
        $password = 'Tele123!';
    }

    if($role == '4')
    {
        $password = 'Qa123!';
    }

    return $password;
}

function fullTimeNow()
{
    return date('Y-m-d H:i:s');
}

function fullDayNow()
{
    return date('Y-m-d');
}