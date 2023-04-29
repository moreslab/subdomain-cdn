<?php
/*
	Plugin Name: Subdomain CDN Image 
	Text Domain: subdomain-cdn-image
	Description: subdomain-cdn-image
  Version:     1.0
  Author:      moreslab
  Plugin URI:  https://github.com/moreslab/subdomain-cdn
  Author URI:  https://github.com/moreslab/
	License: GPLv2 or later
*/

// Add .cdn after http:// or https://
function moreslab_to_cdn($src) {
    $moreslab_cdn_domain = 'cdn.';
    $dslash_pos = strpos($src, '//') + 2;

    $src_pre  = substr($src, 0, $dslash_pos); // http:// or https://
    $src_post = substr($src, $dslash_pos); // The rest after http:// or https://

    return $src_pre . $moreslab_cdn_domain . $src_post;
}

function moreslab_cdn_get_attachment_image_src($image, $attachment_id, $size, $icon) {
    if(!is_admin()) {
        if(!image) {
            return false;
        }

        if(is_array($image)) {
            $src = moreslab_to_cdn($image[0]); // To CDN
            $width = $image[1];
            $height = $image[2];

            return [$src, $width, $height, true];

        } else {
            return false;
        }
    }

  return $image;

}
add_filter('wp_get_attachment_image_src', 'moreslab_cdn_get_attachment_image_src', 10, 4);

function moreslab_cdn_calculate_image_srcset($sources, $size_array, $image_src, $image_meta, $attachment_id) {
    if(!is_admin()) {
        $images = [];

        foreach($sources as $source) {
            $src = moreslab_to_cdn($source['url']); // To CDN
            $images[] = [
                'url' => $src,
                'descriptor' => $source['descriptor'],
                'value' => $source['value']
            ];
        }

        return $images;
    }

  return $sources;
}
add_filter('wp_calculate_image_srcset', 'moreslab_cdn_calculate_image_srcset', 10, 5);
