<?php

$baselink = $breadcrumbs['division']['link'];

?>

<div class="container">

    <p class="poule-titre"> <?= ($selectedPoule < 'E' || $selectedPoule == 'K') ? PHASEPOULE : PHASEBRASSAGE ?></p>

    <div class="poule-center">
        <select class="poule-select" id="select" name="poule" onchange=" affichage_par_poules() ">

            <?php if ($division == 'u13' || $division == 'u11') : ?>
                <option value="A" <?= $selectedPoule == 'A' ? 'selected=selected' : ''; ?>><?= POULE ?> A</option>
                <option value="B" <?= $selectedPoule == 'B' ? 'selected=selected' : ''; ?>><?= POULE ?> B</option>
                <option value="C" <?= $selectedPoule == 'C' ? 'selected=selected' : ''; ?>><?= POULE ?> C</option>
                <option value="D" <?= $selectedPoule == 'D' ? 'selected=selected' : ''; ?>><?= POULE ?> D</option>
                <option value="E" <?= $selectedPoule == 'E' ? 'selected=selected' : ''; ?>><?= POULE ?> E</option>
                <option value="F" <?= $selectedPoule == 'F' ? 'selected=selected' : ''; ?>><?= POULE ?> F</option>
                <option value="G" <?= $selectedPoule == 'G' ? 'selected=selected' : ''; ?>><?= POULE ?> G</option>
                <option value="H" <?= $selectedPoule == 'H' ? 'selected=selected' : ''; ?>><?= POULE ?> H</option>
                <option value="I" <?= $selectedPoule == 'I' ? 'selected=selected' : ''; ?>><?= POULE ?> I</option>
                <option value="J" <?= $selectedPoule == 'J' ? 'selected=selected' : ''; ?>><?= POULE ?> J</option>
            <?php else : ?>
                <option value="K" <?= $selectedPoule == 'K' ? 'selected=selected' : ''; ?>><?= POULE ?> K</option>
                <option value="L" <?= $selectedPoule == 'L' ? 'selected=selected' : ''; ?>><?= POULE ?> L</option>
                <option value="M" <?= $selectedPoule == 'M' ? 'selected=selected' : ''; ?>><?= POULE ?> M</option>
            <?php endif ?>
        </select>
        <i class="fas fa-chevron-down select-arrow"></i>
    </div>

    <p class="poule-titre"><?= CLASSEMENT ?></p>

    <table class="poule-table poule">
        <thead class="poule-table-header">
        <tr>
            <th class="poule-table-header-row"><?= POSITION ?></th>
            <th class="poule-table-header-row"></th>
            <th class="poule-table-header-row"><?= EQUIPE ?></th>
            <th class="poule-table-header-row"><?= J ?></th>
            <th class="poule-table-header-row"><?= PTS ?></th>
            <th class="poule-table-header-row">+/-</th>
        </tr>
        <tbody>
        <?php foreach ($poules as $a) {
            if ($a['Nom_poule'] == $selectedPoule) {
                ?>
                <tr>
                    <th class="poule-table-header-row"><?= $a['Position'] ?></th>
                    <td class="poule-table-header-row">
                        <?php if ($a['Identifiant']): ?>
                            <picture>
                                <?php
                                $static_logo = $a['Identifiant_Club'] . '.webp';
                                ?>
                                <img src="<?= dirname(dirname(plugin_dir_url(__FILE__))) ?>/images/logos_clubs/<?= $static_logo ?>" alt="Logo <?= $a['Nom_Equipe'] ?>">
                            </picture>
                        <?php endif; ?>
                    </td>
                    <th class="poule-table-header-row">
                        <a href="<?= $baselink ?>/equipes/<?= $a['Identifiant'] ?>" style="color: black;">
                            <?= $a['Nom_Equipe'] ?>
                        </a>
                    </th>
                    <th class="poule-table-header-row"><?= $a['Matchs_joues'] ?></th>
                    <th class="poule-table-header-row"><?= $a['Points'] ?></th>
                    <th class="poule-table-header-row"><?= $a['Goal_average'] ?></th>
                </tr>
            <?php }
            } ?>
            <!---->
        </tbody>
    </table>

    <p class="poule-titre"><?= MATCHS ?></p>

    <table>
        <thead>

        </thead>
        <tbody>
            <?php foreach ($matches as $match) :
                if ($match['Poule'] == $selectedPoule) :
                    $static_logo1 = $match['Identifiant_Club1'] . '.webp';
                    $static_logo2 = $match['Identifiant_Club2'] . '.webp';
            ?>
                    <tr>
                        <th class="jourHoraire" colspan="7" style="text-align:center;"><?= substr(constant($match['Jour']), 0, 3) ?> <?= htmlspecialchars($match['Horaire']) ?> <?= TERRAIN ?> <?= $match['Terrain'] ?></th>
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
                                <?php if (isset($match['Identifiant_Club2']) && $match['Identifiant_Club2'] !== null): ?>
                                    <img src="<?= dirname(dirname(plugin_dir_url(__FILE__))) ?>/images/logos_clubs/<?= htmlspecialchars($static_logo2) ?>" alt="Logo <?= htmlspecialchars($match['Identifiant_Club2']) ?>">
                                <?php endif; ?>
                            </picture>
                        </td>
                    </tr>
            <?php endif;
            endforeach; ?>

        </tbody>
    </table>
</div>

<div class="poule-espace"></div>

<script>
    function affichage_par_poules() {
        const select = document.getElementById('select');
        const selectValue = select.value;
        const option = Array.from(select.options);
        const choice = option.find(item => item.value === selectValue);
        choice.selected = true;
        location.href = '<?= $baselink ?>/poules?q=' + selectValue;
    }
</script>

<style></style>