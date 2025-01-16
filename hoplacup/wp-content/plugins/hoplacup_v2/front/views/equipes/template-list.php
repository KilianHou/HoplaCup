<?php

$baselink = $breadcrumbs['division']['link'];

?>

<main class="container">

    <h1><?= LISTEEQUIPE ?> <?= $division_name ?></h1>

    <div class="search-container">
        <input type="text" id="searchInput" placeholder="Recherche...">
        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 48 48" id="search">
            <path d="M46.599 40.236L36.054 29.691C37.89 26.718 39 23.25 39 19.5 39 8.73 30.27 0 19.5 0S0 8.73 0 19.5 8.73 39 19.5 39c3.75 0 7.218-1.11 10.188-2.943l10.548 10.545a4.501 4.501 0 0 0 6.363-6.366zM19.5 33C12.045 33 6 26.955 6 19.5S12.045 6 19.5 6 33 12.045 33 19.5 26.955 33 19.5 33z"></path>
        </svg>
    </div>

    <?php if (empty($equipes)) : ?>
        <p>Aucune équipe trouvée</p>
    <?php else : ?>

        <ul id="liste-equipes">
            <?php foreach ($equipes as $equipe) : ?>
                <li class="equipe">
                    <a href="<?= esc_url($baselink) ?>/equipes/<?= esc_attr($equipe->ID) ?>">
                        <picture>
                            <?php
                            $logo = $equipe->Logo;
                            $logo_url = '';

                            if ($logo) {
                                // Vérifie si le logo est sérialisé
                                if (@unserialize($logo) !== false) {
                                    $logo_data = unserialize($logo);
                                    if (isset($logo_data['file'])) {
                                        $logo_url = wp_get_upload_dir()['baseurl'] . '/' . $logo_data['file'];
                                    }
                                } else {
                                    $logo_url = wp_get_upload_dir()['baseurl'] . '/' . $logo;
                                }
                            }
                            ?>
                            <?php if (!empty($logo_url)): ?>
                                <img src="<?= esc_url($logo_url) ?>" alt="Logo <?= esc_attr($equipe->Nom_Club) ?>">
                            <?php else: ?>
                                <img src="<?= esc_url(dirname(dirname(plugin_dir_url(__FILE__))) . '/images/logos_clubs/default.webp') ?>" alt="Logo par défaut">
                            <?php endif; ?>
                        </picture>
                        <span><?= esc_html($equipe->Nom_Club) ?></span>
                        <p><?= esc_html($equipe->Nom) ?></p>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>

    <?php endif; ?>

</main>

<script>
    document.getElementById('searchInput').addEventListener('input', function() {
        var input = this.value.toLowerCase();
        var normalizedInput = normalizeString(input);
        var lis = document.querySelectorAll('#liste-equipes li');

        lis.forEach(function(li) {
            var p = li.querySelector('p');
            var span = li.querySelector('span');

            // Normalise le texte du <span> et du <p>
            var normalizedSpanText = normalizeString(span.textContent.toLowerCase());
            var normalizedPText = normalizeString(p.textContent.toLowerCase());

            // Vérifie si le texte normalisé du <span> ou du <p> inclut le texte de la recherche
            var spanMatch = normalizedSpanText.includes(normalizedInput);
            var pMatch = normalizedPText.includes(normalizedInput);

            // Affiche l'élément <li> si le texte du <span> ou du <p> correspond à la recherche
            if (spanMatch || pMatch) {
                li.style.display = 'inline-block';
            } else {
                li.style.display = 'none';
            }
        });
    });

    // Fonction pour normaliser une chaîne de caractères en retirant les accents
    function normalizeString(str) {
        return str.normalize("NFD").replace(/[\u0300-\u036f]/g, "");
    }
</script>
