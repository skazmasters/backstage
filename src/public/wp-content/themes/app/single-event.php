<?php get_header();
the_post(); ?>

    <section class="section section-intro section-intro--reports">
        <div class="section__container container">
            <header class="section__header">
                <? $eventsPage = \App\getPageByTemplate('page-events');
                if ($eventsPage): ?>
                    <span class="section-intro__label">
                        <a href="<? the_permalink($eventsPage) ?>"><?= apply_filters('the_title', $eventsPage->post_title) ?></a>
                    </span>
                <? endif; ?>
                <h1 class="section-intro__title"><? the_title() ?></h1>
            </header>
        </div>
    </section>
    <section class="section section-base">
        <div class="section__container container">
            <div class="section__content">
                <ul class="grid grid-event">
                    <li class="card">
                        <article class="card__container">
                            <div class="card__event-text static">
                                <? the_content(); ?>
                            </div>
                            <div class="card__image ">
                                <div class="card__link card__image-link">
                                    <? \App\renderImage(get_post_thumbnail_id(), 'event', true); ?>
                                </div>
                            </div>
                            <div class="card__info">
                                <ul class="card-tags">
                                    <? foreach (wp_get_post_terms(get_the_ID(), 'event_tag') as $tag): ?>
                                        <li class="card-tags__item">
                                            <a class="card-tags__link"
                                               href="<?= get_term_link($tag) ?>">#<?= $tag->name ?></a>
                                        </li>
                                    <? endforeach; ?>
                                </ul>
                                <div class="card-event">
                                    <? if (!empty(get_field('location'))): ?>
                                        <div class="card-event__location">
                                            <span class="card-event__link">
                                              <span class="card-event__icon"><? \App\renderIcon('location') ?></span>
                                              <span class="card-event__text"><? the_field('location') ?></span>
                                            </span>
                                        </div>
                                    <? endif; ?>
                                    <div class="card-event__date">
                                        <div class="card-event__link">
                                            <span class="card-event__icon"><? \App\renderIcon('time') ?></span>
                                            <? if (!empty(get_field('date'))): ?>
                                                <time class="card-event__text card__pubdate"
                                                      datetime="<? strtotime('Y-m-d', get_field('date')) ?>"><?= \App\formatDate(get_field('date')) ?></time>
                                            <? else: ?>
                                                <time class="card-event__text card__pubdate"
                                                      datetime="<?= get_the_time('Y-m-d'); ?>"><?= get_the_time('F d, Y'); ?></time>
                                            <? endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </article>
                    </li>
                </ul>
            </div>
        </div>
    </section>

<?php \App\render('related-posts', [
    'title' => 'Related Events',
    'items' => \App\getRelatedPosts($post),
    'moreUrl' => get_permalink($eventsPage)
]) ?>

<?php get_footer(); ?>