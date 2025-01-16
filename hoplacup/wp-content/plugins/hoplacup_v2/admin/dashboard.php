<main id="dashboard-hoplacup">

    <header>
        <div class="inner container">
            <img src="<?= plugin_dir_url(__FILE__) . 'assets/imgs/logo-hoplacup.png' ?>" alt="">
            <h2>Plugin HoplaCup - V2</h2>
        </div>
    </header>

    <!--
        Menu
    -->
    <nav id="hoplacup-settings-menu">
        <ul>
            <?php foreach ($this->get_menu_views() as $menu_view => $menu_title) :
                $menu_link = esc_url(admin_url($this->settings_root . '&view=' . $menu_view));
            ?>
                <li>
                    <a class="<?= $this->view == $menu_view ? 'active' : '' ?>" href="<?= $menu_link ?>">
                        <?= $menu_title ?>
                    </a>
                </li>
            <?php endforeach ?>
        </ul>
    </nav>

    <!--
        Template view
    -->
    <section id="view-container">
        <?php require_once($this->get_view_html()); ?>
    </section>


</main>