<?php /** Template Name: Page "Privacy Policy" */ ?>
<?php get_header();
the_post(); ?>

    <section class="section section-intro">
        <div class="section__container container">
            <header class="section__header">
                <span class="section-intro__title"><? the_title(); ?></span>
            </header>
        </div>
    </section>

    <section class="section section-single">
        <div class="section__container container">
            <div class="section__content">
                <div class="privacy-wrapper">
                    <div class="static static--privacy">
                        <? the_content(); ?>
                        <? \App\render('address'); ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

<?php get_footer();
