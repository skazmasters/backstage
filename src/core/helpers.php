<?php

namespace OziTag\lib;

/**
 * @return bool
 */
function is_ssl()
{
    if (isset($_SERVER['HTTPS'])) {
        if ('on' == strtolower($_SERVER['HTTPS']))
            return true;
        if ('1' == $_SERVER['HTTPS'])
            return true;
    } elseif (isset($_SERVER['SERVER_PORT']) && ('443' == $_SERVER['SERVER_PORT'])) {
        return true;
    }
    return false;
}

/**
 * @return bool
 */
function is_cli()
{
    return (is_wp_cli() || (php_sapi_name() == 'cli' || (!empty($_SERVER['argc']) && is_numeric($_SERVER['argc']) && $_SERVER['argc'] > 0)));
}

/**
 * @return bool
 */
function is_webp_support()
{
    return strpos($_SERVER['HTTP_ACCEPT'], 'image/webp') !== false || strpos($_SERVER['HTTP_USER_AGENT'], ' Chrome/') !== false;
}

/**
 * @return bool
 */
function is_ajax()
{
    return (is_wp_ajax() ||
        (!empty($_GET['ajax']) && $_GET['ajax'] === '1') ||
        (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest')
    );
}

/**
 * @return bool
 */
function is_wp_cli()
{
    return (defined('WP_CLI') && WP_CLI);
}

/**
 * @return bool
 */
function is_wp_ajax()
{
    return (defined('DOING_AJAX') && DOING_AJAX === true);
}

if (!function_exists('dump')) {
    function dump($var)
    {
        return $var;
    }
}

/**
 * @param $var
 */
function dmp($var)
{
    if (getenv('WP_DEBUG')) {
        echo '<pre>';
        var_dump($var);
        echo '</pre>';
    }
}

/**
 * @param $var
 */
function dpr($var)
{
    if (getenv('WP_DEBUG')) {
        echo '<pre>';
        print_r($var);
        echo '</pre>';
    }
}

function container($service = null)
{
    static $container;

    if ($service === null) {
        return $container;
    }

    if ($service instanceof \DI\Container) {
        $container = $service;
        return $container;
    }

    return $container->get($service);
}
