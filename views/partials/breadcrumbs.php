<?php

/*
 Write a line of breadcrumbs, Bootstrap-style
*/

function breadcrumb_placeholder($name, $url) {
  return (object) array(
    'ID' => 0,
    'post_type' => 'page',
    'post_status' => 'publish',
    'guid' => $url,
    'post_name' => sanitize_title($name),
    'post_title' => $name,
    'permalink' => $url
    );
}

function locate_breadcrumbs($post) {
  $current_url = '';
  if (is_front_page() || is_404())
    return array();

  // for search results, show a summary of search terms
  if (is_search()) {
    return array(breadcrumb_placeholder(__('Search results'), $current_url));
  }


  // for single posts, either show page hierarchy up to that point, or a placeholder header
  if (is_single()) {

  }

  // for pages, show actual ancestors
  if (is_page()) {
    $pages = array_reverse(get_post_ancestors($post));
    $pages[] = $post;
    return $pages;
  }

  // ...

  return array();
}

$breadcrumbs = locate_breadcrumbs($post);
if (!empty($breadcrumbs))
  $breadcrumbs[count($breadcrumbs) - 1]->active = true;
$breadcrumbs = apply_filters('wireframe_b-breadcrumb_location', $breadcrumbs);
$breadcrumbs = array_values(array_filter($breadcrumbs));
do_action('log', 'Wb: $breadcrumbs', '!post_title,permalink', $breadcrumbs);

if (!empty($breadcrumbs)) {
  $home = (get_option('show_on_front') == 'page') ? 
    get_post(get_option('page_on_front')) : 
    breadcrumb_placeholder(get_bloginfo('name'), get_site_url('/'), empty($breadcrumbs));
  $home->post_title = get_bloginfo('name');
  array_unshift($breadcrumbs, $home);

  echo "<ol class='breadcrumb'>";
  foreach ($breadcrumbs as $crumb) {
    $title = $crumb->post_title;
    $permalink = isset($crumb->permalink) ? $crumb->permalink : get_post_permalink($crumb);
    $active = $crumb->active;

    echo "<li";
    if ($active) echo " class='active'";
    echo ">";
    if (!$active) echo "<a href='".esc_attr($permalink)."'>";
    echo esc_html($title);
    if (!$active) echo "</a>";
    echo "</li>";
  }
  echo "</ol>";
}

?>