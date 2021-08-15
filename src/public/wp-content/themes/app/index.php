<?php get_header(); ?>

    <section class="section section-intro">
        <div class="section__container container">
            <header class="section__header">
                <h1 class="section-intro__title"><? the_title(); ?></h1>
            </header>
        </div>
    </section>
    <section class="section section-single">
        <div class="section__container container">
            <div class="section__content">
                <div class="privacy-wrapper">
                    <div class="static">
                        <? the_content(); ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

<?php get_footer();
