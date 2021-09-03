<?php
    $url = 'http://localhost/isales/services/forceout';
    setlocale(LC_ALL, 'IND');
    date_default_timezone_set('Asia/Jakarta');
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $res = json_decode(curl_exec($ch), TRUE);
    curl_close($ch);
    echo "DONE@".date(DATE_RFC2822).".\n ";
    echo json_encode($res);
    $log = "[".date(DATE_RFC2822)."] → ".json_encode($res)."\n";
    file_put_contents('/var/www/html/isales/writable/logs/logout.log', $log, FILE_APPEND);
?>