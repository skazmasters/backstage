<?php /** Template Name: Page "Contributors" */ ?>
<?php get_header();
the_post(); ?>
    <section class="section section-intro">
        <div class="section__container container">
            <header class="section__header">
                <span class="section-intro__title"><? the_title(); ?></span>
            </header>
        </div>
    </section>
<?php query_posts(['post_type' => 'contributor', 'posts_per_page' => -1]);
if (have_posts()): ?>
    <section class="section section-base">
        <div class="section__container container">
            <div class="section__content">
                <div class="section-contributors">
                    <ul class="contributor-list">
                        <? while (have_posts()): the_post(); ?>
                            <li class="contributor">
                                <div class="contributor__image">
                                    <? \App\renderImage(get_post_thumbnail_id(), 'contributor', true); ?>
                                </div>
                                <div class="contributor__description">
                                    <span class="contributor__name"><? the_title(); ?></span>
                                    <p class="contributor__info"><? the_field('text'); ?></p>
                                    <ul class="contributor__socials">
                                        <? $instagramUrl = get_field('instagram_url');
                                        if (!empty($instagramUrl)): ?>
                                            <li class="contributor__socials-item">
                                                <a class="contributor__socials-link contributor__socials-link--instagram"
                                                   href="<?= $instagramUrl ?>" target="_blank">instagram</a>
                                            </li>
                                        <? endif; ?>
                                    </ul>
                                </div>
                            </li>
                        <? endwhile; ?>
                    </ul>
                </div>

            </div>
        </div>
    </section>
<?php endif; ?>


<?php get_footer();
