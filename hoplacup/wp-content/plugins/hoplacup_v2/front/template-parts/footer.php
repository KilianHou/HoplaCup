<footer>

    <div class="logos-institutionnels">
        <img class="logos-footer" src="<?= dirname(plugin_dir_url(__FILE__))?>/images/logos_institutionnels/CEA.png" alt="logo CEA">
        <img class="logos-footer" src="<?= dirname(plugin_dir_url(__FILE__))?>/images/logos_institutionnels/FFN.png" alt="logo FFN">
        <img class="logos-footer" src="<?= dirname(plugin_dir_url(__FILE__))?>/images/logos_institutionnels/M2A.png" alt="logo M2A">
        <img class="logos-footer" src="<?= dirname(plugin_dir_url(__FILE__))?>/images/logos_institutionnels/RegionGrandEst.png" alt="logo Région Grand Est">
        <img class="logos-footer" src="<?= dirname(plugin_dir_url(__FILE__))?>/images/logos_institutionnels/VilledeMulhouse.png" alt="logo Ville de Mulhouse">
    </div>
    <div class="footer-block">
        <figure class="center">
            <img src="https://mulhousewaterpolo.com/wp-content/uploads/2022/04/logov5_500x500.webp" class="footer-image" alt="mulhouse water-polo">
        </figure>
        <figure class="center">
        <img class= "logo-cigogne" src="https://mulhousewaterpolo.com/wp-content/uploads/2022/05/testlogohoplacup.webp" class="footer-image footer-hoplacup" alt="hoplacup">        </figure>
    </div>

</footer>

<script>
/*no script*/
</script>

<style>

    footer {
        background-color: #000;
    }

    img.footer-image {
        display: inline-block;
        width: 100px;
        height: 100px;
        margin: 20px;
    }

    .logo-cigogne{
        max-width: 200px;
        max-height: 100px;
        width: auto;
        height: auto;
       border-radius: 15% 15% 15% 15%;
        margin: 20px;

    }

    .footer-hoplacup {
        border-radius: 50% 25% 50% 25%;
    }
    
    div.footer-info {
        text-align: center;
        justify-content: center;
        color: #FFF;
        padding: 50px 0 50px 0;
        clear: both;
    }

    p.footer-info-general {
        font-weight: 900;
    }

    div.footer-mention {
        display: flex;
        flex-direction: row;
        justify-content: center;
        margin: 20px;
    }

    div.footer-lien {
        display: flex;
        flex-direction: row;
        justify-content: center;
        margin: 20px;
    }

    a.footer-lien-clic {
        text-decoration: none;
        color: #FFF;
        margin: 20px;
        font-weight: 500;
    }

    .footer-block {
        display: flex;
        flex-direction: row;
        justify-content: center;
    }


    @media only screen and (max-width: 1000px) {
        div.footer-mention {
            flex-direction: column;
        }

        .footer-block {
            flex-direction: column;
            justify-content: center;
        }

        .center {
            text-align: center;
        }

    }

    @media only screen and (max-width: 600px) {
        div.footer-mention {
            flex-direction: column;
        }

        div.footer-lien {
            flex-direction: column;
        }
        
        .logos-institutionnels {
            width: 100%; 
            margin-left: 0; 
        }

        .footer-block {
            flex-direction: column;
            justify-content: center;
        }

}
</style>