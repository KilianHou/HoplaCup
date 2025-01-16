<header>
    <div class="header-bandeau">
        <?php require_once plugin_dir_path(__FILE__) . 'switch-lang.php'; ?>
    </div>

    <div class="header-container">

        <div class="header">
            <div class="header-logo-club">
                <img src="https://mulhousewaterpolo.com/wp-content/uploads/2022/04/logov5_500x500.webp" class="header-img-logo-water-polo" alt="mulhouse water-polo">
            </div>
            <div class="header-titre">
                <h1 class="header-titre-water-logo">MULHOUSE WATER-POLO</h1>
                <h2 class="header-titre-slogan">Plus qu'un club, une passion !</h2>
            </div>
            <div class="header-evenement">
                <a href="<?= $breadcrumbs['tournois']['link'] ?>" class="header-titre-evenement">HOPLACUP 2024</a>
                <?php if ($display_switch_division) {
                    require_once plugin_dir_path(__FILE__) . 'switch-division.php';
                } ?>
            </div>
            <?php if (isset($division) && $division) : ?>
                <div class="header-dropdown">
                    <span class="menu-title" style="color: #CD0000;">MENU</span>
                    <a href="javascript:myFunction()" class="header-dropbtn">
                        <img class="burger" src="<?= dirname(plugin_dir_url(__FILE__)) ?>/images/burgerMenu.png" alt="Menu">
                    </a>
                </div>
            <?php endif ?>

        </div>

        <hr class="header-hr-titre">

        <?php if (isset($division) && $division) : ?>
            <div id="myDropdown" class="header-dropdown-content">
                <div class="header-center">

                    <div class="header-flex">
                        <a href="<?= $breadcrumbs['division']['link'] . '/equipes' //equipes 
                                    ?>" class="header-content"><?= LISTEEQUIPE ?></a>
                        <a href="<?= $breadcrumbs['division']['link'] . '/programme' //terrain 
                                    ?>" class="header-content"><?= PROGRAMME_VEN_SAM ?></a>
                        <a href="<?= $breadcrumbs['division']['link'] . '/poules'
                                    ?>" class="header-content"><?= POULES_VEN_SAM ?></a>
                        <a href="<?= $breadcrumbs['division']['link'] . '/playoffs'
                                    ?>" class="header-content">Playoffs <?= Dim ?></a>
                        <a href="<?= $breadcrumbs['division']['link'] . '/classement-final'
                                    ?>" class="header-content"><?= CLASSEMENTFINAL ?></a>
                    </div>
                </div>
            </div>
        <?php endif ?>
    </div>

</header>

<?php if (isset($division) && $division) : ?>
    <script>
        let nav = true;

        function myFunction() {
            if (nav) {
                document.getElementById('myDropdown').style.display = 'block';
                nav = false;
            } else {
                document.getElementById('myDropdown').style.display = 'none';
                nav = true;
            }
        }
    </script>
<?php endif ?>

<style>
    .header-container {
        position: relative;
        max-width: 100%;
        margin: auto;
    }

    .header-bandeau {
        width: 100%;
        height: 50px;
        background-color: var(--rouge);
    }

    .header {
        display: flex;
        flex-direction: row;
        width: 100%;
    }

    .header-logo-club {
        width: 15%;
    }

    .header-titre {
        display: flex;
        flex-direction: column;
        justify-content: center;
        gap: 0.25rem;
        width: 60%;
    }

    .header-evenement {
        position: absolute;
        z-index: 99;
        top: 50%;
        right: 0;
        transform: translateY(-50%);
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        width: 20%;
        margin-right: 100px;
    }

    .header-img-logo-water-polo {
        width: 100px;
        height: 100px;
        margin: 20px;
    }

    .header-titre-water-logo {
        margin: 0;
        font-family: "PT Sans", sans-serif;
        font-weight: 700;
    }

    .header-titre-slogan {
        margin: 0;
        color: var(--rouge);
        font-family: "PT Sans", sans-serif;
    }

    .header-titre-evenement {
        text-align: center;
        padding: 10px 10px 10px 10px;
        background-color: #101F8D;
        color: #FFF;
        white-space: nowrap;
        text-decoration: none;
        font-weight: 900;
        height: 40px;
        border: solid white 2px;
        border-radius: 10px;
        box-shadow: black 5px 5px;
        margin-bottom: 20px;
    }


    .header-dropdown {
        position: absolute;
        z-index: 99;
        top: 50%;
        right: 15px;
        transform: translateY(-50%);
    }

    .header-dropdown-content {
        display: none;
        position: absolute;
        z-index: 99;
        left: 0;
        right: 0;
        padding: 50px;
        text-align: center;
        background: #fff;
        border-bottom: 1px solid #ccc;
    }

    .menu-title {
        display: block;
        text-align: center;
        margin-bottom: -10px;
    }

    .header-dropbtn img {
        height: 75px;
        width: 75px;
        vertical-align: middle;
    }

    a.header-content {
        flex-grow: 1;
        flex-basis: 0;
        padding: 15px;
        font-weight: 900;
        background-color: var(--rouge);
        color: #FFF;
        border-radius: 20px;
        width: 150px;
        text-align: center;
        text-decoration: none;

    }

    .header-center {
        flex-wrap: wrap;
        display: flex;
        justify-content: center;
        flex-direction: row;
        gap: 1rem;
    }

    .header-flex {
        justify-content: space-evenly;
        gap: 10px;
        text-align: center;
    }

    .header-hr-titre {
        border: 1px solid #F2F2F2;
    }


    @media only screen and (max-width: 1200px) {
        .header-flex {
            flex-wrap: wrap;
            justify-content: space-evenly;
        }

        #hp-breadcrumbs {
            padding: 0 15px;
        }
    }


    @media only screen and (max-width: 1000px) {

        .header-titre-water-logo {
            font-size: 1.7em;
        }

        .header-titre-slogan {
            font-size: 1.2em;
        }

        .header-titre-evenement {
            font-size: 0.8em;
        }

        #switch-division li {
            font-size: 1rem;
        }
    }

    @media only screen and (max-width: 768px) {

        .header {
            justify-content: space-between;
            padding-right: 100px;
        }

        .header-logo-club {
            width: 90px;
        }

        .header-titre {
            display: none;
        }

        .header-evenement {
            align-self: center;
            position: static;
            display: block;
            width: auto;
            margin: 0;
            padding: 0;
            transform: none;
        }

        .header-titre-evenement,
        #switch-division {
            display: inline-block;
        }

        .header-titre-water-logo {
            display: none;
        }

        .header-titre-slogan {
            display: none;
        }

        .header-img-logo-water-polo {
            width: 60px;
            height: 60px;
        }

        .header-flex {
            flex-direction: column;
            align-items: center;
        }

        a.header-content {
            width: auto;
            margin-bottom: 10px;
            flex-wrap: wrap;
            display: inline-block;
        }

    }


    @media only screen and (max-width: 600px) {

        a.header-titre-evenement {
            display: block;
            margin-bottom: 10px;
        }

        .header-titre {
            width: 0%;
        }

        .header-evenement {
            right: 5%;
        }

        .header-flex {
            flex-direction: column;
            flex-wrap: wrap;
            align-items: center;

        }

        a.header-content {
            width: auto;
            margin-bottom: 10px;
            display: inline-block;
        }

        .header-dropdown-content {
            padding: 20px;

        }

    }

    @media only screen and (max-width: 400px) {

        a.header-titre-evenement {
            display: block;
            margin-bottom: 10px;
        }

        .header-titre {
            width: 0%;
        }

        .header-evenement {
            right: 5%;
        }

        .header-flex {
            flex-direction: column;
            flex-wrap: wrap;
            align-items: center;
            font-size: 0.8em;
        }

        a.header-content {
            width: auto;
            margin-bottom: 10px;
            display: inline-block;
        }

        .header-dropdown-content {
            padding: 10px;

        }

    }
</style>