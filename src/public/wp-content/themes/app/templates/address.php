<address>
    <ul>
        <? $email = get_field('email');
        if (!empty($email)): ?>
            <li>
                <i class="icon--email"></i>
                <b>Email:</b>
                <a href="mailto:<?= $email ?>"><?= $email ?></a>
            </li>
        <? endif; ?>

        <? $phone = get_field('phone');
        if (!empty($phone)): ?>
            <li>
                <i class="icon--phone"></i>
                <b>Phone:</b>
                <a href="tel:+<?= preg_replace('#[^\d]*#si', '', $phone) ?>"><?= $phone ?></a>
            </li>
        <? endif; ?>

        <? $address = get_field('address');
        $addressUrl = get_field('address_url');
        if (!empty($address)): ?>
            <li>
                <i class="icon--location"></i>
                <a href="<?= $addressUrl ?>" target="_blank"><b><?= $address ?></b></a>
            </li>
        <? endif; ?>
    </ul>
</address>