<?php
require_once 'c:/xampp/htdocs/wordpress/wp-load.php';
require_once 'c:/xampp/htdocs/wordpress/wp-content/themes/smarttours/inc/update-real-experiences.php';

// Check which photos are valid
$ch = curl_init();
curl_setopt($ch, CURLOPT_NOBODY, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 5);

foreach ($real_photos as $k => $url) {
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    if ($code !== 200) {
        echo "FAIL [$code]: $k -> $url\n";
    } else {
        echo "OK [200]: $k\n";
    }
}
curl_close($ch);
