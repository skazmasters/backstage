<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, minimum-scale=1.0">
    <meta name="format-detection" content="telephone=no">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title><?php bloginfo('name'); ?> &raquo; <?php is_front_page() ? bloginfo('description') : wp_title(''); ?></title>

    <link rel="apple-touch-icon" sizes="180x180"
          href="<?= get_template_directory_uri() ?>/html/dist/assets/images/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32"
          href="<?= get_template_directory_uri() ?>/html/dist/assets/images/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16"
          href="<?= get_template_directory_uri() ?>/html/dist/assets/images/favicon/favicon-16x16.png">
    <link rel="manifest" href="<?= get_template_directory_uri() ?>/html/dist/assets/images/favicon/site.webmanifest"
          crossorigin="use-credentials">
    <link rel="mask-icon"
          href="<?= get_template_directory_uri() ?>/html/dist/assets/images/favicon/safari-pinned-tab.svg"
          color="#e02345">

    <meta name="msapplication-config"
          content="<?= get_template_directory_uri() ?>/html/dist/assets/images/favicon/browserconfig.xml">
    <meta name="msapplication-TileColor" content="#e02345">
    <meta name="theme-color" content="#ffffff">

    <link href="//fonts.googleapis.com/css2?family=Nunito+Sans:wght@400;600;700&family=Playfair+Display:wght@400;500;600&display=swap"
          rel="stylesheet">
    <link rel="stylesheet" href="<?= get_template_directory_uri() ?>/html/dist/css/main.css?v=375">

    <? wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<div class="preloader js-preloader"></div>
<div class="page__inner">

    <div class="page__inner">

        <header class="header page__header js-header">
            <div class="header__container container">
                <div class="header__content">
                    <div class="header__logo">
                        <a href="/">
                            <? \App\renderIcon('logo'); ?>
                        </a>
                    </div>
                    <div class="header__menu-toggler _smallTablet-visible">
                        <button class="menu-toggler js-mobile-menu" type="button">
                            <span class="menu-toggler__item"></span>
                            <span class="menu-toggler__item"></span>
                            <span class="menu-toggler__item menu-toggler__item--small"></span>
                        </button>
                    </div>
                    <div class="header__menu header-menu">
                        <div class="header-menu__container js-menu-container">
                            <div class="header-menu__content js-menu-content">
                                <? $headerMenu = \App\getMenuByThemeLocation('header_menu');
                                if (!empty($headerMenu)): ?>
                                    <nav class="header-menu__nav">
                                        <ul class="menu">
                                            <? foreach ($headerMenu as $ind => $headerMenuItem): ?>
                                                <li class="menu__item">
                                                    <a href="<?= $headerMenuItem->url ?>"
                                                       class="menu__link <?= $headerMenuItem->object_id == get_queried_object_id() ? 'menu__link--current' : '' ?>"><?= $headerMenuItem->title ?></a>
                                                </li>
                                            <? endforeach; ?>
                                        </ul>
                                    </nav>
                                <? endif; ?>
                                <div class="header-menu__search">
                                    <form class="header-menu__form" action="/">
                                        <div class="form-field ">
                                            <label class="form-label ">
                                                <input type="text" class="form-input" value="<?= get_query_var('s') ?>"
                                                       name="s" placeholder="Search">
                                                <button class="form-btn-search" type="submit"
                                                        aria-label="Search button.">
                                                    <? \App\renderIcon('search'); ?>
                                                </button>
                                            </label>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <main class="page__content <?= is_front_page() ? 'page__content--home' : '' ?>">