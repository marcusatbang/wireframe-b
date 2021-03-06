<?php

namespace wireframe_b;

/*
Various hooks to make sure SSL links are always written with SSL, whether that's in menus, furniture or content.
*/

function correct_https($text) {
  $http_url  = memoise('http_url',  function () {  return str_replace('https:', 'http:', site_url('/'));  });
  $https_url = memoise('https_url', function () {  return str_replace('http:', 'https:', site_url('/'));  });

  if (is_ssl()) {
    $text = str_replace($http_url, $https_url, $text);
  } else {
    $text = str_replace($https_url, $http_url, $text);
  }
  return $text;
}

add_filter('the_content', __NAMESPACE__.'\correct_https', 1);

add_filter('script_loader_src', __NAMESPACE__.'\correct_https');
add_filter('style_loader_src', __NAMESPACE__.'\correct_https');

add_filter('wp_get_nav_menu_items', function ($items, $menu, $args) {
  $newitems = array();
  foreach ($items as $item) {
    if (is_object($item) && is_string($item->url)) {
      $item->url = correct_https($item->url);
    }
    $newitems[] = $item;
  }
  return $newitems;
}, 10, 3);

add_filter('post_thumbnail_html', __NAMESPACE__.'\correct_https');

add_filter('widget_title', __NAMESPACE__.'\correct_https');
add_filter('widget_text', __NAMESPACE__.'\correct_https');
