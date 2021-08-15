<?php /** Template Name: Page "Contacts" */ ?>
<?php get_header();
the_post(); ?>

    <section class="section section-base js-section-contacts">
        <div class="section__container container">
            <span class="main-title js-section-contacts__title"
                  data-success-title="Thank You!"><? the_title(); ?></span>

            <div class="grid grid-contacts js-section-contacts__grid">
                <div class="grid-contacts__info">
                    <p class="grid-contacts__text"><? the_field('text'); ?></p>
                </div>
                <div class="grid-contacts__address static-address">
                    <? \App\render('address'); ?>
                </div>
                <div class="grid-contacts__form">
                    <form class="form js-contacts-form" data-api-url="/wp/wp-admin/admin-ajax.php?action=contact">
                        <div class="form-wrapper">
                            <div class="form-wrapper__row">
                                <input class="form-input" type="text" name="name" placeholder="Name"
                                       autocomplete="off" data-validation="isNotEmpty">
                            </div>
                            <div class="form-wrapper__row">
                                <input class="form-input" type="email" name="email" placeholder="Email"
                                       inputmode="email" autocomplete="off" data-validation="isNotEmpty,isValidEmail">
                            </div>
                        </div>
                        <div class="form-row">
                            <input class="form-input" type="text" name="subject" placeholder="Subject"
                                   autocomplete="off" data-validation="isNotEmpty">
                        </div>
                        <div class="form-row">
                            <textarea class="form-area" name="message" id cols="30" rows="5"
                                      placeholder="Message" data-validation="isNotEmpty"></textarea>
                        </div>
                        <div class="form-row">
                            <button class="form-button" type="submit">Submit</button>
                        </div>
                    </form>
                </div>
                <div class="grid-contacts__success">
                    <p class="grid-contacts__text">
                        <? the_field('success_message'); ?>
                        <button class="btn grid-contacts__btn" type="button" data-success-button>Back</button>
                    </p>
                </div>
            </div>
        </div>
    </section>

<?php get_footer();
