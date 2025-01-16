<div class="wrap">

    <style>

        img.console {
            width: 100%;
            height: 100px;
            object-fit: cover;
        }

        img.uha {
            position: absolute;
            top: 85px;
            left: 50%;
        }

        header {
            border: 4px outset #6EC1E4;
            height: 100px;
        }

        main {
            display: flex;
            flex-direction: row;
            width: 100%;
            background-color: #FFF;
        }

        section {
            padding: 30px;
            position: relative;
            width: 100%;
            height: 100%;
            background-color: #FFF;
        }

        footer {
            display: flex;
            justify-content: center;
            background-color: #FFF;
            border: 2px solid #6EC1E4;
        }

        div.footer {
            margin: 50px;
        }

    </style>

    <header>
        <img class="console" src="<?= dirname(dirname(plugin_dir_url(__FILE__))) ?>/images/logos_institutionnels/programming-3460032_1920.jpg" alt="Logo UHA4.0 ?>">
        <img class="uha" src="<?= dirname(dirname(plugin_dir_url(__FILE__))) ?>/images/logos_institutionnels/grand-logo-n.png" alt="" width="90px" height="90px">
    </header>

    <main>

        <section>
            <h2>Panneau de configuration du plugin HoplaCup</h2>
            <br>
            <blockquote>
            <h3>Activation de la récupération des données depuis GoogleSheets</h3>
                <blockquote>
                <form method="post" action="options.php">
                <?php
                settings_fields('hoplacup_plugin_options');
                do_settings_sections('hoplacup_plugin_settings');

                $enabled_cron = get_option('hoplacup_plugin_enable_cron', false);
                $enabled_simulation = get_option('hoplacup_plugin_enable_date_simulation', false);
                $simulation_time = get_option('hoplacup_plugin_simulation_time', '');
                $simulation_day = get_option('hoplacup_plugin_simulation_day', '');
                ?>

                <label for="hoplacup_plugin_enable_cron">
                    <input type="checkbox" id="hoplacup_plugin_enable_cron" name="hoplacup_plugin_enable_cron" <?php checked($enabled_cron, 'on'); ?>>
                    Synchronisation périodique avec GoogleSheets (toutes les 60 secondes)
                </label>
                </blockquote>
                <br>
                <br>
                <br>
                <br>
                <h3>Simulation</h3>
                <blockquote>
                    <p style="background-color:yellow;color:red;padding:10px;font-weight:bold;font-size:14px;"><span style="font-size:32px;">▲</span> Il est possible que des doublons apparaissent pour remédier à cela, il suffit de désactiver la synchronisation et de la réactiver, les doublons devrai alors disparaitre !</p>
                <label for="hoplacup_plugin_enable_date_simulation">
                    <input type="checkbox" id="hoplacup_plugin_enable_date_simulation" name="hoplacup_plugin_enable_date_simulation" <?php checked($enabled_simulation, 'on'); ?>>
                    Activer la simulation pour prendre en compte les variables de temps lors de votre validation.
                </label>
                <br><br>

                <label for="hoplacup_plugin_simulation_time">Temps (Heures : minutes) de simulation :
                    <input type="time" id="hoplacup_plugin_simulation_time" name="hoplacup_plugin_simulation_time" value="<?php echo esc_attr($simulation_time); ?>">
                </label>
                <br>

                <label for="hoplacup_plugin_simulation_day">Date (Jour : Mois : Années) de simulation :
                    <select id="hoplacup_plugin_simulation_day" name="hoplacup_plugin_simulation_day">
                        <option value=""></option>
                        <option value="Mon" <?php selected($simulation_day, 'Mon'); ?>>Lundi</option>
                        <option value="Tue" <?php selected($simulation_day, 'Tue'); ?>>Mardi</option>
                        <option value="Wed" <?php selected($simulation_day, 'Wed'); ?>>Mercredi</option>
                        <option value="Thu" <?php selected($simulation_day, 'Thu'); ?>>Jeudi</option>
                        <option value="Fri" <?php selected($simulation_day, 'Fri'); ?>>Vendredi</option>
                        <option value="Sat" <?php selected($simulation_day, 'Sat'); ?>>Samedi</option>
                        <option value="Sun" <?php selected($simulation_day, 'Sun'); ?>>Dimanche</option>
                    </select>
                </label>
                <br>
                </blockquote>
                </blockquote>
                <?php
                /*
                <label for="hoplacup_plugin_simulation_day">Date (Jour : Mois : Années) de simulation :
                    <input type="date" id="hoplacup_plugin_simulation_day" name="hoplacup_plugin_simulation_day" value="<?php //echo esc_attr($simulation_time); ?>">
                </label>
                */
                ?>

                <?php submit_button(); ?>
            </form>

        </section>

        <section>

            <h2>Documentation installation et activation du plugin</h2>
            <br>
            <blockquote>
            <h3>Activation du plugin</h3>
            <blockquote>
                <p>Vérifier de bien avoir cocher l'activation du plugin puis sauvegarder.</p>
            </blockquote>
            <br>
            <h3>Réglages du plugin</h3>
            <p>Chemin des différents réglages à cocher et sauvegarder</p>
            <blockquote>
                <p>Settings -> Permalinks -> Permalink structure -> Numeric</p>
                <p>Settings -> Général -> Timezone -> Paris</p>
                <p>Settings -> Général -> Date_Format -> d/m/Y</p>
                <p>Settings -> Général -> Time_Format -> H:i</p>
            </blockquote>
            <br>
            <h3>Réglages plugin tiers</h3>
            <blockquote>
                <p>Rechercher 'WP Super Cache' : Extensions -> Extensions installées</p>
                <p>Aller dans les réglages et cocher : Avancé -> avancé -> Activer la mise en cache dynamique</p>
            </blockquote>
            <br>
            <h3>Réglage simulation</h3>
            <blockquote>
                <ul>
                    <li>Activer le mode simulation</li>
                    <li>Entrer les dates et horaires pour prompter le temps</li>
                    <li>Valider pour que les modifications soit pris en compte</li>
                    <li>Un avertisseur sur le site web vous avertit du mode simulation</li>
                    <li>Pour désactiver le mode simulation décocher la case et sauvegarder</li>
                </ul>
            </blockquote>
                </blockquote>
        </section>

    </main>

    <footer>
        <div class="footer">
            <a href="https://www.uha4point0.fr"><img src="<?= dirname(dirname(plugin_dir_url(__FILE__))) ?>/images/logos_institutionnels/moyen-logo-titre.png" style="height:30px;" alt="UHA 4.0"></a>
            <a href="https://www.fst.uha.fr" target="_blank" style="margin-left:20px;" rel="noopener">
                <img src="<?= dirname(dirname(plugin_dir_url(__FILE__))) ?>/images/logos_institutionnels/logoFST.png" style="height:28px;" alt="FST UHA"></a>
            <br>
            40 rue Marc Seguin<br>
            68200 Mulhouse (<a href="https://www.uha4point0.fr/contact/#plan">voir plan d'accès</a>)
        </div>
        <div class="footer">
            Téléphone : <a style="display:none" href="tel:003389336438">03 89 33 64 38</a>
            <a href="tel:0033972490105">09 72 49 01 05</a>
            <br>
            E-mail : <a href="mailto:uha40@uha.fr">uha40@uha.fr</a><br>
            + d'info : <a target="_blank" href="https://www.uha4point0.fr/wp-content/uploads/2020/02/fiche-uha4.0-2020.pdf" rel="noopener">Télécharger ici</a>
        </div>

    </footer>
</div>
