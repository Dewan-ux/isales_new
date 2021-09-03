<?php
    setlocale(LC_ALL, 'IND');
    date_default_timezone_set('Asia/Jakarta');
    ini_set('memory_limit', '-1');
    ini_set('max_execution_time', '1000');
    
    $url = 'http://localhost/isales/services/cronjob/downrec';
    $data = array(
        'token' => base64_encode(date('Y-m-d H:i:s', strtotime( '-1 days' ))),
        // 'search' =>  $search,
        // 'length' => $$option,
        'date' => date('Y-m-d', strtotime( '-1 days' ))
    );

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $res = json_decode(curl_exec($ch), TRUE);
    curl_close($ch);
    
    echo "DONE@".date(DATE_RFC2822).".\n ";
    echo json_encode($res);
    $log = "[".date(DATE_RFC2822)."] → ".json_encode($res)."\n";
    file_put_contents('/var/www/html/isales/writable/logs/recording.log', $log, FILE_APPEND);
?>