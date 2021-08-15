<?php
/**
 * Configuration overrides for WP_ENV === 'development'
 */

use Roots\WPConfig\Config;

Config::define('SAVEQUERIES', true);
Config::define('WP_DEBUG', true);
Config::define('WP_DEBUG_DISPLAY', false);
Config::define('WP_DEBUG_LOG', true);
Config::define('SCRIPT_DEBUG', true);

ini_set('display_errors', 1);
error_reporting(E_ALL);

// Enable plugin and theme updates and installation from the admin
Config::define('DISALLOW_FILE_MODS', false);

// Cache
Config::define('WP_CACHE', false);

/** @see https://wordpress.org/plugins/redis-cache/#description */
# Config::define('WP_REDIS_DISABLED', true);
# Config::define('WP_REDIS_CLIENT', 'pecl');

/** @see https://wordpress.org/plugins/autoptimize/ */
Config::define('DONOTMINIFY', true);
Config::define('CONCATENATE_SCRIPTS', false);
Config::define('COMPRESS_SCRIPTS', false);
Config::define('COMPRESS_CSS', false);
