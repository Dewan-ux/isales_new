<?php
    $url = 'http://localhost/isales/services/resetshare';
    $ch = curl_init($url);
    curl_exec($ch);
    curl_close($ch);
?>