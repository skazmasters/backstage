<?php

add_action('init', 'init_post_types');
function init_post_types()
{
    $labels = array(
        'name' => _x('Events', 'post type general name', 'your-plugin-textdomain'),
        'singular_name' => _x('Event', 'post type singular name', 'your-plugin-textdomain'),
        'menu_name' => _x('Events', 'admin menu', 'your-plugin-textdomain'),
        'name_admin_bar' => _x('Event', 'add new on admin bar', 'your-plugin-textdomain'),
        'add_new' => _x('Add new', 'book', 'your-plugin-textdomain'),
        'add_new_item' => __('Add new event', 'your-plugin-textdomain'),
        'new_item' => __('New event', 'your-plugin-textdomain'),
        'edit_item' => __('Edit event', 'your-plugin-textdomain'),
        'view_item' => __('View event', 'your-plugin-textdomain'),
        'all_items' => __('All events', 'your-plugin-textdomain'),
        'search_items' => __('Search events', 'your-plugin-textdomain'),
        'not_found' => __('Events not found', 'your-plugin-textdomain'),
        'not_found_in_trash' => __('Events not found in trash', 'your-plugin-textdomain')
    );

    $args = array(
        'labels' => $labels,
        'public' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'query_var' => true,
        'capability_type' => 'post',
        'has_archive' => false,
        'hierarchical' => false,
        'menu_position' => null,
        'supports' => array('title', 'editor', 'thumbnail')
    );

    register_post_type('event', $args);

    $labels = array(
        'name' => _x('Tags', 'taxonomy general name', 'textdomain'),
        'singular_name' => _x('Tag', 'taxonomy singular name', 'textdomain'),
        'search_items' => __('Search tags', 'textdomain'),
        'popular_items' => __('Popular tags', 'textdomain'),
        'all_items' => __('All tags', 'textdomain'),
        'parent_item' => null,
        'parent_item_colon' => null,
        'edit_item' => __('Edit tags', 'textdomain'),
        'update_item' => __('Update tags', 'textdomain'),
        'add_new_item' => __('Add new tag', 'textdomain'),
        'new_item_name' => __('Name of tag', 'textdomain'),
        'separate_items_with_commas' => __('Separate tags with commas', 'textdomain'),
        'add_or_remove_items' => __('Add or remove tags', 'textdomain'),
        'choose_from_most_used' => __('Choose from most used', 'textdomain'),
        'not_found' => __('Tags not found', 'textdomain'),
        'menu_name' => __('Tags', 'textdomain'),
    );

    $args = array(
        'hierarchical' => false,
        'labels' => $labels,
        'show_ui' => true,
        'show_admin_column' => true,
        'query_var' => false,
    );

    register_taxonomy('event_tag', 'event', $args);


    $labels = array(
        'name' => _x('Contributors', 'post type general name', 'your-plugin-textdomain'),
        'singular_name' => _x('Contributor', 'post type singular name', 'your-plugin-textdomain'),
        'menu_name' => _x('Contributors', 'admin menu', 'your-plugin-textdomain'),
        'name_admin_bar' => _x('Contributor', 'add new on admin bar', 'your-plugin-textdomain'),
        'add_new' => _x('Add new', 'book', 'your-plugin-textdomain'),
        'add_new_item' => __('Add new contributor', 'your-plugin-textdomain'),
        'new_item' => __('New contributor', 'your-plugin-textdomain'),
        'edit_item' => __('Edit contributor', 'your-plugin-textdomain'),
        'view_item' => __('View contributor', 'your-plugin-textdomain'),
        'all_items' => __('All contributors', 'your-plugin-textdomain'),
        'search_items' => __('Search contributors', 'your-plugin-textdomain'),
        'not_found' => __('Contributors not found', 'your-plugin-textdomain'),
        'not_found_in_trash' => __('Contributors not found in trash', 'your-plugin-textdomain')
    );

    $args = array(
        'labels' => $labels,
        'public' => false,
        'show_ui' => true,
        'show_in_menu' => true,
        'query_var' => true,
        'capability_type' => 'post',
        'has_archive' => false,
        'hierarchical' => false,
        'menu_position' => null,
        'supports' => array('title', 'thumbnail')
    );

    register_post_type('contributor', $args);
}