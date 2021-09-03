<?php
    $url = 'https://isales.arwics.com/services/get/recording';
    $ch = curl_init($url);
    curl_exec($ch);
    curl_close($ch);
?>