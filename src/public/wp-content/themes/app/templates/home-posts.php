<?php /** @var WP_Post[] $items */ ?>

<?php if (count($items) <= 2): ?>
    <ul class="grid grid--two">
        <? foreach ($items as $_post): ?>
            <li class="card grid-culture__item">
                <? \App\render('card', ['model' => $_post, 'thumbnail' => 'post-middle']); ?>
            </li>
        <? endforeach; ?>
    </ul>
<?php elseif (count($items) === 3): ?>
    <section class="section-slider swiper-container js-slider-home">
        <ul class="grid grid--three-preview swiper-wrapper">
            <? foreach ($items as $_post): ?>
                <li class="card swiper-slide">
                    <? \App\render('card', ['model' => $_post, 'thumbnail' => 'event-small']); ?>
                </li>
            <? endforeach; ?>
        </ul>
        <div class="_mobile-visible swiper-pagination"></div>
    </section>
<?php elseif (count($items) === 4): ?>
    <section class="section-slider swiper-container js-slider-home">
        <ul class="grid grid--four swiper-wrapper">
            <? foreach ($items as $ind => $_post): ?>
                <? if ($ind == 0): ?>
                    <li class="card grid-fashion__item card--main card--light swiper-slide">
                        <? \App\render('card', ['model' => $_post, 'thumbnail' => 'post-large']); ?>
                    </li>
                <? else: ?>
                    <li class="card grid-fashion__item swiper-slide">
                        <? \App\render('card', ['model' => $_post, 'thumbnail' => 'post-small']); ?>
                    </li>
                <? endif; ?>
            <? endforeach; ?>
        </ul>
        <div class="_mobile-visible swiper-pagination"></div>
    </section>
<?php elseif (count($items) === 5): ?>
    <section class="section-slider swiper-container js-slider-home">
        <ul class="grid grid--five swiper-wrapper">
            <? foreach ($items as $ind => $_post): ?>
                <? if ($ind == 0): ?>
                    <li class="card grid-art__item card--main card--light swiper-slide">
                        <? \App\render('card', ['model' => $_post, 'thumbnail' => 'post-big']); ?>
                    </li>
                <? else: ?>
                    <li class="card grid-art__item swiper-slide">
                        <? \App\render('card', ['model' => $_post, 'thumbnail' => 'post-small']); ?>
                    </li>
                <? endif; ?>
            <? endforeach; ?>
        </ul>
        <div class="_mobile-visible swiper-pagination"></div>
    </section>
<?php endif; ?>