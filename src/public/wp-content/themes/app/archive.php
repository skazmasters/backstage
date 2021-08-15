<?php get_header(); ?>

<?php

$tagName = get_queried_object()->name;

$page = get_query_var('paged');
if (!$page) {
    $page = 1;
}

$categorySlug = get_query_var('category');
$category = empty($categorySlug) ? null : get_term_by('slug', $categorySlug, 'category');

$query = [
    'tag' => get_query_var('tag'),
    'order' => 'desc',
    'paged' => $page
];

$queriedTag = get_queried_object();

if ($queriedTag->taxonomy == 'event_tag') {
    $query['post_type'] = 'event';
} else {
    $query['post_type'] = 'post';
    $query['category_name'] = $categorySlug;
}

query_posts($query);
?>

<section class="section section-intro">
    <div class="section__container container">
        <header class="section__header">
            <? if (!empty($category)): ?>
                <span class="section-intro__label">
                <a href="<?= get_term_link($category) ?>"><?= apply_filters('the_title', $category->name) ?></a>
            </span>
            <? endif; ?>
            <span class="section-intro__title">Tag #<?php echo $tagName ?></span>
        </header>
    </div>
    <br/><br/> <br/><br/>
</section>

<section class="section section-reports">
    <div class="section__container container">
        <div class="section__content">
            <div class="section-reports">
                <section class="section-tabs section-tabs--preview">
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
                </section>
            </div>
        </div>
    </div>
</section>

<?php get_footer(); ?>
