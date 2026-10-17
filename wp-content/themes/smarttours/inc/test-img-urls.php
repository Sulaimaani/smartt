<?php
$candidates = array(
    'kayak1' => 'https://images.unsplash.com/photo-1510312305653-8ed496efae75?auto=format&fit=crop&w=1200&q=80',
    'kayak2' => 'https://images.unsplash.com/photo-1498654896293-37aacf113fd9?auto=format&fit=crop&w=1200&q=80',
    'sup1'   => 'https://images.unsplash.com/photo-1520255870062-bd79d3865de7?auto=format&fit=crop&w=1200&q=80',
    'sup2'   => 'https://images.unsplash.com/photo-1508739773434-c26b3d09e071?auto=format&fit=crop&w=1200&q=80',
    'sup3'   => 'https://images.unsplash.com/photo-1519046904884-53103b34b206?auto=format&fit=crop&w=1200&q=80',
    'water1' => 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=1200&q=80',
    'boat2'  => 'https://images.unsplash.com/photo-1559136555-9303baea8ebd?auto=format&fit=crop&w=1200&q=80'
);

$ch = curl_init();
curl_setopt($ch, CURLOPT_NOBODY, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 5);

foreach ($candidates as $k => $u) {
    curl_setopt($ch, CURLOPT_URL, $u);
    curl_exec($ch);
    $c = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    echo "$k: $c\n";
}
curl_close($ch);
