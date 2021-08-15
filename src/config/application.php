<?php
/**
 * Your base production configuration goes in this file. Environment-specific
 * overrides go in their respective config/environments/{{WP_ENV}}.php file.
 *
 * A good default policy is to deviate from the production config as little as
 * possible. Try to define as much of your configuration in this file as you
 * can.
 */

use Roots\WPConfig\Config;

/** @var string Directory containing all of the site's files */
$root_dir = dirname(__DIR__);

/** @var string Document Root */
$webroot_dir = $root_dir . '/public';

/**
 * Expose global env() function from oscarotero/env
 */
Env::init();

/**
 * Use Dotenv to set required environment variables and load .env file in root
 */
$dotenv = Dotenv\Dotenv::create($root_dir);
if (file_exists($root_dir . '/.env')) {
    $dotenv->load();
    if (!env('DATABASE_URL')) {
        $dotenv->required(['DB_NAME', 'DB_USER', 'DB_PASSWORD']);
    }
}

/**
 * Set up our global environment constant and load its config first
 * Default: production
 */
define('WP_ENV', env('WP_ENV') ?: 'production');

/**
 * Multisite
 */
Config::define('WP_ALLOW_MULTISITE', false);
Config::define('MULTISITE', false);
Config::define('SUBDOMAIN_INSTALL', true);
Config::define('DOMAIN_CURRENT_SITE', env('MULTISITE_DOMAIN') ?: 'localhost');
Config::define('PATH_CURRENT_SITE', '/');
Config::define('SITE_ID_CURRENT_SITE', 1);
Config::define('BLOG_ID_CURRENT_SITE', 1);
Config::define('WP_DEFAULT_THEME', 'app');

/**
 * URLs
 */
Config::define('WP_HOME', env('WP_HOME')
    ?: (!empty($_SERVER['HTTP_HOST']) ? (OziTag\lib\is_ssl() ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] : ''));

Config::define('WP_SITEURL', env('WP_SITEURL')
    ?: (!empty($_SERVER['HTTP_HOST']) ? (OziTag\lib\is_ssl() ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/wp' : '')
    ?: Config::get('WP_HOME') . '/wp');

/**
 * WP-CLI
 */
if (!isset($_SERVER['SERVER_NAME'])) {
    $_SERVER['SERVER_NAME'] = parse_url(Config::get('WP_HOME'), PHP_URL_HOST);
}

/**
 * Custom Content Directory
 */
Config::define('CONTENT_DIR', '/wp-content');
Config::define('WP_CONTENT_DIR', $webroot_dir . Config::get('CONTENT_DIR'));
Config::define('WP_CONTENT_URL', env('WP_CONTENT_URL') ? env('WP_CONTENT_URL') : Config::get('WP_HOME') . Config::get('CONTENT_DIR'));

/**
 * DB settings
 */
Config::define('DB_NAME', env('DB_NAME'));
Config::define('DB_USER', env('DB_USER'));
Config::define('DB_PASSWORD', env('DB_PASSWORD'));
Config::define('DB_HOST', env('DB_HOST') ?: 'localhost');
Config::define('DB_CHARSET', 'utf8mb4');
Config::define('DB_COLLATE', '');
$table_prefix = env('DB_PREFIX') ?: 'wp_';

if (env('DATABASE_URL')) {
    $dsn = (object) parse_url(env('DATABASE_URL'));

    Config::define('DB_NAME', substr($dsn->path, 1));
    Config::define('DB_USER', $dsn->user);
    Config::define('DB_PASSWORD', isset($dsn->pass) ? $dsn->pass : null);
    Config::define('DB_HOST', isset($dsn->port) ? "{$dsn->host}:{$dsn->port}" : $dsn->host);
}

/**
 * Authentication Unique Keys and Salts
 */
Config::define('AUTH_KEY', env('AUTH_KEY'));
Config::define('SECURE_AUTH_KEY', env('SECURE_AUTH_KEY'));
Config::define('LOGGED_IN_KEY', env('LOGGED_IN_KEY'));
Config::define('NONCE_KEY', env('NONCE_KEY'));
Config::define('AUTH_SALT', env('AUTH_SALT'));
Config::define('SECURE_AUTH_SALT', env('SECURE_AUTH_SALT'));
Config::define('LOGGED_IN_SALT', env('LOGGED_IN_SALT'));
Config::define('NONCE_SALT', env('NONCE_SALT'));

/**
 * Allow WordPress to detect HTTPS when used behind a reverse proxy or a load balancer
 * See https://codex.wordpress.org/Function_Reference/is_ssl#Notes
 */
if (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') {
    $_SERVER['HTTPS'] = 'on';
}

/**
 * SSL Admin
 */
if (stripos(Config::get('WP_SITEURL'), 'https://') === 0) {
    Config::define('FORCE_SSL_ADMIN', true);
    if (Config::get('FORCE_SSL_ADMIN')) {
        $_SERVER['HTTPS'] = 'on';
    }
}

/**
 * Cache
 */
Config::define('WP_CACHE', true);
Config::define('WPCACHEHOME', Config::get('WP_CONTENT_DIR') . '/plugins/wp-super-cache/');
Config::define('WP_CACHE_KEY_SALT', env('WP_CACHE_KEY_SALT') ?: str_replace('/', '_', preg_replace('#^https?://#uisU', '', Config::get('WP_HOME')) . '_'));
Config::define('WP_REDIS_CLIENT', 'pecl');

/**
 * Cookies
 */
$cookie_hash = hash('sha256', (Config::get('MULTISITE', false) && Config::get('SUBDOMAIN_INSTALL', true)) ? Config::get('WP_SITEURL') : Config::get('WP_HOME'));
define('TEST_COOKIE', 'wp_tc');
define('LOGGED_IN_COOKIE', 'wp_li_' . $cookie_hash);
define('SECURE_AUTH_COOKIE', 'wp_sa_' . $cookie_hash);
define('AUTH_COOKIE', 'wp_a_' . $cookie_hash);
define('PASS_COOKIE', 'wp_p_' . $cookie_hash);
define('USER_COOKIE', 'wp_u_' . $cookie_hash);
unset($cookie_hash);

/**
 * Custom Settings
 */
Config::define('AUTOMATIC_UPDATER_DISABLED', true);
Config::define('DISABLE_WP_CRON', env('DISABLE_WP_CRON') ?: false);
// Disable the plugin and theme file editor in the admin
Config::define('DISALLOW_FILE_EDIT', true);
// Disable plugin and theme updates and installation from the admin
Config::define('DISALLOW_FILE_MODS', true);
// Plugin: Contact Form 7
Config::define('WPCF7_AUTOP', false);

/**
 * Debugging Settings
 */
Config::define('WP_DEBUG_DISPLAY', false);
Config::define('SCRIPT_DEBUG', false);
ini_set('display_errors', 0);

$env_config = __DIR__ . '/environments/' . WP_ENV . '.php';

if (file_exists($env_config)) {
    require_once $env_config;
}

Config::apply();

/**
 * Bootstrap WordPress
 */
if (!defined('ABSPATH')) {
    define('ABSPATH', $webroot_dir . '/wp/');
}
