<?php /** Template Name: Page "Events" */ ?>
<?php get_header();
the_post(); ?>

<?php
$topicId = isset($_GET['topic']) ? $_GET['topic'] : null;
$topic = $topicId ? get_term($topicId) : null;

$page = get_query_var('paged');
if (!$page) {
    $page = 1;
}

$location = isset($_GET['location']) ? $_GET['location'] : null;
$date = isset($_GET['date']) ? $_GET['date'] : null;
?>

    <section class="section section-intro">
        <div class="section__container container">
            <header class="section__header">
                <span class="section-intro__title">
                    <? if ($date): ?>
                        Events on <?= $date ?>
                        <br/><br/>
                    <? elseif ($location): ?>
                        Events at "<?= $location ?>"
                        <br/><br/>
                    <? else: ?>
                        <? the_title(); ?>
                    <? endif; ?>
                </span>
            </header>
        </div>
    </section>


    <section class="section section-reports">
        <div class="section__container container">
            <div class="section__content">
                <div class="section-reports">
                    <? if (!$location && !$date): ?>
                        <div class="section-reports__tabs tabs">
                            <? $filter = get_query_var('filter'); ?>
                            <? if (!$filter): ?>
                                <span class="tabs__item active">All events</span>
                            <? else: ?>
                                <a href="<? the_permalink(); ?>" class="tabs__item">All events</a>
                            <? endif; ?>

                            <? if ($filter == 'upcoming'): ?>
                                <span class="tabs__item active">Upcoming</span>
                            <? else: ?>
                                <a href="<? the_permalink(); ?>upcoming" class="tabs__item">Upcoming</a>
                            <? endif; ?>

                            <? if ($filter == 'past'): ?>
                                <span class="tabs__item active">Past</span>
                            <? else: ?>
                                <a href="<? the_permalink(); ?>past" class="tabs__item">Past</a>
                            <? endif; ?>
                        </div>
                    <? endif; ?>

                    <?php
                    $query = [
                        'post_type' => 'event',
                        'paged' => $page,
                        'order' => $filter == 'upcoming' ? 'asc' : 'desc',
                        'orderby' => 'order_clause',
                    ];

                    $query['meta_query'] = [
                        'condition' => 'and',
                        'order_clause' => [
                            'key' => 'date',
                            'type' => 'DATE'
                        ]
                    ];

                    if ($filter) {
                        $query['meta_query'][] = [
                            [
                                'key' => 'date',
                                'value' => date('Y-m-d'),
                                'compare' => $filter == 'past' ? '<' : '>=',
                                'type' => 'DATE'
                            ],
                        ];
                    }

                    if ($date) {
                        $query['meta_query'][] = [
                            'key' => 'date',
                            'value' => $date,
                            'compare' => '=',
                            'type' => 'DATE'
                        ];
                    }

                    if ($location) {
                        $query['meta_query'][] = [
                            [
                                'key' => 'location',
                                'value' => $location,
                                'compare' => '=',
                            ],
                        ];
                    }


                    if ($topic) {
                        $query['tax_query'] = [
                            [
                                'taxonomy' => 'event_tag',
                                'field' => 'term_id',
                                'terms' => $topic->term_id
                            ]
                        ];
                    }

                    query_posts($query);
                    ?>

                    <?php if (have_posts()): ?>
                        <div class="tab-all">
                            <section class="section-tabs section-tabs--preview">

                                <? if (!$topic && $page == 1): ?>
                                    <? if (have_posts()): the_post(); ?>
                                        <ul class="grid grid-intro grid-intro--preview">
                                            <li class="card grid-preview__item card--main card--light card--static">
                                                <? \App\render('card', [
                                                    'model' => $post,
                                                    'thumbnail' => 'post-large'
                                                ]); ?>
                                            </li>
                                        </ul>
                                    <? endif; ?>
                                <? endif; ?>

                                <? if (have_posts()): ?>
                                    <div class="section-reports__filters filters">
                                        <form action="" method="GET" class="js-filter-form">
                                            <div class="filters__container">
                                                <div class="filters__item">
                                                    <? $tags = get_terms([
                                                        'taxonomy' => 'event_tag'
                                                    ]); ?>
                                                    <select class="js-dropdown-box" data-placeholder="All topics"
                                                            name="topic">
                                                        <option value="">All topics</option>
                                                        <? foreach ($tags as $tag):
                                                            if (get_field('hide_from_filter', $tag)) continue;
                                                            ?>
                                                            <option value="<?= $tag->term_id ?>" <?= isset($_GET['topic']) && $tag->term_id == $_GET['topic'] ? 'selected' : '' ?>><?= $tag->name ?></option>
                                                        <? endforeach; ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </form>
                                    </div>

                                    <ul class="grid grid-reports grid-reports--preview">
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
                            </section>
                        </div>
                    <? else: ?>
                        <span class="not-found"
                              style="text-align: center; display: block; font-size: 24px; font-weight: 500; line-height: 36px;">Not found</span>
                    <?php endif; ?>

                    <? wp_reset_query(); ?>
                    <? \App\pagination(); ?>
                </div>
            </div>
        </div>
    </section>

<?php get_footer();
