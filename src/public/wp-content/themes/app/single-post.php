<?php get_header();
the_post(); ?>

<?php
$categoryTerms = wp_get_post_categories($post->ID, ['taxonomy' => 'category']);

$categoryTerm = array_shift($categoryTerms);
$categoryTerm = $categoryTerm ? get_term($categoryTerm) : null;

if ($categoryTerm) {
    $postCategoryUrl = get_term_link($categoryTerm);
    $postCategoryLabel = $categoryTerm->name;
}
?>
    <section class="section section-intro">
        <div class="section__container container">
            <header class="section-intro__header section-intro__header--reports">
                <span class="section-intro__label"><a
                            href="<?= $postCategoryUrl ?>"><?= $postCategoryLabel ?></a></span>
                <span class="section-intro__title"><? the_title(); ?></span>
            </header>
            <div class="section-intro__content publication">
                <ul class="publication__tags card-tags card-tags--normal">
                    <? foreach (wp_get_post_tags($post->ID) as $tag): ?>
                        <li class="card-tags__item">
                            <a class="card-tags__link" href="<?= get_term_link($tag) ?>">#<?= $tag->name ?></a>
                        </li>
                    <? endforeach; ?>
                </ul>

                <?

                if (get_field('type') == 'photo') {
                    $options = [
                        'author_name' => 'Photographer'
                    ];
                } else {
                    $options = [
                        'tv_host' => 'TV Host',
                        'cameraman' => 'Cameraman',
                    ];
                }

                foreach ($options as $option => $label):
                    $value = get_field($option);
                    if (!empty($value)): ?>
                        <p class="publication__info">
                            <?= $label ?> <span class="publication__info-name"><?php echo $value ?></span>
                        </p>
                    <? endif; ?>
                <? endforeach; ?>

                <div class="publication__date card-pubdate">
                    <time class="card-pubdate__time" datetime="<? the_time('Y-m-d') ?>"
                          tabindex="0"><? the_time('F d, Y') ?></time>
                </div>
                <div class="publication__sharing">
                    <span>Share</span>
                    <ul class="sharing-list  js-sharing-section">
                        <li class="sharing-list__item">
                            <button class="js-social-share" data-action="twitter" type="button">
                                <? \App\renderIcon('twitter'); ?>
                            </button>
                        </li>
                        <li class="sharing-list__item">
                            <button class="sharing-list__icon" data-action="pinterest">
                                <? \App\renderIcon('pinterest'); ?>
                            </button>
                        </li>
                        <li class="sharing-list__item">
                            <button class="js-social-share" data-action="facebook" type="button">
                                <? \App\renderIcon('facebook'); ?>
                            </button>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

<?php if (get_field('type') == 'video'): ?>
    <section class="section section-base section-base--report">
        <div class="section__container container">
            <div class="section__content">
                <div class="section-video-report">
                    <div class="card card--video-report">
                        <div class="card__image card__image--video">
                            <a data-lity href="https://www.youtube.com/watch?v=<? the_field('video_youtube_id') ?>"
                               class="card__link card__image-link">
                                <? \App\renderImage(get_post_thumbnail_id()); ?>
                                <div class="card__video-btn">
                                    <? \App\renderIcon('play'); ?>
                                </div>
                            </a>
                        </div>
                    </div>
                    <div class="static">
                        <? the_content(); ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php else: ?>

    <section class="section section-base section-base--report">
        <div class="section__container container">
            <div class="section__content">
                <div class="section-video-report">
                    <div class="card">
                        <div class="card__image">
                            <span class="card__link card__image-link">
                                <? \App\renderImage(get_post_thumbnail_id()); ?>
                            </span>
                        </div>
                    </div>
                    <div class="static">
                        <? the_content(); ?>
                    </div>
                    <div class="photogallery js-photogallery"
                         data-images-path="<?= get_template_directory_uri() ?>/html/dist/assets/images/common/logo.svg"
                         data-sprite-url="<?= get_template_directory_uri() ?>/html/dist/assets/images/spriteInline.svg"
                         data-pinterest-icon="<?= get_template_directory_uri() ?>/html/dist/assets/images/sharing/pinterest.svg"
                    >
                        <? $gallery = get_field('gallery');
                        foreach ($gallery as $item): ?>
                            <div class="photogallery__item">
                                <img class="photogallery__image lazy js-photogallery-image" data-sizes="auto"
                                     srcset="<?= wp_get_attachment_image_url($item['id'], 'gallery') ?>, <?= wp_get_attachment_image_url($item['id'], 'gallery@2x') ?> 2x"
                                     data-original="<?= wp_get_attachment_image_url($item['id'], 'original') ?>"
                                     data-thumbs="<?= wp_get_attachment_image_url($item['id'], 'gallery-thumb') ?>"
                                     src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw=="
                                     alt="" tabindex="0">
                            </div>
                        <? endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <button class="js-scroll-to-top" aria-label="Scroll to the top." tabindex="0"></button>
<?php endif; ?>

<?php \App\render('related-posts', [
    'title' => 'Related Posts',
    'items' => \App\getRelatedPosts($post),
    'moreUrl' => $postCategoryUrl
]) ?>

<? get_footer(); ?>