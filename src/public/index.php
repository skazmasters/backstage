<?php

require_once dirname(__DIR__) . '/vendor/autoload.php';

/**
 * Use Dotenv to set required environment variables and load .env file in root
 */
(Dotenv\Dotenv::create(dirname(__DIR__))->safeLoad());

/**
 * Load Sentry
 */
if (!defined('SENTRY_LOADED') && getenv('SENTRY_DSN')) {
    Sentry\init([
        'dsn' => getenv('SENTRY_DSN'),
        'environment' => getenv('WP_ENV') ?: 'development',
    ]);
}
require_once dirname(__DIR__) . '/core/helpers.php';
require_once dirname(__DIR__) . '/core/bootstrap.php';

/** WordPress view bootstrapper */
define('WP_USE_THEMES', true);
require __DIR__ . '/wp/wp-blog-header.php';