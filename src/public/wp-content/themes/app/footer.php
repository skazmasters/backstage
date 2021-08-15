</main>

<footer class="footer page__footer">
    <div class="footer__container container">
        <div class="footer__content">
            <div class="footer__top">
                <? $footerMenu = \App\getMenuByThemeLocation('footer_menu');
                if (!empty($footerMenu)): ?>
                    <nav class="footer__nav">
                        <ul class="menu">
                            <? foreach ($footerMenu as $footerMenuItem): ?>
                                <li class="menu__item">
                                    <a href="<?= $footerMenuItem->url ?>"
                                       class="menu__link <?= $footerMenuItem->object_id == get_queried_object_id() ? 'menu__link--current' : '' ?>"><?= $footerMenuItem->title ?></a>
                                </li>
                            <? endforeach; ?>
                        </ul>
                    </nav>
                <? endif; ?>
            </div>
            <div class="footer__bottom js-footer-bottom">
                <div class="footer__credits">
                    <?
                    $footerLeftLinkLabel = get_field('footer_left_link_label', get_option('page_on_front'));
                    $footerLeftLinkUrl = get_field('footer_left_link_url', get_option('page_on_front'));
                    ?>
                    <? if (!empty($footerLeftLinkLabel)): ?>
                        <a href="<?= $footerLeftLinkUrl ?>"><?= $footerLeftLinkLabel ?></a>
                    <? endif; ?>
                </div>
                <div class="footer__copy">&copy; Backstage <span><?= date('Y') ?></span></div>
                <div class="footer__socials">
                    <div class="footer__dev">
                        Developed&nbsp;by&nbsp;<a href="https://agentestudio.com/" target="_blank">Agente</a>
                    </div>
                    <ul class="socials">
                        <? $youtubeUrl = get_field('youtube_url', get_option('page_on_front')); ?>
                        <? if (!empty($youtubeUrl)): ?>
                            <li class="socials__item">
                                <a href="<?= $youtubeUrl ?>" class="socials__link socials__link--youtube"
                                   target="_blank"
                                   aria-label="Link to Youtube"></a>
                            </li>
                        <? endif; ?>
                        <? $instagramUrl = get_field('instagram_url', get_option('page_on_front')); ?>
                        <? if (!empty($instagramUrl)): ?>
                            <li class="socials__item">
                                <a href="<?= $instagramUrl ?>" class="socials__link socials__link--instagram"
                                   target="_blank"
                                   aria-label="Link to instagram"></a>
                            </li>
                        <? endif; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</footer>

</div>

<script src="<?= get_template_directory_uri() ?>/html/dist/js/vendors.js"></script>
<script src="<?= get_template_directory_uri() ?>/html/dist/js/main.js?v=2"></script>
<script src="<?= get_template_directory_uri() ?>/custom.js"></script>

<?php wp_footer(); ?>

</body>
</html>
