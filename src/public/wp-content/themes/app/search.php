<?php get_header();

$postsAll = get_posts([
    's' => get_query_var('s'),
    'post_type' => ['post'],
    'posts_per_page' => -1
]);

$terms = get_terms([
    'taxonomy' => 'category'
]);

$counts = [];
foreach ($terms as $term) {
    $counts[$term->term_id] = 0;
}

foreach ($postsAll as $post) {
    $category = wp_get_post_categories($post);
    foreach (wp_get_post_terms($post->ID, 'category') as $category) {
        $counts[$category->term_id]++;
    }
}

?>

<section class="section section-base section-search">
    <div class="section__container container">
        <div class="section__content">
            <div class="grid-aside">
                <div class="grid-aside__header _mobile-hidden">
                    <span class="search-aside__title">Search</span>
                </div>
                <aside class="search-aside">
                    <div class="search-aside__results collapse js-mobile-collapse active">
                        <div class="search-aside__total-wrapper">
                            <button class="search-aside__total collapse-btn" type="button">
                                <span class="search-aside__total-counts"><?= count($postsAll) ?></span>
                                Results found
                            </button>
                        </div>
                        <div class="collapse-content">
                            <ul class="search-results">
                                <? foreach ($terms as $term): ?>
                                    <li class="search-results__item">
                                        <div class="search-results__label"><?= $term->name ?></div>
                                        <div class="search-results__counts"><?= $counts[$term->term_id] ?></div>
                                    </li>
                                <? endforeach; ?>
                            </ul>
                        </div>
                    </div>
                </aside>

                <form class="search-form js-search-form" action="/">
                    <div class="search-form__row">
                        <label class="search-form__label">
                            <input type="text" value="<?= get_query_var('s') ?>" class="search-form__input"
                                   name="s" placeholder="Search"
                                   autocomplete="off">
                            <button class="search-form__btn search-form__btn--reset" type="reset">
                                <? \App\renderIcon('reset'); ?>
                            </button>
                            <button class="search-form__btn search-form__btn--search" type="submit"
                                    aria-label="Search button.">
                                <? \App\renderIcon('search'); ?>
                            </button>
                        </label>
                    </div>
                </form>
                <section class="search-content">
                    <ul class="grid grid-search">
                        <? query_posts([
                            's' => get_query_var('s'),
                            'post_type' => ['post'],
                            'paged' => get_query_var('paged')
                        ]); ?>

                        <? while (have_posts()): the_post(); ?>
                            <li class="card">
                                <? \App\render('card', [
                                    'model' => $post,
                                    'thumbnail' => 'search-thumb',
                                    'searchQuery' => get_query_var('s')
                                ]); ?>
                            </li>
                        <? endwhile; ?>
                    </ul>
                </section>
            </div>

            <? \App\pagination() ?>
        </div>
    </div>
</section>

<?php get_footer(); ?>
