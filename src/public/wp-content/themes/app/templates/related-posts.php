<?php
/** @var WP_Post[] $items */
/** @var string $moreUrl */
/** @var string $title */

?>

<?php if (!empty($items)): ?>
    <section class="section section-related section--bg">
        <div class="section__container container">
            <header class="section__header">
                <span class="section__title section__title--preview"><?= $title ?></span>
            </header>
            <div class="section__content">
                <section class="section-slider section-slider--no-gutters swiper-container js-slider-related">
                    <ul class="grid grid-related swiper-wrapper">
                        <? foreach ($items as $_post): ?>
                            <li class="card grid-related__item swiper-slide">
                                <? \App\render('card', [
                                    'model' => $_post,
                                    'thumbnail' => 'post-small'
                                ]); ?>
                            </li>
                        <? endforeach; ?>
                    </ul>
                    <div class="_mobile-visible swiper-pagination"></div>
                </section>
            </div>

            <? if (!empty($moreUrl)): ?>
                <footer class="section__footer">
                    <a href="<?= $moreUrl ?>" class="btn  btn-preview">
                        More
                    </a>
                </footer>
            <? endif; ?>
        </div>
    </section>
<?php endif; ?>