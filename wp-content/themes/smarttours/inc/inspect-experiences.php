<?php
require_once 'c:/xampp/htdocs/wordpress/wp-load.php';

$posts = get_posts(array(
    'post_type'      => 'experience',
    'posts_per_page' => -1,
    'post_status'    => 'any'
));

echo "Total experiences found: " . count($posts) . "\n";
foreach (array_slice($posts, 0, 30) as $idx => $p) {
    $cats = wp_get_object_terms($p->ID, 'experience_category', array('fields' => 'names'));
    $cat_name = !empty($cats) ? implode(' > ', $cats) : 'No Cat';
    $img = get_post_meta($p->ID, '_st_cover_image', true);
    echo ($idx + 1) . ". ID: " . $p->ID . " | Title: " . $p->post_title . " | Cat: " . $cat_name . " | Img: " . substr($img, 0, 50) . "...\n";
}
