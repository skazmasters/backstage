<?php get_header(); ?>

<section class="section section-intro">
    <div class="section__container container">
        <header class="section__header">
            <span class="section-intro__title"><?php echo get_queried_object()->name ?></span>
        </header>
    </div>
</section>

<?php
$topicId = isset($_GET['topic']) ? $_GET['topic'] : null;

$tag = get_query_var('post_tag');
$order = isset($_GET['order']) ? $_GET['order'] : null;
$topic = $topicId ? get_term($topicId) : null;

$page = get_query_var('paged');
if (!$page) {
    $page = 1;
}
?>

<section class="section section-reports">
    <div class="section__container container">
        <div class="section__content">
            <div class="section-reports">

                <div class="section-reports__tabs tabs">
                    <? $filter = get_query_var('filter'); ?>
                    <? if (!$filter): ?>
                        <span class="tabs__item active">All reports</span>
                    <? else: ?>
                        <a href="<?= get_term_link(get_query_var('category_name'), 'category') ?>" class="tabs__item">All
                            reports</a>
                    <? endif; ?>

                    <? if ($filter == 'photo'): ?>
                        <span class="tabs__item active">Photo</span>
                    <? else: ?>
                        <a href="<?= get_term_link(get_query_var('category_name'), 'category') ?>photo"
                           class="tabs__item">Photo</a>
                    <? endif; ?>

                    <? if ($filter == 'video'): ?>
                        <span class="tabs__item active">Video</span>
                    <? else: ?>
                        <a href="<?= get_term_link(get_query_var('category_name'), 'category') ?>video"
                           class="tabs__item">Video</a>
                    <? endif; ?>
                </div>

                <?php
                $query = [
                    'post_type' => 'post',
                    'category_name' => get_query_var('category_name'),
                    'order' => 'desc',
                    'paged' => $page
                ];

                $query['meta_query'] = [];

                if ($filter) {
                    $query['meta_query']['relation'] = 'and';
                    $query['meta_query'][] = [
                        'key' => 'type',
                        'value' => $filter
                    ];
                }

                if ($topic) {
                    $query['tax_query'] = [
                        [
                            'taxonomy' => 'post_tag',
                            'field' => 'term_id',
                            'terms' => $topic->term_id
                        ]
                    ];
                }

                if ($order == 'last') {
                    $query['orderby'] = 'published_date';
                }

                if ($order == 'popular') {
                    $query['orderby'] = 'order_clause';
                    $query['meta_query']['order_clause'] = [
                        'key' => 'views',
                        'type' => 'NUMERIC'
                    ];
                }

                query_posts($query);
                ?>

                <section class="section-tabs section-tabs--preview">
                    <? if (!have_posts()): ?>
                        <span class="not-found"
                              style="display:block; text-align: center; font-size: 24px; font-weight: 500; line-height: 36px;">Not found</span>
                    <? else: ?>
                        <? if (!$topic && $page == 1): ?>
                            <? if (have_posts()): the_post(); ?>
                                <ul class="grid grid-intro">
                                    <li class="card grid-intro__item card--main card--light swiper-slide">
                                        <? \App\render('card', [
                                            'model' => $post,
                                            'thumbnail' => 'post-large'
                                        ]); ?>
                                    </li>
                                </ul>
                            <? endif; ?>
                        <? endif; ?>
                        <? if (have_posts()): ?>
                            <? if ($page == 1): ?>
                                <div class="section-reports__filters filters">
                                    <form action="" method="GET" class="js-filter-form">
                                        <div class="filters__container">
                                            <div class="filters__item">
                                                <select class="js-dropdown-box" data-placeholder="All Reports"
                                                        name="order">
                                                    <option value="">All Reports</option>
                                                    <option value="popular" <?= $order == 'popular' ? 'selected' : '' ?>>
                                                        Popular Reports
                                                    </option>
                                                    <option value="last" <?= $order == 'last' ? 'selected' : '' ?>>
                                                        Last reports
                                                    </option>
                                                </select>
                                            </div>
                                            <div class="filters__item">
                                                <? $tags = \App\getTagsByCategory(get_queried_object()); ?>
                                                <select class="js-dropdown-box" data-placeholder="All topics"
                                                        name="topic">
                                                    <option value="">All topics</option>
                                                    <? foreach ($tags as $tag):
                                                        if (get_field('hide_from_filter', $tag)) continue;
                                                        ?>
                                                        <option value="<?= $tag->term_id ?>" <?= $tag && $tag->term_id == $topicId ? 'selected' : '' ?>><?= $tag->name ?></option>
                                                    <? endforeach; ?>
                                                </select>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            <? endif; ?>
                            <ul class="grid grid-reports">
                                <? $ind = 0;
                                while (have_posts()): the_post();
                                    $ind++ ?>
                                    <li class="card grid-reports__item">
                                        <? \App\render('card', [
                                            'model' => $post,
                                            'thumbnail' => ($ind % 2 === 1 ? 'post-list-big' : 'post-list-small'),
                                            'withArrow' => true
                                        ]); ?>
                                    </li>
                                <? endwhile; ?>
                            </ul>
                        <? endif; ?>
                    <? endif; ?>
                </section>

                <? \App\pagination(); ?>
            </div>
        </div>
    </div>
</section>

<?php get_footer(); ?>
