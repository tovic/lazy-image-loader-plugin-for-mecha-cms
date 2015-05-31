<?php

if( ! isset($_GET['js']) || isset($_GET['js']) && (int) $_GET['js'] > 0) {

    // Redirect to `?js=0` if JavaScript is disabled
    Weapon::add('meta', function() use($config) {
        $q = $config->url_query;
        $q = strpos($q, '?') === 0 ? str_replace('&', '&amp;', $q) . '&amp;js=0' : '?js=0';
        echo O_BEGIN . '<noscript><meta http-equiv="refresh" content="0;URL=' . $config->url_current . $q . '"></noscript>' . O_END;
    });

    // Include the lazy image loader plugin
    Weapon::add('SHIPMENT_REGION_BOTTOM', function() {
        echo Asset::javascript('cabinet/plugins/' . basename(__DIR__) . '/sword/lazy-image-loader.js');
    });

    // Replace `src` attribute with `data-src` on images
    Filter::add('sanitize:output', function($content) {
        if(strpos($content, '<img ') === false) return $content;
        return preg_replace_callback('#(?<!<noscript>)\s*<img\s(.*?)(\s*\/?)>\s*(?!<\/noscript>)#', function($matches) {
            return '<img ' . preg_replace('#(^|\s)src=([\'"]?)#', '$1src=$2data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7$2 data-src=$2', $matches[1]) . $matches[2] . '>';
        }, $content);
    });

}