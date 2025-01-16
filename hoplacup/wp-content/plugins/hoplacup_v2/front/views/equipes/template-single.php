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
    <img class="logo" src="<?= dirname(dirname(plugin_dir_url(__FILE__))) ?>/images/logos_clubs/<?= $club_identifiant ?>.webp" alt="Logo">
    <h1><?= DETAILMATCH ?> <?= $Equipe[0]->Nom_Equipe ?></h1>

    <?php
    $phases = ['poules' => POULES, 'brassages' => BRASSAGE, 'playoffs' => 'Playoffs'];

    foreach ($phases as $phase_key => $phase_name):
        // Regrouper les matchs par poule ou phase
        $grouped_matches = [];

        foreach ($matches as $match) {
            if ($match->Jour == 'dim' && $phase_key == 'playoffs') {
                $key = $match->Phase;
            } else {
                $key = $phase_key == 'playoffs' ? $match->Phase : $match->Poule;
            }

            if ($match->Phase == $phase_key || ($phase_key == 'playoffs' && $match->Jour == 'dim')) {
                if (!isset($grouped_matches[$key])) {
                    $grouped_matches[$key] = [];
                }
                $grouped_matches[$key][] = $match;
            }
        }

        foreach ($grouped_matches as $group_key => $group_matches):
            // Utilisation des constantes pour le nom de la phase dans les playoffs
            $phase_display_name = $phase_key == 'playoffs' && defined($group_key) ? constant($group_key) : rtrim($group_key, '.');
            ?>
            <h2><?= $phase_name ?> -
                <?php if ($phase_key == 'poules' || $phase_key == 'brassages'): ?>
                    <a href="<?= $baselink ?>/<?= $division ?>/poules/?q=<?= $group_key ?>" class="red-text"><?= $phase_display_name ?></a>
                <?php else: ?>
                    <span class="red-text"><?= $phase_display_name ?></span>
                <?php endif; ?>
            </h2>
            <table class="table">
                <tbody>
                <?php foreach ($group_matches as $match): ?>
                    <tr>
                        <th class="jourHoraire" colspan="7">
                            <?= ucfirst(constant($match->Jour)) ?> - <?= $match->Horaire ?> <?= constant('TERRAIN') ?> <?= $match->Terrain ?>
                        </th>
                    </tr>
                    <tr>
                        <td class="logoEquipe">
                            <picture>
                                <img src="<?= dirname(dirname(plugin_dir_url(__FILE__))) ?>/images/logos_clubs/<?= $match->Identifiant_Club1 ?>.webp" alt="Logo <?= $match->Nom_Equipe1 ?>">
                            </picture>
                        </td>
                        <td class="nomEquipe nomEquipe1">
                            <a href="<?= $baselink ?>/equipes/<?= $match->Equipe1 ?>" style="color: white;"><?= $match->Nom_Equipe1 ?></a>
                            <?php if ($phase_key == 'playoffs'): ?>
                                <br>
                                <small>
                                    <img src="<?= dirname(dirname(plugin_dir_url(__FILE__))) ?>/images/icontab.png" alt="<?= TIRSAUBUT ?>" style="width: 16px; vertical-align: middle;">
                                    <?= TIRSAUBUT ?>
                                </small>
                            <?php endif; ?>
                        </td>
                        <td class="scoreEquipe score">
                            <?= $match->Score_Equipe1 ?><br>
                            <small>
                                <?= $phase_key == 'playoffs' && (isset($match->Tab_Equipe1) && $match->Tab_Equipe1 !== '') ? "({$match->Tab_Equipe1})" : '' ?>
                            </small>
                        </td>
                        <td class="separateur1">-</td>
                        <td class="scoreEquipe score">
                            <?= $match->Score_Equipe2 ?><br>
                            <small>
                                <?= $phase_key == 'playoffs' && (isset($match->Tab_Equipe2) && $match->Tab_Equipe2 !== '') ? "({$match->Tab_Equipe2})" : '' ?>
                            </small>
                        </td>
                        <td class="nomEquipe nomEquipe2">
                            <a href="<?= $baselink ?>/equipes/<?= $match->Equipe2 ?>" style="color: white;"><?= $match->Nom_Equipe2 ?></a>
                            <?php if ($phase_key == 'playoffs'): ?>
                                <br>
                                <small>
                                    <img src="<?= dirname(dirname(plugin_dir_url(__FILE__))) ?>/images/icontab.png" alt="<?= TIRSAUBUT ?>" style="width: 16px; vertical-align: middle;">
                                    <?= TIRSAUBUT ?>
                                </small>
                            <?php endif; ?>
                        </td>
                        <td class="logoEquipe">
                            <picture>
                                <img src="<?= dirname(dirname(plugin_dir_url(__FILE__))) ?>/images/logos_clubs/<?= $match->Identifiant_Club2 ?>.webp" alt="Logo <?= $match->Nom_Equipe2 ?>">
                            </picture>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            <br>
        <?php endforeach;
    endforeach; ?>
</main>
