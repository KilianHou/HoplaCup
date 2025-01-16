<div class="wrap">
    <h1 class="wp-heading-inline">Visualisation du tournoi</h1>
    <span id="infos-etapes"></span>
    <br>
    <a class="lien-retour" href="<?= esc_url(admin_url($this->settings_root . '&view=tournois&subview=item&id=' . $tournoi_id)) ?>">Retour à l'accueil du tournoi</a>
    <hr class="wp-header-end">
    <div class="lien-etape-prev-container">
        <div class="lien-etape lien-etape-prev">
            <a href="<?= esc_url(admin_url($this->settings_root . '&view=tournois&subview=item&id=' . $tournoi_id . '&configstep=4')) ?>"><i class="fa-solid fa-arrow-left"></i> Étape précédente</a>
        </div>
    </div>

    <!-- Onglets -->
    <div class="tabs">
        <ul class="tab-links">
            <li class="active"><a href="#poule">Classement par Poule</a></li>
            <li><a href="#general">Classement Général</a></li>
            <li><a href="#fairplay">Classement Fair Play</a></li>

        </ul>

        <div class="tab-content">
            <div id="poule" class="tab active">
                <h2>Classement par Poule</h2>
                <ul>
                <div class="poules-container">
                <?php if (!empty($grouped_poules)) : ?>
                    <?php foreach ($grouped_poules as $poule_id => $poule) : ?>
                        <div class="poule">
                            <h3>
                                <?php echo esc_html($poule['nom']); ?>
                                <span class="phase-name">(Phase : <?php echo esc_html($poule['phase_nom']); ?>)</span>
                            </h3>
                            <div class="poule-content">
                                <!-- Liste des équipes -->
                                <table class="equipes-table">
                                    <thead>
                                        <tr>
                                            <!--<th>Classement</th>-->
                                            <th>Position</th>
                                            <th>Équipe</th>
                                            <th>Points</th>
                                            <th>Buts marqués</th>
                                            <th>Buts encaissés</th>
                                            <th>Goal average</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach ($poule['equipes'] as $position => $equipe) : ?>
                                        <tr>
                                            <td><?php echo esc_html($position + 1); ?></td>
                                            <td><?php echo esc_html($equipe['equipe_nom']); ?></td>
                                            <td><?php echo esc_html($equipe['points']); ?></td>
                                            <td><?php echo esc_html($equipe['buts_marques'] ?? 0); ?></td>
                                            <td><?php echo esc_html($equipe['buts_encaisses'] ?? 0); ?></td>
                                            <td><?php echo esc_html($equipe['goal_average'] ?? 0); ?></td>
                                        </tr>
                                    <?php endforeach; ?>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else : ?>
                    <p>Aucune poule trouvée pour ce tournoi.</p>
                <?php endif; ?>
            </div>

                </ul>
            </div>

            <!-- Contenu de l'onglet Général -->
            <div id="general" class="tab">
            <h2>Classement Général</h2>
            <table class="classement-general-table">
                <thead>
                    <tr>
                        <th>Position</th>
                        <th>Équipe</th>
                        <th>Points</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $position = 0;
                    $previous_score = null;
                    
                    foreach ($classement as $index => $equipe) :
                        if ($previous_score !== $equipe['score']) {
                            $position += 1;
                        }
                        $previous_score = $equipe['score'];
                    ?>
                        <tr>
                            <td><?php echo esc_html($position); ?></td>
                            <td><?php echo esc_html($equipe['nom']); ?></td>
                            <td><?php echo esc_html($equipe['score']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
            <!-- Contenu de l'onglet Fair Play -->
            <div id="fairplay" class="tab">
    <h2>Classement Fair Play</h2>
    <table class="classement-fairplay-table">
        <thead>
            <tr>
                <th>Position</th>
                <th>Équipe</th>
                <th>Points</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($classement_fairplay)) : ?>
                <?php foreach ($classement_fairplay as $index => $equipe) : ?>
                    <tr>
                        <td><?php echo esc_html($index + 1); ?></td>
                        <td><?php echo esc_html($equipe->equipe_nom); ?></td>
                        <td><?php echo esc_html($equipe->points); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else : ?>
                <tr>
                    <td colspan="3">Aucun résultat trouvé.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>


        </div>
    </div>
</div>

<!-- Script pour gérer les onglets -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    const tabLinks = document.querySelectorAll('.tab-links li');
    const tabContents = document.querySelectorAll('.tab-content .tab');

    tabLinks.forEach(link => {
        link.addEventListener('click', function () {
            tabLinks.forEach(link => link.classList.remove('active'));
            this.classList.add('active');

            const target = this.querySelector('a').getAttribute('href').substring(1);
            tabContents.forEach(content => content.classList.remove('active'));
            document.getElementById(target).classList.add('active');
        });
    });
});

</script>
