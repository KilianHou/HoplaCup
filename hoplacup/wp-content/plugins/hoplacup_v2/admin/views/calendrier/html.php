<h2>Planning des matchs</h2>
<div>
    <form id="date-form" method="post">
        <p>Date: <input type="text" id="datepicker" name="selected_date"></p>
        <input type="hidden" name="selected_date_hidden" id="selected_date_hidden">
    </form>
</div>
<div class="container">
    <div>
        <div>
            <select name="pouleSelect" id="pouleSelect" >
		        <?php foreach ($pouleResults as $pouleResult) : ?>
                    <option value="<?php echo esc_attr($pouleResult->ID); ?>"><?php echo esc_html($pouleResult->Nom); ?></option>
		        <?php endforeach; ?>
            </select>
        </div>
        <table style="width: 150px" class="calendar">
            <thead>
                <tr>
                    <th>Matchs</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($matchToSort as $matchResult) : ?>
                <tr class="matchRow" data-poule-id="<?php echo esc_attr($matchResult->Poules_ID); ?>">
                    <td class="draggable" data-match-id="<?php echo esc_attr($matchResult->ID); ?>">
                        <div class="draggable-match">
                            Poule: <?php echo esc_html($matchResult->nom_poule); ?><br>
                            <?php echo esc_html($matchResult->nom_equipe1); ?><br>
                            -<br>
                            <?php echo esc_html($matchResult->nom_equipe2); ?><br>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>



    <div>
        <div class="widget">
            <fieldset>
            <legend>Choississez le créneau horaire: </legend>
                <button id="morning-button" >Matin</button>
                <button id="afternoon-button" >Après-midi</button>
                <button id="evening-button" >Soir</button>
            </fieldset>
        </div>
        <body>
            <table class="calendar" >
                <thead>
                <tr>
                    <th>Heure</th>
                    <?php

                    foreach ($terrainResults as $terrainResult) {
                        echo "<th>" . $terrainResult->Nom . "</th>";
                    }
                    ?>
                </tr>
                </thead>
                <tbody>
                <?php
                $selectedDate = isset($_GET['selected_date']) ? $_GET['selected_date'] : date('Y-m-d');
                $timePeriod = isset($_GET['time_period']) ? $_GET['time_period'] : 'morning';

                // Générer les lignes pour les intervalles de temps
                if ($timePeriod == 'afternoon') {
	                $heureDebut = strtotime("$selectedDate 12:00");
	                $heureFin = strtotime("$selectedDate 17:00");
                } else if ($timePeriod == 'morning'){
	                $heureDebut = strtotime("$selectedDate 07:00");
	                $heureFin = strtotime("$selectedDate 12:00");
                } else {
	                $heureDebut = strtotime("$selectedDate 17:00");
	                $heureFin = strtotime("$selectedDate 22:00");
                }
                $intervalMatchs = 5;
                $interval = $intervalMatchs * 60;

                while ($heureDebut < $heureFin) {
	                echo "<tr class='heure'>";
	                $heureCourante = date("H:i", $heureDebut);
	                echo "<td class='time'>$heureCourante</td>";

	                // Remplir les cellules avec les matchs correspondants
	                foreach ($terrainResults as $terrainResult) {
		                $matchFound = false;

		                // Recherchez les matchs correspondant à l'heure et au terrain actuels
		                foreach ($matchSorted as $matchResult) {
			                $timestamp = strtotime($matchResult->Temps);

			                $tempsMatch = date("H:i", $timestamp);
			                $dateMatch = date("Y-m-d", $timestamp);
			                if ($tempsMatch == $heureCourante && $dateMatch == $selectedDate) {
				                if ($matchResult->Terrains_id == $terrainResult->ID) {
					                // Affiche les détails du match dans la cellule
					                echo "<td class='draggable' data-match-id='" . esc_attr($matchResult->ID) . "'>";
					                echo "<div class='draggable-match2'>";
					                echo "Poule: " . esc_html($matchResult->nom_poule) . "<br>";
					                echo esc_html($matchResult->nom_equipe1) . "<br>";
					                echo   ' - ' . "<br>";
					                echo esc_html($matchResult->nom_equipe2) . "<br>";
					                echo "</div>";
					                echo "</td>";

					                $matchFound = true;
					                break;
				                }
			                }
		                }

		                // Si aucun match n'est trouvé pour cette heure et ce terrain, laisse la cellule vide
		                if (!$matchFound) {
			                echo "<td class='droppable' data-heure='$heureCourante' data-terrain-id='$terrainResult->ID'></td>";
		                }
	                }

	                echo "</tr>";
	                $heureDebut += $interval;
                }
                ?>
                </tbody>
            </table>
        </body>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('pouleSelect').addEventListener('change', function() {
            var pouleId = this.value;
            var matchRows = document.getElementsByClassName('matchRow');

            for (var i = 0; i < matchRows.length; i++) {
                var matchRow = matchRows[i];
                var rowPouleId = matchRow.getAttribute('data-poule-id');

                if (pouleId === '' || pouleId === rowPouleId) {
                    matchRow.style.display = 'table-row';
                } else {
                    matchRow.style.display = 'none';
                }
            }
        });
    });

    jQuery(document).ready(function($) {

        $("#datepicker").datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: 'yy-mm-dd',
            onSelect: function(dateText) {
                $('#selected_date_hidden').val(dateText);
                // Mettre à jour l'URL avec la date sélectionnée
                var newUrl = updateQueryStringParameter(window.location.href, 'selected_date', dateText);
                window.location.href = newUrl;
                console.log(dateText);
            }
        });

        $('#morning-button').click(function() {
            var newUrl = updateQueryStringParameter(window.location.href, 'time_period', 'morning');
            window.location.href = newUrl;
        });
        $('#afternoon-button').click(function() {
            var newUrl = updateQueryStringParameter(window.location.href, 'time_period', 'afternoon');
            window.location.href = newUrl;
        });
        $('#evening-button').click(function() {
            var newUrl = updateQueryStringParameter(window.location.href, 'time_period', 'evening');
            window.location.href = newUrl;
        });
        function updateQueryStringParameter(uri, key, value) {
            var re = new RegExp("([?&])" + key + "=.*?(&|$)", "i");
            var separator = uri.indexOf('?') !== -1 ? "&" : "?";
            if (uri.match(re)) {
                return uri.replace(re, '$1' + key + "=" + value + '$2');
            } else {
                return uri + separator + key + "=" + value;
            }
        }
        var urlParams = new URLSearchParams(window.location.search);
        var selectedDate = urlParams.get('selected_date');
        if (selectedDate) {
            $("#datepicker").datepicker("setDate", selectedDate);
            $('#selected_date_hidden').val(selectedDate);
        }

        // Rendre les lignes du tableau "draggables"
        $(".draggable").draggable({
            revert: function(dropped) {
                if (!dropped) {
                    return true;
                }
                var droppable = $(dropped)[0];
                if (droppable.classList.contains("droppable")) {
                    return false;
                } else {
                    return true;
                }
            },
            snap: ".droppable",
            cursor: "move",
            cursorAt: { top: -5, left: 75 }
        });

        $(".droppable").droppable({
            accept: ".draggable",
            tolerance: "pointer",

            drop: function( event, ui ) {
                // Récupérer l'heure et l'ID du terrain
                var $this = $(this);

                var heure = $this.data('heure');
                var terrainId = $this.data('terrain-id');
                var matchId = ui.draggable.data('match-id');

                // Récupérer la date choisie dans le datepicker
                var date = $("#selected_date_hidden").val();
                if (!date) {
                    alert("Veuillez sélectionner une date.");
                    return;
                }
                var datetime = date + ' ' + heure + ':00';
                console.log("Heure: " + heure);
                console.log("Terrain ID: " + terrainId);
                console.log("Match ID: " + matchId);
                console.log("Date:" + date);

                $.ajax({
                    url: ajaxurl,
                    method: 'POST',
                    data: {
                        action: 'ajax_calendar_action',
                        datetime: datetime,
                        terrain_id: terrainId,
                        match_id: matchId,
                    },

                    success: function(response) {
                        console.log(response);
                        // ui.item.addClass("ui-state-highlight");
                        console.log("Les données ont été envoyées avec succès !");
                    },

                    error: function(xhr, status, error) {
                        console.error(error);
                        console.log("Une erreur s'est produite lors de l'envoi des données.");
                    }
                });

            }
        });

    });
</script>


<style>
    .container {
        display: flex;
        justify-content: space-between;
    }
    .calendar {
        width: 100%;
        border-collapse: collapse;
        table-layout: fixed;
        margin-right: 20px;
    }

    .calendar th, .calendar td {
        border: 1px solid #ccc;
        text-align: center;
    }
    .calendar tr:nth-child(12n) td {
        border-bottom: 1px solid black;
    }

    .calendar th {
        background-color: #f9f9f9;
        font-weight: bold;
    }
    .calendar td {
        line-height: 12px;
        vertical-align: top;
    }
    .calendar tr:nth-child(even) {
        background-color: #fff9fa;
    }

    .calendar tr:nth-child(odd) {
        background-color: #ffeef0;
    }
    td.time {
        width: 50px;
        background-color: #f9f9f9;
        text-align: center;
        font-weight: bold;
    }
    .calendar .heure td {
        color: #000;
    }
    .droppable {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;

    }
    .heure{
        font-size: 0.7em;
    }
    .draggable{
        position: relative;
    }
    .draggable-match, .draggable-match2  {
        color: #000000;
        font-weight: bold;
        height: 50px;
        background-color: #7df6dd;
        box-shadow: 2px 2px 8px rgba(0,0,0,0.1);
        cursor: move;
        padding: 4px;
        border-radius: 4px;
        margin: 2px;
        z-index: 10;
        width: 93%;
    }
    .draggable-match2 {
        position: absolute;
        top: 0;
        left: 0;
        font-size: 1.3em;

    }
    .widget fieldset {
        border: 1px solid #ccc; /* Bordure de la boîte */
        padding: 10px; /* Espacement interne */
        border-radius: 5px; /* Coins arrondis */
        width: 40%;
        text-align: center;
    }

    .widget legend {
        padding: 0 10px; /* Espacement autour du texte de la légende */
        font-weight: bold; /* Texte en gras */
    }

    .widget button {
        margin: 5px; /* Espacement entre les boutons */
        padding: 10px 20px; /* Espacement interne des boutons */
        border: 1px solid #ccc; /* Bordure des boutons */
        border-radius: 5px; /* Coins arrondis */
        background-color: #f9f9f9; /* Couleur de fond */
        cursor: pointer; /* Curseur pointeur */
    }

    .widget button:hover {
        background-color: #e9e9e9; /* Couleur de fond au survol */
    }
    .widget button.selected {
        background-color: #d1e7dd; /* Couleur de fond pour le bouton sélectionné */
        border-color: #0d6efd; /* Bordure pour le bouton sélectionné */
    }
    .ui-droppable-hover {
        background-color: #5eff6b !important;
    }

    .ui-state-default{
        border: 1px solid #c5c5c5;
        background: #f6f6f6;
        font-weight: normal;
        color: #454545;
    }

</style>