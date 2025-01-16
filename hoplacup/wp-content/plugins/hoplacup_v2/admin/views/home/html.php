<div class="wrap">
    <div style="text-align:center">
        <h1 style="font-size: 2rem">Bienvenue sur le plugin Hopla cup</h1>
    </div>
    <div class="wrapper">
        <div class="button-sommaire">
            <h2 style="text-align: center">Documentations :</h2>
            <button data-content="Prereglage" class="">Pré-réglage</button>
            <button data-content="Utilisation" class="button-selected">Utilisation</button>
            <button data-content="UniquePlugin" class="">Accès unique au plugin</button>
        </div>
        <div style="width: 3px; background-color: #2271b1; margin: 0 10px; height: 40vh; align-self: center" class="vertical-separateur"></div>
        <div class="contentbox">
            <h1 style="text-align: center"><strong style="font-size: larger">/* Utilisation */</strong></h1>
            <p class="Titre"><strong>Ajouter des Divisions, Clubs et Terrains :</strong></p>
            <p>Dans les sections correspondantes, vous pouvez ajouter des divisions, clubs et terrains pour une meilleure organisation de vos compétitions. Cela permet de structurer les différents niveaux de jeu, d'assigner des équipes à des clubs spécifiques et de préciser les lieux des matchs.</p>

            <p class="Titre"><strong>Créer un Tournoi :</strong></p>
            <p>Accédez à la section « Tournois » pour créer un nouveau tournoi. Une fois le tournoi créé, vous pouvez personnaliser les paramètres (dates, points, etc.) et organiser les différentes étapes du tournoi.</p>

            <p class="Titre"><strong>Étape 1 : Création des Poules de la Phase Initiale</strong></p>
            <p>Dans cette étape, vous pouvez créer des poules pour la phase initiale de la compétition. Utilisez la fonctionnalité de glisser-déposer (drag & drop) pour associer facilement les équipes aux poules.</p>
            <p><span style="color: red">Note importante : Vous ne pourrez pas passer à l'étape 2 tant que vous n'avez pas créé toutes les poules et associé les équipes.</span></p>

            <p class="Titre"><strong>Étape 2 : Gestion des Phases Suivantes et Transferts d’Équipes</strong></p>
            <p>Une fois les poules et les équipes associées, vous pouvez gérer les phases suivantes du tournoi. Cette étape vous permet de définir les matchs à venir et de transférer des équipes entre les différentes phases (par exemple, des phases de groupes aux éliminatoires).</p>

            <p class="Titre"><strong>Étape 3 : Gestion des Matches</strong></p>
            <p>Une fois les matchs générés, associez-les à des terrains et programmez leurs horaires.</p>

            <p class="Titre"><strong>Étape 4 : Gestion du Tournoi</strong></p>
            <p>Cette étape vous permet de consulter les matchs actuels, à venir ou terminés, et de saisir les scores des matchs joués.</p>

            <p class="Titre"><strong>Étape 5 : Visualisation du Tournoi</strong></p>
            <p>Affichez un classement par poule ainsi qu’un classement général.</p>
        </div>

    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const buttons = document.querySelectorAll('.button-sommaire button');
        const contentbox = document.querySelector('.contentbox');

        buttons.forEach(button => {
            button.addEventListener('click', function() {
                buttons.forEach(b => b.classList.remove('button-selected'));
                this.classList.add('button-selected');

                switch (this.getAttribute('data-content')) {
                    case "Prereglage":
                        contentbox.innerHTML=`
                        <h1 style="text-align: center"><strong style="font-size: larger">/* Pré-réglage */</strong></h1>
                        <p><strong>Pré-Réglages :</strong><br>
                        Avant de commencer à configurer votre tournoi, assurez-vous de bien ajuster les paramètres de base. Ces réglages sont nécessaires pour garantir le bon fonctionnement du plugin HoplaCup. <strong>Voici les différents éléments à configurer :</strong><br>
                        <br>
                        <strong>Étape 1: Modification des Permaliens :</strong><br>
                        Les permaliens définissent l'URL des pages liées à votre tournoi :<span style="color: red"> Réglages -> Permaliens -> Structure des permaliens -> Numérique</span><br>
                        <br>
                        <strong>Étape 2: Modification des réglages de temps :</strong><br>
                        Les réglages de temps doivent être bien réglée pour afficher les bonnes dates dans le plugin :<br>
                            <span style="color: red">• Règlages -> Général -> Fuseau horaire -> Paris<br>
                            • Règlages -> Général -> Format de date -> d/m/Y<br>
                            • Règlages -> Général -> Format d’heure -> H:i<\p></span>`
                        break;
                    case "Utilisation":
                        contentbox.innerHTML=`
            <h1 style="text-align: center"><strong style="font-size: larger">/* Utilisation */</strong></h1>
            <p class="Titre"><strong>Ajouter des Divisions, Clubs et Terrains :</strong></p>
            <p>Dans les sections correspondantes, vous pouvez ajouter des divisions, clubs et terrains pour une meilleure organisation de vos compétitions. Cela permet de structurer les différents niveaux de jeu, d'assigner des équipes à des clubs spécifiques et de préciser les lieux des matchs.</p>

            <p class="Titre"><strong>Créer un Tournoi :</strong></p>
            <p>Accédez à la section « Tournois » pour créer un nouveau tournoi. Une fois le tournoi créé, vous pouvez personnaliser les paramètres (dates, points, etc.) et organiser les différentes étapes du tournoi.</p>

            <p class="Titre"><strong>Étape 1 : Création des Poules de la Phase Initiale</strong></p>
            <p>Dans cette étape, vous pouvez créer des poules pour la phase initiale de la compétition. Utilisez la fonctionnalité de glisser-déposer (drag & drop) pour associer facilement les équipes aux poules.</p>
            <p><span style="color: red">Note importante : Vous ne pourrez pas passer à l'étape 2 tant que vous n'avez pas créé toutes les poules et associé les équipes.</span></p>

            <p class="Titre"><strong>Étape 2 : Gestion des Phases Suivantes et Transferts d’Équipes</strong></p>
            <p>Une fois les poules et les équipes associées, vous pouvez gérer les phases suivantes du tournoi. Cette étape vous permet de définir les matchs à venir et de transférer des équipes entre les différentes phases (par exemple, des phases de groupes aux éliminatoires).</p>

            <p class="Titre"><strong>Étape 3 : Gestion des Matches</strong></p>
            <p>Une fois les matchs générés, associez-les à des terrains et programmez leurs horaires.</p>

            <p class="Titre"><strong>Étape 4 : Gestion du Tournoi</strong></p>
            <p>Cette étape vous permet de consulter les matchs actuels, à venir ou terminés, et de saisir les scores des matchs joués.</p>

            <p class="Titre"><strong>Étape 5 : Visualisation du Tournoi</strong></p>
            <p>Affichez un classement par poule ainsi qu’un classement général.</p>`
                        break;
                    case "UniquePlugin":
                        contentbox.innerHTML=`
                        <h1 style="text-align: center"><strong style="font-size: larger">/* Accès unique au plugin */</strong></h1>
                        <p><strong>Pré-réquis :</strong><br>
                            • Accès <strong>administrateur</strong> au <strong>Wordpress</strong><br>
                            • Plugin <strong>Hopla Cup V2</strong><br>
                            • Plugin <strong>Members</strong> par <strong>Memberpress</strong><br>
                        <br>
                        <strong>Tutoriel: </strong></p>
                        <div class="video-container">
                            <video controls>
                                <source src="<?= plugin_dir_url(__FILE__) . 'video/Membre.mp4'?>" type="video/mp4">
                                Your browser does not support the video tag.
                            </video>
                        </div>
                        `
                        break;
                    default:
                        break;
                }
            });
        });
    });
</script>