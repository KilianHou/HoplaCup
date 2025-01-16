<!--Cruds sur les équipes-->
<h1>Gestion des équipes</h1>

<div class="wrap">
    <h2>CRUD Operations</h2>
    <table class="wp-list-table widefat striped">
        <thead>
        <tr>
            <th width="20%">ID</th>
            <th width="20%">Nom</th>
            <th width="20%">Club</th>
            <th width="20%">Logo</th>
            <th width="20%">Division</th>
            <th width="20%">Actions</th>
        </tr>
        </thead>
        <tbody>
        <form action="" method="post">
            <tr>
                <td><input type="text" value="AUTO_GENERATED" disabled></td>
                <td><input type="text" id="nomEquipe" name="nomEquipe" required></td>
                <td><select id="nomClub" name="nomClub">
		            <?php foreach ($clubsSelect as $clubSelect) : ?>
                        <option value="<?= $clubSelect->id ?>"><?= $clubSelect->Nom ?></option>
		            <?php endforeach; ?>
                </select></td>
                <td><input type="text" value="GENERER DANS CLUB" disabled></td>
                <td><select id="nomDivision" name="nomDivision">
		            <?php foreach ($divisionsSelect as $divisionSelect) : ?>
                        <option value="<?= $divisionSelect->id ?>"><?= $divisionSelect->Division ?></option>
		            <?php endforeach; ?>
                </select></td>
                <td><button id="submitEquipe" name="submitEquipe" type="submit">INSERT</button></td>
            </tr>
        </form>
        <?php foreach ($equipeResults as $equipeResult) : ?>
            <form method="post" action="admin.php?page=hoplacup-v2-settings&view=equipes&update">
                <tr>
                    <td width='20%'><input type="text" name="id" value="<?= $equipeResult->id ?>" readonly></td>
                    <td width='20%'><input type="text" name="nomEquipe" value="<?= $equipeResult->NomEquipe ?>"></td>
                    <td width='20%'>
                        <select name="nomClub">
					        <?php foreach ($clubsSelect as $clubSelect) : ?>
                                <option value="<?= $clubSelect->id ?>" <?= $equipeResult->NomClub == $clubSelect->Nom ? 'selected' : '' ?>><?= $clubSelect->Nom ?></option>
					        <?php endforeach; ?>
                        </select>
                    </td>
                    <td width='20%'><img src="<?= wp_get_attachment_image_url($equipeResult->LogoClub, 'thumbnail') ?>" alt="Image du club" /></td>
                    <td width='20%'>
                        <select name="nomDivision">
                            <option value="">Aucune division</option>
                            <?php foreach ($divisionsSelect as $divisionSelect) : ?>
                                <option value="<?= $divisionSelect->id ?>" <?= $equipeResult->NomDivision == $divisionSelect->Division ? 'selected' : '' ?>><?= $divisionSelect->Division ?></option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                    <td width='20%'>
                        <button type="submit" name="update">Mettre à jour</button>
                        <a href='admin.php?page=hoplacup-v2-settings&view=equipes&del=<?= $equipeResult->id ?>'><button type="button">Supprimer</button></a>
                    </td>
                </tr>
            </form>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>



<!--Cruds sur les divisions-->
<!--<h1>Gestion des divisions</h1>-->
<!---->
<!--<div class="wrap">-->
<!--    <h2>CRUD Operations</h2>-->
<!--    <table class="wp-list-table widefat striped">-->
<!--        <thead>-->
<!--        <tr>-->
<!--            <th width="20%">ID</th>-->
<!--            <th width="20%">Nom</th>-->
<!--            <th width="20%">Actions</th>-->
<!---->
<!--        </tr>-->
<!--        </thead>-->
<!--        <tbody>-->
<!--        <form action="" method="post">-->
<!--            <tr>-->
<!--                <td><input type="text" value="AUTO_GENERATED" disabled></td>-->
<!--                <td><input type="text" id="nomDivision" name="nomDivision"></td>-->
<!--                <td><button id="submitDivision" name="submitDivision" type="submit">INSERT</button></td>-->
<!--            </tr>-->
<!--        </form>-->
<!--		--><?php //foreach ($divisionResults as $divisionResult) : ?>
<!--            <tr>-->
<!--                <td width='20%'>--><?php //= $divisionResult->id ?><!--</td>-->
<!--                <td width='20%'>--><?php //= $divisionResult->Nom ?><!--</td>-->
<!--                <td width='20%'>-->
<!--                    <a href='admin.php?page=hoplacup-v2-settings&view=equipes&upt=--><?php //= $divisionResult->id ?><!--'><button type='button'>UPDATE</button></a>-->
<!--                    <a href='admin.php?page=hoplacup-v2-settings&view=equipes&del=--><?php //= $divisionResult->id ?><!--'><button type='button'>DELETE</button></a>-->
<!--                </td>-->
<!--            </tr>-->
<!--		--><?php //endforeach; ?>
<!--        </tbody>-->
<!--    </table>-->
<!--</div>-->
