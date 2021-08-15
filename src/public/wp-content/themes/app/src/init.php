<?php

add_action('init', 'custom_init');
function custom_init()
{
    add_theme_support('post-thumbnails');

    add_image_size('post-small', 366, 207, true);
    add_image_size('post-small@2x', 366 * 2, 207 * 2, true);

    add_image_size('post-middle', 570, 322, true);
    add_image_size('post-middle@2x', 570 * 2, 322 * 2, true);

    add_image_size('post-big', 773, 421, true);
    add_image_size('post-big@2x', 773 * 2, 421 * 2, true);

    add_image_size('post-large', 1180, 140);

    add_image_size('event-small', 366, 244, true);
    add_image_size('event-small@2x', 366 * 2, 244 * 2, true);

    add_image_size('post-list-small', 500, 330, true);
    add_image_size('post-list-big', 835, 553, true);

    add_image_size('gallery', 366);
    add_image_size('gallery@2x', 366 * 2);

    add_image_size('gallery-thumb', 100);
    add_image_size('gallery-thumb@2x', 100 * 2);

    add_image_size('contributor', 481, 481, true);
    add_image_size('contributor@2x', 481 * 2, 481 * 2, true);

    add_image_size('event', 705, 465, true);
    add_image_size('event@2x', 705 * 2, 465 * 2, true);

    add_image_size('search-thumb', 500, 330, true);
    add_image_size('search-thumb@2x', 500 * 2, 330 * 2, true);
}

add_action('after_setup_theme', 'register_my_menu');
function register_my_menu()
{
    register_nav_menu('header_menu', 'Header Menu');
    register_nav_menu('footer_menu', 'Footer Menu');
}

add_filter('body_class', function ($classes) {
    if (\OziTag\lib\is_webp_support()) {
        $classes[] = 'webp';
    }

    return $classes;
});
