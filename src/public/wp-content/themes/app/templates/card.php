<?php
/** @var WP_Post $model */
/** @var string $thumbnail */
/** @var string $searchQuery */


$postCategoryUrl = null;
$postCategoryLabel = null;
$withArrow = isset($withArrow) ? (bool)$withArrow : false;

if ($model->post_type == 'event') {
    $eventsPage = \App\getPageByTemplate('page-events');
    $postCategoryUrl = $eventsPage ? get_permalink($eventsPage) : '/preview';
    $postCategoryLabel = $eventsPage ? apply_filters('the_title', $eventsPage->post_title) : null;
} else {
    $categoryTerms = wp_get_post_terms($model->ID, 'category');
    $categoryTerm = array_shift($categoryTerms);

    if ($categoryTerm) {
        $postCategoryUrl = get_term_link($categoryTerm);
        $postCategoryLabel = apply_filters('the_title', $categoryTerm->name);
    }
}

$tags = $model->post_type == 'event' ? wp_get_post_terms($model->ID, 'event_tag') : wp_get_post_tags($model->ID);

$isVideo = $model->post_type != 'event' && get_field('type', $model) == 'video';
?>

<article class="card__container">
    <div class="card__image <?= $isVideo ? 'card__image--video' : '' ?>">
        <a class="card__link card__image-link" href="<?= get_permalink($model) ?>">
            <? \App\renderImage(get_post_thumbnail_id($model->ID), $thumbnail, $thumbnail != 'post-large'); ?>
            <? if ($isVideo): ?>
                <div class="card__video-btn"><? \App\renderIcon('play') ?></div>
            <? endif; ?>
        </a>
    </div>
    <div class="card__info">
        <? if ($postCategoryLabel && $postCategoryUrl): ?>
            <p class="card__label">
                <a class="card__link card__label-link" href="<?= $postCategoryUrl ?>"><?= $postCategoryLabel ?></a>
            </p>
        <? endif; ?>
        <span class="card__title">
          <a class="card__link card__title-link" href="<?= get_permalink($model) ?>">
              <? if (!empty($searchQuery)): ?>
                  <?= preg_replace('#(' . $searchQuery . ')#su', '<mark>$1</mark>', apply_filters('the_title', $model->post_title)); ?>
              <? else: ?>
                  <?= apply_filters('the_title', $model->post_title) ?>
              <? endif; ?>
          </a>
        </span>
        <? if (!empty($tags)): ?>
            <ul class="card-tags">
                <? foreach ($tags as $tag): ?>
                    <li class="card-tags__item">
                        <a class="card-tags__link" href="<?= \App\getTagLink($model, $tag) ?>">
                            <? if (!empty($searchQuery)): ?>
                                <?= preg_replace('#(\#' . $searchQuery . ')#su', '<mark>$1</mark>', '#' . $tag->name); ?>
                            <? else: ?>
                                #<?= $tag->name ?>
                            <? endif; ?>
                        </a>
                    </li>
                <? endforeach; ?>
            </ul>
        <? endif; ?>
        <? if ($model->post_type == 'event'): ?>
            <? $eventsPage = \App\getPageByTemplate('page-events'); ?>
            <div class="card-event">
                <? if (!empty(get_field('location', $model->ID))): ?>
                    <div class="card-event__location">
                        <a href="<?= get_permalink($eventsPage) ?>?location=<?= get_field('location', $model->ID) ?>"
                           class="card-event__link">
                            <span class="card-event__icon"><? \App\renderIcon('location') ?></span>
                            <span class="card-event__text"><? the_field('location', $model->ID) ?></span>
                        </a>
                    </div>
                <? endif; ?>

                <? if (!empty(get_field('date', $model->ID))): ?>
                    <div class="card-event__date">
                        <a href="<?= get_permalink($eventsPage) ?>?date=<?= date('Y-m-d', strtotime(implode('-', explode('/', get_field('date', $model->ID))))); ?>"
                           class="card-event__link">
                            <span class="card-event__icon"><? \App\renderIcon('time') ?></span>
                            <time class="card-event__text card__pubdate"
                                  datetime="<?= date('Y-m-d', strtotime(implode('-', explode('/', get_field('date', $model->ID))))); ?>">
                                <?= \App\formatDate(get_field('date', $model->ID)); ?>
                            </time>
                        </a>
                    </div>
                <? endif; ?>
            </div>
        <? else: ?>
            <div class="card-pubdate">
                <time class="card-pubdate__time"
                      datetime="<?= get_the_time('Y-m-d', $model); ?>"><?= get_the_time('F d, Y', $model); ?></time>
            </div>

            <? if ($withArrow): ?>
                <div class="grid-reports__arrow">
                    <a href="<?= get_permalink($model) ?>" class="grid-reports__link">
                        <? \App\renderIcon('report-arrow'); ?>
                    </a>
                </div>
            <? endif; ?>
        <? endif; ?>
    </div>
</article>