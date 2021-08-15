<?php

namespace App;

use function Clue\StreamFilter\fun;

function getMenuByThemeLocation($theme_location, $tree = false)
{
    $menu = get_term(get_nav_menu_locations()[$theme_location], 'nav_menu');
    if (!$menu) {
        return null;
    }

    $menu_items = wp_get_nav_menu_items($menu->term_id);

    if (!$tree) {
        return $menu_items;
    }

    $root_items = array_filter($menu_items, function ($menu_item) {
        return $menu_item->menu_item_parent == 0;
    });

    $root_items = array_map(function ($menu_item) {
        return [
            'post' => $menu_item,
            'children' => []
        ];
    }, $root_items);


    $root_items_filtered = [];
    foreach ($root_items as $item) {
        $root_items_filtered[$item['post']->ID] = $item;
    }

    foreach ($menu_items as $menu_item) {
        if ($menu_item->menu_item_parent != 0 && isset($root_items_filtered[$menu_item->menu_item_parent])) {
            $root_items_filtered[$menu_item->menu_item_parent]['children'][] = $menu_item;
        }
    }

    return $root_items_filtered;
}


function my_process_image_url($url)
{
    $result = $url;

    if (getenv('WP_IMAGES_HOME')) {
        return str_replace(getenv('WP_HOME'), getenv('WP_IMAGES_HOME'), $result);
    }

    return $result;
}

function renderImage($attachment_id, $size = 'full', $retina = false, $alt = '')
{
    if (!$attachment_id) return;
    if ($size === null) $size = 'full';

    $imageType = get_post_mime_type($attachment_id);
    $original = my_process_image_url(wp_get_attachment_image_url($attachment_id, $size));

    $originalImgHtml = '<img class="lazy" data-original="' . $original . '" src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" alt="' . $alt . '">';

    if ($imageType == 'image/svg+xml') {
        echo $originalImgHtml;
        return;
    }

    $output = '<picture>';

    if ($retina) {
        $image2x = is_integer($retina) ? my_process_image_url(wp_get_attachment_image_url($retina, 'full')) : my_process_image_url(wp_get_attachment_image_url($attachment_id, $size . '@2x'));
        $srcset = $original . ' 1x, ' . $image2x . ' 2x';
    } else {
        $image = wp_get_attachment_image_src($attachment_id, $size);
        $image_meta = wp_get_attachment_metadata($attachment_id);
        list($src, $width, $height) = $image;
        $size_array = array(absint($width), absint($height));
        $srcset = my_process_image_url(wp_calculate_image_srcset($size_array, $src, $image_meta, $attachment_id));
    }

    $output .= '<source data-srcset="' . $srcset . '" type="' . $imageType . '">';
    $output .= $originalImgHtml;
    $output .= '</picture>';

    echo $output;
}

function renderIcon($icon)
{
    echo '<svg class="icon icon-' . $icon . '">
                        <use xmlns:xlink="http://www.w3.org/1999/xlink"
                             xlink:href="' . get_template_directory_uri() . '/html/dist/assets/images/spriteInline.svg#' . $icon . '"/>
                    </svg>';
}

function renderTemplate($template_name)
{
    get_template_part('templates/' . $template_name);
}

/**
 * @param $template
 * @return \WP_Post
 */
function getPageByTemplate($template)
{
    $pages = get_pages(array(
        'meta_key' => '_wp_page_template',
        'meta_value' => $template . '.php'
    ));

    return count($pages) ? $pages[0] : null;
}

function render($template, $params = [], $return = false)
{
    foreach ($params as $param => $value) {
        set_query_var($param, $value);
    }

    if ($return) {
        ob_start();
    }

    get_template_part('templates/' . $template);

    if ($return) {
        $var = ob_get_contents();
        ob_end_clean();
        return $var;
    }
}

function formatDate($date)
{
    return date('F d, Y', strtotime(implode('-', array_reverse(explode('/', $date)))));
}

/**
 * @return array
 */
function getLocations()
{
    $result = [];

    $models = get_posts([
        'post_type' => 'event',
        'posts_per_page' => -1
    ]);

    foreach ($models as $model) {
        $modelLocation = trim(get_field('location', $model->id));
        if (!empty($modelLocation) && !in_array($modelLocation, $result)) {
            $result[] = $modelLocation;
        }
    }

    return $result;
}

function getTagsByCategory(\WP_Term $category)
{
    $terms = get_terms([
        'taxonomy' => 'post_tag'
    ]);

    $terms = array_filter($terms, function ($term) use ($category) {
        $found = get_posts([
            'tax_query' => [
                [
                    'taxonomy' => 'post_tag',
                    'field' => 'term_id',
                    'terms' => $term->term_id
                ],
                [
                    'taxonomy' => 'category',
                    'field' => 'term_id',
                    'terms' => $category->term_id
                ]
            ]
        ]);

        return $found != null;
    });

    return $terms;
}

function pagination_items($count, $current)
{
    if ($count < 9) {
        $result = [];
        for ($i = 1; $i <= $count; $i++) {
            $result[] = $i;
        }
        return $result;
    }

    $result = [1, 2];

    if ($current == 2) {
        $result[] = 3;
    }

    if ($current == 3) {
        $result[] = 3;
        $result[] = 4;
    }

    if ($current == 4) {
        $result[] = 3;
        $result[] = 4;
        $result[] = 5;
    }

    $result[] = null;

    if ($current > 4 && $current < $count - 2) {
        $result[] = $current - 1;
        $result[] = $current;
        $result[] = $current + 1;

        if ($current < $count - 3) {
            $result[] = null;
        }
    }

    if ($current == $count - 2) {
        $result[] = $current - 1;
        $result[] = $current;
        $result[] = $current + 1;
        $result[] = $current + 2;
    } else if ($current == $count - 1) {
        $result[] = $current - 1;
        $result[] = $current;
        $result[] = $current + 1;
    } else {
        $result[] = $count - 1;
        $result[] = $count;
    }

    return $result;
}

function pagination()
{
    global $wp_query, $paged;

    $pages = $wp_query->max_num_pages;
    if (!$pages) $pages = 1;

    if ($pages == 1) {
        return;
    }

    if (empty($paged)) $paged = 1;

    echo '<div class="section__pagination"><ul class="pagination">';

    foreach (pagination_items($pages, $paged) as $page) {
        echo '<li class="pagination__item ' . ($page == $paged ? 'active' : '') . '">';
        if ($page == null) {
            echo '...';
        } else {
            echo '<a class="pagination__link" href="' . get_pagenum_link($page) . '">' . $page . '</a>';
        }
        echo '</li>';
    }

    echo '</ul></div>';
}


function getRelatedPostsByPostTypeAndTaxonomy(\WP_Post $post, $post_type, $taxonomy)
{
    $postTagIds = array_map(function (\WP_Term $term) {
        return $term->term_id;
    }, wp_get_post_terms($post->ID, $taxonomy));

    return get_posts([
        'post_type' => $post_type,
        'posts_per_page' => 3,
        'post__not_in' => [$post->ID],
        'orderby' => 'publish_date',
        'order' => 'DESC',
        'tax_query' => array(
            array(
                'taxonomy' => $taxonomy,
                'field' => 'id',
                'operator' => 'in',
                'terms' => $postTagIds
            )
        )
    ]);
}

function getRelatedPosts(\WP_Post $post)
{
    if ($post->post_type == 'event') {
        return getRelatedPostsByPostTypeAndTaxonomy($post, 'event', 'event_tag');
    } else {
        return getRelatedPostsByPostTypeAndTaxonomy($post, 'post', 'post_tag');
    }
}

function getTagLink(\WP_Post $post, \WP_Term $term)
{
    if ($post->post_type == 'event') {
        return get_term_link($term);
    }

    $categoryTerms = wp_get_post_terms($post->ID, 'category');
    $categoryTerm = array_shift($categoryTerms);

    return get_category_link($categoryTerm) . 'tag/' . $term->slug;
}