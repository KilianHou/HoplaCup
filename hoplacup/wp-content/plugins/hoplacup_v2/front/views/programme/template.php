<?php
$baselink = '';

if (isset($breadcrumbs['division']['link'])) {
    $baselink = $breadcrumbs['division']['link'];
}

// Extraire la division
$division = '';
if (isset($breadcrumbs['division']['name'])) {
    $division = strtolower($breadcrumbs['division']['name']);
}
?>

<main class="container">
    <h1><?= PROGRAMME2 ?> <?= htmlspecialchars($division) ?></h1>
    <div class="search-container">
        <input type="text" id="searchInput" placeholder="Recherche...">
        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 48 48" id="search">
            <path d="M46.599 40.236L36.054 29.691C37.89 26.718 39 23.25 39 19.5 39 8.73 30.27 0 19.5 0S0 8.73 0 19.5 8.73 39 19.5 39c3.75 0 7.218-1.11 10.188-2.943l10.548 10.545a4.501 4.501 0 0 0 6.363-6.366zM19.5 33C12.045 33 6 26.955 6 19.5S12.045 6 19.5 6 33 12.045 33 19.5 26.955 33 19.5 33z"></path>
        </svg>
    </div>

    <form action="" method="get" id="selectionForm">
        <div class="select-wrapper">
            <select name="jour" id="jour" onchange="document.getElementById('selectionForm').submit();" class="select-jour">
                <option value="ven" <?= ($jour == 'ven' ? 'selected' : '') ?>><?= VENDREDI ?></option>
                <option value="sam" <?= ($jour == 'sam' ? 'selected' : '') ?>><?= SAMEDI ?></option>
                <option value="dim" <?= ($jour == 'dim' ? 'selected' : '') ?>><?= DIMANCHE ?></option>
            </select>
            <i class="fas fa-chevron-down select-arrow"></i>
        </div>
    </form>


    <?php if ($jour == 'sam') : ?>
        <?php if (!empty($matchesMatin)) : ?>
            <h2 class="toggle-collapse session-title" aria-expanded="false" aria-controls="matinMatches">
                <?= MATIN ?>
            </h2>
            <div class="collapsable" id="matinMatches" data-height="0">
                <table>
                    <tbody>
                        <?php foreach ($matchesMatin as $match) :
                            $static_logo1 = $match['Identifiant_Club1'] . '.webp';
                            $static_logo2 = $match['Identifiant_Club2'] . '.webp';
                        ?>
                            <tr>
                                <th class="jourHoraire" colspan="7" style="text-align:center;">
                                    <?= constant($match['Jour']), " ", htmlspecialchars($match['Horaire']), " ", TERRAIN, " ", $match['Terrain'] ?>
                                </th>
                            </tr>
                            <tr>
                                <td class="logoEquipe">
                                    <picture>
                                        <?php if ($match['Identifiant_Club1']) : ?>
                                            <img src="<?= dirname(dirname(plugin_dir_url(__FILE__))) ?>/images/logos_clubs/<?= $static_logo1 ?>" alt="Logo <?= $match['Identifiant_Club1'] ?>">
                                        <?php endif ?>
                                    </picture>
                                </td>
                                <td class="nomEquipe nomEquipe1">
                                    <a href="<?= $baselink ?>/equipes/<?= htmlspecialchars($match['Equipe1'] ?? '') ?>" style="color: white;">
                                        <?= htmlspecialchars($match['Nom_Equipe1'] ?? '') ?>
                                    </a>
                                </td>
                                <td class="scoreEquipe"><?= isset($match['Score_Equipe1']) ? htmlspecialchars($match['Score_Equipe1']) : '' ?></td>
                                <td class="separateur1">-</td>
                                <td class="scoreEquipe"><?= isset($match['Score_Equipe2']) ? htmlspecialchars($match['Score_Equipe2']) : '' ?></td>
                                <td class="nomEquipe nomEquipe2">
                                    <a href="<?= $baselink ?>/equipes/<?= htmlspecialchars($match['Equipe2'] ?? '') ?>" style="color: white;">
                                        <?= htmlspecialchars($match['Nom_Equipe2'] ?? '') ?>
                                    </a>
                                </td>
                                <td class="logoEquipe">
                                    <picture>
                                        <img src="<?= dirname(dirname(plugin_dir_url(__FILE__))) ?>/images/logos_clubs/<?= $static_logo2 ?>" alt="Logo <?= $match['Identifiant_Club2'] ?>">
                                    </picture>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>

        <?php if (!empty($matchesApresMidi)) : ?>
            <h2 class="toggle-collapse session-title" aria-expanded="false" aria-controls="apresMidiMatches">
                <?= APRESMIDI ?>
            </h2>
            <div class="collapsable" id="apresMidiMatches" data-height="0">
                <table>
                    <tbody>

                        <?php foreach ($matchesApresMidi as $match) :
                            $static_logo1 = $match['Identifiant_Club1'] . '.webp';
                            $static_logo2 = $match['Identifiant_Club2'] . '.webp';
                        ?>
                            <tr>
                                <th class="jourHoraire" colspan="7" style="text-align:center;">
                                    <?= constant($match['Jour']), " ", htmlspecialchars($match['Horaire']), " ", TERRAIN, " ", $match['Terrain'] ?>
                                </th>
                            </tr>
                            <tr>
                                <td class="logoEquipe">
                                    <picture>
                                        <?php if ($match['Identifiant_Club1']) : ?>
                                            <img src="<?= dirname(dirname(plugin_dir_url(__FILE__))) ?>/images/logos_clubs/<?= $static_logo1 ?>" alt="Logo <?= $match['Identifiant_Club1'] ?>">
                                        <?php endif ?>
                                    </picture>
                                </td>
                                <td class="nomEquipe nomEquipe1">
                                    <a href="<?= $baselink ?>/equipes/<?= htmlspecialchars($match['Equipe1'] ?? '') ?>" style="color: white;">
                                        <?= htmlspecialchars($match['Nom_Equipe1'] ?? '') ?>
                                    </a>
                                </td>
                                <td class="scoreEquipe"><?= isset($match['Score_Equipe1']) ? htmlspecialchars($match['Score_Equipe1']) : '' ?></td>
                                <td class="separateur1">-</td>
                                <td class="scoreEquipe"><?= isset($match['Score_Equipe2']) ? htmlspecialchars($match['Score_Equipe2']) : '' ?></td>
                                <td class="nomEquipe nomEquipe2">
                                    <a href="<?= $baselink ?>/equipes/<?= htmlspecialchars($match['Equipe2'] ?? '') ?>" style="color: white;">
                                        <?= htmlspecialchars($match['Nom_Equipe2'] ?? '') ?>
                                    </a>
                                </td>
                                <td class="logoEquipe">
                                    <picture>
                                        <?php if ($match['Identifiant_Club1']) : ?>
                                            <img src="<?= dirname(dirname(plugin_dir_url(__FILE__))) ?>/images/logos_clubs/<?= $static_logo2 ?>" alt="Logo <?= $match['Identifiant_Club2'] ?>">
                                        <?php endif ?>
                                    </picture>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    <?php else : ?>
        <table>
            <tbody>
                <?php
                foreach ($matches as $match) :
                    $static_logo1 = $match['Identifiant_Club1'] . '.webp';
                    $static_logo2 = $match['Identifiant_Club2'] . '.webp';
                ?>
                    <tr>
                        <th class="jourHoraire" colspan="7" style="text-align:center;">
                            <?= constant($match['Jour']), " ", htmlspecialchars($match['Horaire']), " ", TERRAIN, " ", $match['Terrain'] ?>
                        </th>
                    </tr>
                    <tr class="equipe-info">
                        <td class="logoEquipe">
                            <picture>
                                <?php if ($match['Identifiant_Club1']) : ?>
                                    <img src="<?= dirname(dirname(plugin_dir_url(__FILE__))) ?>/images/logos_clubs/<?= $static_logo1 ?>" alt="Logo <?= $match['Identifiant_Club1'] ?>">
                                <?php endif ?>
                            </picture>
                        </td>
                        <td class="nomEquipe nomEquipe1">
                            <a href="<?= $baselink ?>/equipes/<?= htmlspecialchars($match['Equipe1'] ?? '') ?>" style="color: white;">
                                <?= htmlspecialchars($match['Nom_Equipe1'] ?? '') ?>
                            </a>
                            <?php if ($jour == 'dim' && isset($match['Tab_Equipe1']) && $match['Tab_Equipe1'] !== '') : ?>
                                <br>
                                <small>
                                    <img src="<?= dirname(dirname(plugin_dir_url(__FILE__))) ?>/images/icontab.png" alt="<?= TIRSAUBUT ?>" style="width: 16px; vertical-align: middle;">
                                    <?= TIRSAUBUT ?>
                                </small>
                            <?php endif; ?>
                        </td>

                        <td class="scoreEquipe">
                            <?= isset($match['Score_Equipe1']) ? htmlspecialchars($match['Score_Equipe1']) : '' ?>
                            <br>
                            <small><?= $jour == 'dim' && isset($match['Tab_Equipe1']) && $match['Tab_Equipe1'] !== '' ? "({$match['Tab_Equipe1']})" : '' ?></small>
                        </td>
                        <td class="separateur1">-</td>
                        <td class="scoreEquipe">
                            <?= isset($match['Score_Equipe2']) ? htmlspecialchars($match['Score_Equipe2']) : '' ?>
                            <br>
                            <small><?= $jour == 'dim' && isset($match['Tab_Equipe2']) && $match['Tab_Equipe2'] !== '' ? "({$match['Tab_Equipe2']})" : '' ?></small>
                        </td>

                        <td class="nomEquipe nomEquipe2">
                            <a href="<?= $baselink ?>/equipes/<?= htmlspecialchars($match['Equipe2'] ?? '') ?>" style="color: white;">
                                <?= htmlspecialchars($match['Nom_Equipe2'] ?? '') ?>
                            </a>
                            <?php if ($jour == 'dim' && isset($match['Tab_Equipe2']) && $match['Tab_Equipe2'] !== '') : ?>
                                <br>
                                <small>
                                    <img src="<?= dirname(dirname(plugin_dir_url(__FILE__))) ?>/images/icontab.png" alt="<?= TIRSAUBUT ?>" style="width: 16px; vertical-align: middle;">
                                    <?= TIRSAUBUT ?>
                                </small>
                            <?php endif; ?>
                        </td>

                        <td class="logoEquipe">
                            <picture>
                                <?php if ($match['Identifiant_Club1']) : ?>
                                    <img src="<?= dirname(dirname(plugin_dir_url(__FILE__))) ?>/images/logos_clubs/<?= $static_logo2 ?>" alt="Logo <?= $match['Identifiant_Club2'] ?>">
                                <?php endif ?>
                            </picture>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</main>


<script>
    //
    //
    function measure_collapsable_height() {
        let collapsables = document.getElementsByClassName('collapsable')
        for (let i = 0; i < collapsables.length; i++) {
            // Memoriser la height totale
            let height = collapsables[i].getElementsByTagName('table')[0].offsetHeight
            collapsables[i].dataset.height = height
            console.log(collapsables[i].getElementsByTagName('table')[0])
        }
    }

    window.addEventListener('resize', () => {
        // Met à jour mesures collapsables si resize 
        measure_collapsable_height()
    })

    window.addEventListener('load', () => {

        // Mesure collapsables au load
        measure_collapsable_height()

        // Toggles expand matin/aprm
        let togglesExpand = document.getElementsByClassName('toggle-collapse')
        for (let i = 0; i < togglesExpand.length; i++) {
            togglesExpand[i].addEventListener('click', (e) => {
                toggleExpand(togglesExpand[i])
            })
        }

        // Champ Rechercher
        var searchInput = document.getElementById('searchInput');
        searchInput.addEventListener('input', function() {
            var input = this.value.toLowerCase();
            var rows = document.querySelectorAll('tbody tr');





            if (input === '') {

                rows.forEach(function(row) {
                    row.style.display = '';
                });
            } else {
                rows.forEach(tr => tr.style.display = 'none'); // Cache toutes les lignes
                var teamNames1 = document.querySelectorAll('.nomEquipe1');
                var teamNames2 = document.querySelectorAll('.nomEquipe2');
                teamNames1.forEach(function(name) {
                    if (name.textContent.toLowerCase().includes(input)) {
                        name.parentElement.style.display = '';
                        name.parentElement.previousElementSibling.style.display = '';
                        // faire le expand du tableau si masqué
                        let div = name.parentElement.parentElement.parentElement.parentElement
                        let h2 = div.previousElementSibling
                        if (!h2.classList.contains('deployed')) {
                            toggleExpand(h2)
                        }
                        // readapter la hauteur du tableau
                        measure_collapsable_height()
                        name.parentElement.parentElement.parentElement.parentElement.style.height =
                            name.parentElement.parentElement.parentElement.parentElement.dataset.height + 'px'
                    }
                });
                teamNames2.forEach(function(name) {
                    if (name.textContent.toLowerCase().includes(input)) {
                        name.parentElement.style.display = '';
                        name.parentElement.previousElementSibling.style.display = '';
                        // faire le expand du tableau si masqué
                        let div = name.parentElement.parentElement.parentElement.parentElement
                        let h2 = div.previousElementSibling
                        if (!h2.classList.contains('deployed')) {
                            toggleExpand(h2)
                        }
                        // readapter la hauteur du tableau
                        measure_collapsable_height()
                        name.parentElement.parentElement.parentElement.parentElement.style.height =
                            name.parentElement.parentElement.parentElement.parentElement.dataset.height + 'px'
                    }
                });






            }
        });
    })


    /**
     * Gère le toggle height matin/après-midi
     */
    function toggleExpand(toggler) {
        toggler.classList.toggle('deployed')
        let expandableElement = toggler.nextElementSibling;
        expandableElement.classList.toggle('expanded')
        if (expandableElement.classList.contains('expanded')) {
            expandableElement.style.height = expandableElement.dataset.height + 'px'
        } else {
            expandableElement.style.height = 0 + 'px'
        }
    }
</script>