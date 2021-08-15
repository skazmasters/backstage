<?php get_header();
the_post(); ?>

    <section class="section section-intro section-intro--reports">
        <div class="section__container container">
            <header class="section__header section-intro__header">
                <span class="section-intro__title"><? the_field('title') ?></span>
            </header>
        </div>
    </section>

<?php foreach (['fashion', 'culture', 'art'] as $ind => $termId):
    $term = get_term_by('slug', $termId, 'category');
    $termPosts = get_field($termId . '_posts');
    if (!$term || empty($termPosts)) continue;
    ?>
    <section class="section section-base <?= $ind === 1 ? 'section--bg' : '' ?>">
        <div class="section__container container">
            <header class="section__header">
                <span class="section__title section__title--preview"><?= $term->name ?></span>
            </header>
            <div class="section__content">
                <? \App\render('home-posts', ['items' => $termPosts]); ?>
            </div>
            <footer class="section__footer">
                <a href="<?= get_term_link($term); ?>" class="btn  btn-preview">
                    More
                </a>
            </footer>
        </div>
    </section>
<?php endforeach; ?>

<?php
$eventsPage = \App\getPageByTemplate('page-events');
$events = get_field('events');
if (!empty($events)):
    ?>
    <section class="section section-base">
        <div class="section__container container">
            <header class="section__header">
                <span class="section__title section__title--preview"><?php echo apply_filters('the_title', $eventsPage->post_title) ?></span>
            </header>
            <div class="section__content">
                <? \App\render('home-posts', ['items' => $events]); ?>
            </div>
            <? if (!empty($eventsPage)): ?>
                <footer class="section__footer">
                    <a href="<?= get_permalink($eventsPage->ID) ?>" class="btn  btn-preview">
                        More
                    </a>
                </footer>
            <? endif; ?>
        </div>
    </section>
<?php endif; ?>

<?php get_footer();
