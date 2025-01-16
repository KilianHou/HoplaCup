<?php

if (isset($division) && $division) {
    $sub_url = '/' . $division . $url_division;
} else {
    $sub_url = '';
}



$fr_link = site_url() . '/' . $public_root_slug . '' . $sub_url;
$de_link = site_url() . '/' . $public_root_slug . '/de' . $sub_url;
$en_link = site_url() . '/' . $public_root_slug . '/en' . $sub_url;



?>
<div id="lang-switch">
    <ul>
        <li <?= $lang === '' ? 'class="active"' : ''; ?>>
            <a href="<?= $fr_link ?>">
                <div class="icon-container<?= $lang === '' ? ' active' : ''; ?>">
                    <img src="<?= dirname(plugin_dir_url(__FILE__))?>/images/icones_drapeaux/flag-fr.png" alt="FR" class="icon">
                    <span class="lang-label">FR</span>
                </div>
            </a>
        </li>
        <li <?= $lang === 'de' ? 'class="active"' : ''; ?>>
            <a href="<?= $de_link ?>">
                <div class="icon-container<?= $lang === 'de' ? ' active' : ''; ?>">
                    <img src="<?= dirname(plugin_dir_url(__FILE__))?>/images/icones_drapeaux/flag-de.png" alt="DE" class="icon">
                    <span class="lang-label">DE</span>
                </div>
            </a>
        </li>
        <li <?= $lang === 'en' ? 'class="active"' : ''; ?>>
            <a href="<?= $en_link ?>">
                <div class="icon-container<?= $lang === 'en' ? ' active' : ''; ?>">
                    <img src="<?= dirname(plugin_dir_url(__FILE__))?>/images/icones_drapeaux/flag-gb.png" alt="EN" class="icon">
                    <span class="lang-label">EN</span>
                </div>
            </a>
        </li>
    </ul>
</div>