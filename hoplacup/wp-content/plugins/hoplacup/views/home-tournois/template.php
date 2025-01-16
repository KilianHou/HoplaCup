<?php



?>
<main>

    <div class="container">
        <h3 class="rouge"> <?= EDITION2024 ?></h3>
        <h1>Hopla Cup 2024</h1>
    </div>

    <section id="tournois">
        <ul id="categories-tournoi" class="container">
            <li>
                <a class="tournoi" href="<?= $url . '/u9' ?>">
                    <?= TOURNOI ?>
                    <br> U9
                </a>
            </li>
            <li>
                <a class="tournoi" href="<?= $url . '/u11' ?>">
                    <?= TOURNOI ?>
                    <br> U11
                </a>
            </li>
            <li>
                <a class="tournoi" href="<?= $url . '/u13' ?>">
                    <?= TOURNOI ?>
                    <br> U13
                </a>
            </li>
        </ul>
    </section>

    <div id="container">

    </div>

</main>


<div class="logos">
    <div class="logos-slide">
        <img src="<?= dirname(dirname(plugin_dir_url(__FILE__))) ?>/images/3DEclairage.jpg" alt="logosponsor3DEclairage" />
        <img src="<?= dirname(dirname(plugin_dir_url(__FILE__))) ?>/images/AT2E.png" alt="logoAT2E" />
        <img src="<?= dirname(dirname(plugin_dir_url(__FILE__))) ?>/images/Audebert.jpg" alt="logoAudebert" />
        <img src="<?= dirname(dirname(plugin_dir_url(__FILE__))) ?>/images/Cegelec.bmp" alt="logoCegelec" />
        <img src="<?= dirname(dirname(plugin_dir_url(__FILE__))) ?>/images/Cérébat.jpeg" alt="logoCerebat" />
        <img src="<?= dirname(dirname(plugin_dir_url(__FILE__))) ?>/images/ElectricitéKoch.png" alt="logoElectricitéKoch" />
        <img src="<?= dirname(dirname(plugin_dir_url(__FILE__))) ?>/images/GroupeVincentz.jpg" alt="logoGroupeVincentz" />
        <img src="<?= dirname(dirname(plugin_dir_url(__FILE__))) ?>/images/HesingueCarrelage.jpeg" alt="logoHesingueCarrelage" />
        <img src="<?= dirname(dirname(plugin_dir_url(__FILE__))) ?>/images/Ilconnect.png" alt="logoIlconnect" />
        <img src="<?= dirname(dirname(plugin_dir_url(__FILE__))) ?>/images/uha40.png" alt="logoUha40" />
        <img src="<?= dirname(dirname(plugin_dir_url(__FILE__))) ?>/images/Boehli.png" alt="logo Boehli" />
        <img src="<?= dirname(dirname(plugin_dir_url(__FILE__))) ?>/images/HoplaCafe.png" alt="logo Hopla Café">
        <img src="<?= dirname(dirname(plugin_dir_url(__FILE__))) ?>/images/MoulinJenny.png" alt="logo Moulin Jenny">
        <img src="<?= dirname(dirname(plugin_dir_url(__FILE__))) ?>/images/Serat.jpeg" alt="logo Serat" />
        <img src="<?= dirname(dirname(plugin_dir_url(__FILE__))) ?>/images/Lesage.jpg" alt="logo Lesage" />
        <img src="<?= dirname(dirname(plugin_dir_url(__FILE__))) ?>/images/Iloosinformatique.png" alt="logo Iloose informatique" />
        <img src="<?= dirname(dirname(plugin_dir_url(__FILE__))) ?>/images/Herrgott.png" alt="logo Herrgott" />
        <img src="<?= dirname(dirname(plugin_dir_url(__FILE__))) ?>/images/HauserPeinture.jpg" alt="logo Hauser Peinture" />
    </div>
</div>
</div>
<script>
    var copy = document.querySelector(".logos-slide").cloneNode(true);
    document.querySelector('.logos').appendChild(copy);
</script>