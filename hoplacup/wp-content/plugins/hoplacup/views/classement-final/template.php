<?php

$baselink = $breadcrumbs['division']['link'];

/*print_r($classement_hopla_table);
echo "testjr";*/
?>

<main class="container">

    <h1>
        <?= TOURNOI ?> <?= $division_name ?>
        <span><?= CLASSEMENTFINAL ?></span>
    </h1>

    <div class="container-medium">

        <h2 class="hopla">
            <img class="mascotte" src="/wp-content/plugins/hoplacup/images/mascotte-hopla-nobg.png">
            Hopla Cup
            <img class="mascotte" src="/wp-content/plugins/hoplacup/images/mascotte-hopla-nobg.png">
        </h2>

        <!-- /////////////// classements Hopla -->
        <div class="parentHopla">
            <div class="div1">
                <?php if (isset($classement_hopla_table[0]) && !empty($classement_hopla_table[0])) : ?>
                    <a href="<?= $baselink ?>/equipes/<?= $classement_hopla_table[0]->Identifiant ?>">
                        <img class="hoplaOr" src="/wp-content/plugins/hoplacup/images/hoplaOr.png">
                        <?php if (!empty($classement_hopla_table[0]->Identifiant_Club)) : ?>
                            <img class="team-logo" src="<?= dirname(dirname(plugin_dir_url(__FILE__))) ?>/images/logos_clubs/<?= $classement_hopla_table[0]->Identifiant_Club ?>.webp">
                        <?php endif; ?>
                        <?= $classement_hopla_table[0]->Equipe ?>
                    </a>
                <?php else : ?>
                    <?= "" ?>
                <?php endif; ?>
            </div>
            <div class="div2">
                <?php if (isset($classement_hopla_table[1]) && !empty($classement_hopla_table[1])) : ?>
                    <a href="<?= $baselink ?>/equipes/<?= $classement_hopla_table[1]->Identifiant ?>">
                        <img class="hoplaArgent" src="/wp-content/plugins/hoplacup/images/hoplaArgent.png">
                        <?php if (!empty($classement_hopla_table[1]->Identifiant_Club)) : ?>
                            <img class="team-logo" src="<?= dirname(dirname(plugin_dir_url(__FILE__))) ?>/images/logos_clubs/<?= $classement_hopla_table[1]->Identifiant_Club ?>.webp">
                        <?php endif; ?>
                        <?= $classement_hopla_table[1]->Equipe ?>
                    </a>
                <?php else : ?>
                    <?= "" ?>
                <?php endif; ?>
            </div>
            <div class="div3">
                <?php if (isset($classement_hopla_table[2]) && !empty($classement_hopla_table[2])) : ?>
                    <a href="<?= $baselink ?>/equipes/<?= $classement_hopla_table[2]->Identifiant ?>">
                        <img class="hoplaBronze" src="/wp-content/plugins/hoplacup/images/hoplaBronze.png">
                        <?php if (!empty($classement_hopla_table[2]->Identifiant_Club)) : ?>
                            <img class="team-logo" src="<?= dirname(dirname(plugin_dir_url(__FILE__))) ?>/images/logos_clubs/<?= $classement_hopla_table[2]->Identifiant_Club ?>.webp">
                        <?php endif; ?>
                        <?= $classement_hopla_table[2]->Equipe ?>
                    </a>
                <?php else : ?>
                    <?= "" ?>
                <?php endif; ?>
            </div>
            <div class="div4"></div>
            <div class="div5"></div>
            <div class="div6"></div>
            <div class="div7"></div>
            <div class="div8">
                <?php if (isset($classement_hopla_table[3]) && !empty($classement_hopla_table[3])) : ?>
                    <?= $classement_hopla_table[3]->Classement ?>
                <?php else : ?>
                    <?= "" ?>
                <?php endif; ?>
            </div>
            <div class="div9">
                <?php if (isset($classement_hopla_table[3]) && !empty($classement_hopla_table[3])) : ?>
                    <a href="<?= $baselink ?>/equipes/<?= $classement_hopla_table[3]->Identifiant ?>">
                        <?php if (!empty($classement_hopla_table[3]->Identifiant_Club)) : ?>
                            <img class="team-logo" src="<?= dirname(dirname(plugin_dir_url(__FILE__))) ?>/images/logos_clubs/<?= $classement_hopla_table[3]->Identifiant_Club ?>.webp">
                        <?php endif; ?>
                        <?= $classement_hopla_table[3]->Equipe ?>
                    </a>
                <?php endif; ?>
            </div>
            <div class="div10">
                <?php if (isset($classement_hopla_table[4]) && !empty($classement_hopla_table[4])) : ?>
                    <?= $classement_hopla_table[4]->Classement ?>
                <?php else : ?>
                    <?= "" ?>
                <?php endif; ?>
            </div>
            <div class="div11">
                <?php if (isset($classement_hopla_table[4]) && !empty($classement_hopla_table[4])) : ?>
                    <a href="<?= $baselink ?>/equipes/<?= $classement_hopla_table[4]->Identifiant ?>">
                        <?php if (!empty($classement_hopla_table[4]->Identifiant_Club)) : ?>
                            <img class="team-logo" src="<?= dirname(dirname(plugin_dir_url(__FILE__))) ?>/images/logos_clubs/<?= $classement_hopla_table[4]->Identifiant_Club ?>.webp">
                        <?php endif; ?>
                        <?= $classement_hopla_table[4]->Equipe ?>
                    </a>
                <?php endif; ?>
            </div>
                <div class="div12">
                <?php if (isset($classement_hopla_table[5]) && !empty($classement_hopla_table[5])) : ?>
                    <?= $classement_hopla_table[5]->Classement ?>
                <?php else : ?>
                    <?= "" ?>
                <?php endif; ?>
            </div>
                <div class="div13">
                <?php if (isset($classement_hopla_table[5]) && !empty($classement_hopla_table[5])) : ?>
                    <a href="<?= $baselink ?>/equipes/<?= $classement_hopla_table[5]->Identifiant ?>" class="high-z-index">
                        <?php if (!empty($classement_hopla_table[5]->Identifiant_Club)) : ?>
                            <img class="team-logo" src="<?= dirname(dirname(plugin_dir_url(__FILE__))) ?>/images/logos_clubs/<?= $classement_hopla_table[5]->Identifiant_Club ?>.webp">
                        <?php endif; ?>
                        <?= $classement_hopla_table[5]->Equipe ?>
                    </a>
                    <?php endif; ?>
                </div>
            <?php if ($division == 'u11' || $division == "u13") : ?>
                <div class="div14">
                <?php if (isset($classement_hopla_table[6]) && !empty($classement_hopla_table[6])) : ?>
                    <?= $classement_hopla_table[6]->Classement ?>
                <?php else : ?>
                    <?= "" ?>
                <?php endif; ?>
            </div>
                <div class="div15">
                <?php if (isset($classement_hopla_table[6]) && !empty($classement_hopla_table[6])) : ?>
                    <a href="<?= $baselink ?>/equipes/<?= $classement_hopla_table[6]->Identifiant ?>">
                        <?php if (!empty($classement_hopla_table[6]->Identifiant_Club)) : ?>
                            <img class="team-logo" src="<?= dirname(dirname(plugin_dir_url(__FILE__))) ?>/images/logos_clubs/<?= $classement_hopla_table[6]->Identifiant_Club ?>.webp">
                        <?php endif; ?>
                        <?= $classement_hopla_table[6]->Equipe ?>
                    </a>
                <?php endif; ?>
            </div>
            <div class="div16">
                <?php if (isset($classement_hopla_table[7]) && !empty($classement_hopla_table[7])) : ?>
                    <?= $classement_hopla_table[7]->Classement ?>
                <?php else : ?>
                    <?= "" ?>
                <?php endif; ?>
            </div>
            <div class="div17">
                <?php if (isset($classement_hopla_table[7]) && !empty($classement_hopla_table[7])) : ?>
                    <a href="<?= $baselink ?>/equipes/<?= $classement_hopla_table[7]->Identifiant ?>">
                        <?php if (!empty($classement_hopla_table[7]->Identifiant_Club)) : ?>
                            <img class="team-logo" src="<?= dirname(dirname(plugin_dir_url(__FILE__))) ?>/images/logos_clubs/<?= $classement_hopla_table[7]->Identifiant_Club ?>.webp">
                        <?php endif; ?>
                        <?= $classement_hopla_table[7]->Equipe ?>
                    </a>
                <?php endif; ?>
            </div>
            <?php else : ?>
                <div style="border-bottom: 3px solid" class="div12"> <?php echo isset($classement_hopla[5]) ? $classement_hopla[5]->Classement : ''; ?></div>
                <div style="border-bottom: 3px solid" class="div13"> <?php echo isset($classement_hopla[5]) ? $classement_hopla[5]->Equipe : ''; ?></div>

            <?php endif ?>
        </div>

        <?php if (!empty($classement_castor_table) && isset($classement_castor_table[0])) : ?>
            <h2 class="castor">
                <img class="mascotte" src="/wp-content/plugins/hoplacup/images/mascotte-castor-nobg.png">
                Castor Cup
                <img class="mascotte" src="/wp-content/plugins/hoplacup/images/mascotte-castor-nobg.png">
            </h2>

            <!-- /////////////// classements Castor -->
            <div class="parentCastor">
                <div class="div1">
                    <?php if (isset($classement_castor_table[0]) && !empty($classement_castor_table[0])) : ?>
                        <a href="<?= $baselink ?>/equipes/<?= $classement_castor_table[0]->Identifiant ?>">
                            <img class="hoplaOr" src="/wp-content/plugins/hoplacup/images/hoplaOr.png">
                            <?php if (!empty($classement_castor_table[0]->Identifiant_Club)) : ?>
                                <img class="team-logo" src="<?= dirname(dirname(plugin_dir_url(__FILE__))) ?>/images/logos_clubs/<?= $classement_castor_table[0]->Identifiant_Club ?>.webp">
                            <?php endif; ?>
                            <?= $classement_castor_table[0]->Equipe ?>
                        </a>
                    <?php else : ?>
                        <?= "" ?>
                    <?php endif; ?>
                </div>
                <div class="div2">
                    <?php if (isset($classement_castor_table[1]) && !empty($classement_castor_table[1])) : ?>
                        <a href="<?= $baselink ?>/equipes/<?= $classement_castor_table[1]->Identifiant ?>">
                            <img class="hoplaArgent" src="/wp-content/plugins/hoplacup/images/hoplaArgent.png">
                            <?php if (!empty($classement_castor_table[1]->Identifiant_Club)) : ?>
                                <img class="team-logo" src="<?= dirname(dirname(plugin_dir_url(__FILE__))) ?>/images/logos_clubs/<?= $classement_castor_table[1]->Identifiant_Club ?>.webp">
                            <?php endif; ?>
                            <?= $classement_castor_table[1]->Equipe ?>
                        </a>
                    <?php else : ?>
                        <?= "" ?>
                    <?php endif; ?>
                </div>
                <div class="div3">
                    <?php if (isset($classement_castor_table[2]) && !empty($classement_castor_table[2])) : ?>
                        <a href="<?= $baselink ?>/equipes/<?= $classement_castor_table[2]->Identifiant ?>">
                            <img class="hoplaBronze" src="/wp-content/plugins/hoplacup/images/hoplaBronze.png">
                            <?php if (!empty($classement_castor_table[2]->Identifiant_Club)) : ?>
                                <img class="team-logo" src="<?= dirname(dirname(plugin_dir_url(__FILE__))) ?>/images/logos_clubs/<?= $classement_castor_table[2]->Identifiant_Club ?>.webp">
                            <?php endif; ?>
                            <?= $classement_castor_table[2]->Equipe ?>
                        </a>
                    <?php else : ?>
                        <?= "" ?>
                    <?php endif; ?>
                </div>
                <div class="div4"></div>
                <div class="div5"></div>
                <div class="div6"></div>
                <div class="div7"></div>
                <div class="div8">
                    <?php if (isset($classement_castor_table[3]) && !empty($classement_castor_table[3])) : ?>
                        <?= $classement_castor_table[3]->Classement ?>
                    <?php else : ?>
                        <?= "" ?>
                    <?php endif; ?>
                </div>
                <div class="div9">
                    <?php if (isset($classement_castor_table[3]) && !empty($classement_castor_table[3])) : ?>
                        <a href="<?= $baselink ?>/equipes/<?= $classement_castor_table[3]->Identifiant?>">
                            <?php if (!empty($classement_castor_table[3]->Identifiant_Club)) : ?>
                                <img class="team-logo" src="<?= dirname(dirname(plugin_dir_url(__FILE__))) ?>/images/logos_clubs/<?= $classement_castor_table[3]->Identifiant_Club ?>.webp">
                            <?php endif; ?>
                            <?= $classement_castor_table[3]->Equipe ?>
                        </a>
                    <?php endif; ?>
                </div>
                <div class="div10">
                    <?php if (isset($classement_castor_table[4]) && !empty($classement_castor_table[4])) : ?>
                        <?= $classement_castor_table[4]->Classement ?>
                    <?php else : ?>
                        <?= "" ?>
                    <?php endif; ?>
                </div>
                <div class="div11">
                    <?php if (isset($classement_castor_table[4]) && !empty($classement_castor_table[4])) : ?>
                        <a href="<?= $baselink ?>/equipes/<?= $classement_castor_table[4]->Identifiant ?>">
                            <?php if (!empty($classement_castor_table[4]->Identifiant_Club)) : ?>
                                <img class="team-logo" src="<?= dirname(dirname(plugin_dir_url(__FILE__))) ?>/images/logos_clubs/<?= $classement_castor_table[4]->Identifiant_Club ?>.webp">
                            <?php endif; ?>
                            <?= $classement_castor_table[4]->Equipe ?>
                        </a>
                    <?php endif; ?>
                </div>
                <div class="div12">
                    <?php if (isset($classement_castor_table[5]) && !empty($classement_castor_table[5])) : ?>
                        <?= $classement_castor_table[5]->Classement ?>
                    <?php else : ?>
                        <?= "" ?>
                    <?php endif; ?>
                </div>
                <div class="div13">
                    <?php if (isset($classement_castor_table[5]) && !empty($classement_castor_table[5])) : ?>
                        <a href="<?= $baselink ?>/equipes/<?= $classement_castor_table[5]->Identifiant ?>">
                            <?php if (!empty($classement_castor_table[5]->Identifiant_Club)) : ?>
                                <img class="team-logo" src="<?= dirname(dirname(plugin_dir_url(__FILE__))) ?>/images/logos_clubs/<?= $classement_castor_table[5]->Identifiant_Club ?>.webp">
                            <?php endif; ?>
                            <?= $classement_castor_table[5]->Equipe ?>
                        </a>
                    <?php endif; ?>
                </div>
                <div class="div14">
                    <?php if (isset($classement_castor_table[6]) && !empty($classement_castor_table[6])) : ?>
                        <?= $classement_castor_table[6]->Classement ?>
                    <?php else : ?>
                        <?= "" ?>
                    <?php endif; ?>
                </div>
                <div class="div15">
                    <?php if (isset($classement_castor_table[6]) && !empty($classement_castor_table[6])) : ?>
                        <a href="<?= $baselink ?>/equipes/<?= $classement_castor_table[6]->Identifiant ?>">
                            <?php if (!empty($classement_castor_table[6]->Identifiant_Club)) : ?>
                                <img class="team-logo" src="<?= dirname(dirname(plugin_dir_url(__FILE__))) ?>/images/logos_clubs/<?= $classement_castor_table[6]->Identifiant_Club ?>.webp">
                            <?php endif; ?>
                            <?= $classement_castor_table[6]->Equipe ?>
                        </a>
                    <?php endif; ?>
                </div>
                <div class="div16">
                    <?php if (isset($classement_castor_table[7]) && !empty($classement_castor_table[7])) : ?>
                        <?= $classement_castor_table[7]->Classement ?>
                    <?php else : ?>
                        <?= "" ?>
                    <?php endif; ?>
                </div>
                <div class="div17">
                    <?php if (isset($classement_castor_table[7]) && !empty($classement_castor_table[7])) : ?>
                        <a href="<?= $baselink ?>/equipes/<?= $classement_castor_table[7]->Identifiant ?>">
                            <?php if (!empty($classement_castor_table[7]->Identifiant_Club)) : ?>
                                <img class="team-logo" src="<?= dirname(dirname(plugin_dir_url(__FILE__))) ?>/images/logos_clubs/<?= $classement_castor_table[7]->Identifiant_Club ?>.webp">
                            <?php endif; ?>
                            <?= $classement_castor_table[7]->Equipe ?>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif ?>

        <?php if (!empty($classement_hamster_table) && isset($classement_hamster_table[0])) : ?>
            <h2 class="hamster">
                <img class="mascotte" src="/wp-content/plugins/hoplacup/images/mascotte-hamster-nobg.png">
                Hamster Cup
                <img class="mascotte" src="/wp-content/plugins/hoplacup/images/mascotte-hamster-nobg.png">
            </h2>

            <!-- /////////////// classements Hamster -->
            <div class="parentHamster">
                <div class="div1">
                    <?php if (isset($classement_hamster_table[0]) && !empty($classement_hamster_table[0])) : ?>
                        <a href="<?= $baselink ?>/equipes/<?= $classement_hamster_table[0]->Identifiant ?>">
                            <img class="hoplaOr" src="/wp-content/plugins/hoplacup/images/hoplaOr.png">
                            <?php if (!empty($classement_hamster_table[0]->Identifiant_Club)) : ?>
                                <img class="team-logo" src="<?= dirname(dirname(plugin_dir_url(__FILE__))) ?>/images/logos_clubs/<?= $classement_hamster_table[0]->Identifiant_Club ?>.webp">
                            <?php endif; ?>
                            <?= $classement_hamster_table[0]->Equipe ?>
                        </a>
                    <?php else : ?>
                        <?= "" ?>
                    <?php endif; ?>
                </div>
                <div class="div2">
                    <?php if (isset($classement_hamster_table[1]) && !empty($classement_hamster_table[1])) : ?>
                        <a href="<?= $baselink ?>/equipes/<?= $classement_hamster_table[1]->Identifiant ?>">
                            <img class="hoplaArgent" src="/wp-content/plugins/hoplacup/images/hoplaArgent.png">
                            <?php if (!empty($classement_hamster_table[1]->Identifiant_Club)) : ?>
                                <img class="team-logo" src="<?= dirname(dirname(plugin_dir_url(__FILE__))) ?>/images/logos_clubs/<?= $classement_hamster_table[1]->Identifiant_Club ?>.webp">
                            <?php endif; ?>
                            <?= $classement_hamster_table[1]->Equipe ?>
                        </a>
                    <?php else : ?>
                        <?= "" ?>
                    <?php endif; ?>
                </div>
                <div class="div3">
                    <?php if (isset($classement_hamster_table[2]) && !empty($classement_hamster_table[2])) : ?>
                        <a href="<?= $baselink ?>/equipes/<?= $classement_hamster_table[2]->Identifiant ?>">
                            <img class="hoplaBronze" src="/wp-content/plugins/hoplacup/images/hoplaBronze.png">
                            <?php if (!empty($classement_hamster_table[2]->Identifiant_Club)) : ?>
                                <img class="team-logo" src="<?= dirname(dirname(plugin_dir_url(__FILE__))) ?>/images/logos_clubs/<?= $classement_hamster_table[2]->Identifiant_Club ?>.webp">
                            <?php endif; ?>
                            <?= $classement_hamster_table[2]->Equipe ?>
                        </a>
                    <?php else : ?>
                        <?= "" ?>
                    <?php endif; ?>
                </div>
                <div class="div4"></div>
                <div class="div5"></div>
                <div class="div6"></div>
                <div class="div7"></div>
                <div class="div8">
                    <?php if (isset($classement_hamster_table[3]) && !empty($classement_hamster_table[3])) : ?>
                        <?= $classement_hamster_table[3]->Classement ?>
                    <?php else : ?>
                        <?= "" ?>
                    <?php endif; ?>
                </div>
                <div class="div9">
                    <?php if (isset($classement_hamster_table[3]) && !empty($classement_hamster_table[3])) : ?>
                        <a href="<?= $baselink ?>/equipes/<?= $classement_hamster_table[3]->Identifiant ?>">
                            <?php if (!empty($classement_hamster_table[3]->Identifiant_Club)) : ?>
                                <img class="team-logo" src="<?= dirname(dirname(plugin_dir_url(__FILE__))) ?>/images/logos_clubs/<?= $classement_hamster_table[3]->Identifiant_Club ?>.webp">
                            <?php endif; ?>
                            <?= $classement_hamster_table[3]->Equipe ?>
                        </a>
                    <?php endif; ?>
                </div>
                <div class="div10">
                    <?php if (isset($classement_hamster_table[4]) && !empty($classement_hamster_table[4])) : ?>
                        <?= $classement_hamster_table[4]->Classement ?>
                    <?php else : ?>
                        <?= "" ?>
                    <?php endif; ?>
                </div>
                <div class="div11">
                    <?php if (isset($classement_hamster_table[4]) && !empty($classement_hamster_table[4])) : ?>
                        <a href="<?= $baselink ?>/equipes/<?= $classement_hamster_table[4]->Identifiant ?>">
                            <?php if (!empty($classement_hamster_table[4]->Identifiant_Club)) : ?>
                                <img class="team-logo" src="<?= dirname(dirname(plugin_dir_url(__FILE__))) ?>/images/logos_clubs/<?= $classement_hamster_table[4]->Identifiant_Club ?>.webp">
                            <?php endif; ?>
                            <?= $classement_hamster_table[4]->Equipe ?>
                        </a>
                    <?php endif; ?>
                </div>
                <div class="div12">
                    <?php if (isset($classement_hamster_table[5]) && !empty($classement_hamster_table[5])) : ?>
                        <?= $classement_hamster_table[5]->Classement ?>
                    <?php else : ?>
                        <?= "" ?>
                    <?php endif; ?>
                </div>
                <div class="div13">
                    <?php if (isset($classement_hamster_table[5]) && !empty($classement_hamster_table[5])) : ?>
                        <a href="<?= $baselink ?>/equipes/<?= $classement_hamster_table[5]->Identifiant ?>">
                            <?php if (!empty($classement_hamster_table[5]->Identifiant_Club)) : ?>
                                <img class="team-logo" src="<?= dirname(dirname(plugin_dir_url(__FILE__))) ?>/images/logos_clubs/<?= $classement_hamster_table[5]->Identifiant_Club ?>.webp">
                            <?php endif; ?>
                            <?= $classement_hamster_table[5]->Equipe ?>
                        </a>
                    <?php endif; ?>
                </div>
                <div class="div14">
                    <?php if (isset($classement_hamster_table[6]) && !empty($classement_hamster_table[6])) : ?>
                        <?= $classement_hamster_table[6]->Classement ?>
                    <?php else : ?>
                        <?= "" ?>
                    <?php endif; ?>
                </div>
                <div class="div15">
                    <?php if (isset($classement_hamster_table[6]) && !empty($classement_hamster_table[6])) : ?>
                        <a href="<?= $baselink ?>/equipes/<?= $classement_hamster_table[6]->Identifiant ?>">
                            <?php if (!empty($classement_hamster_table[6]->Identifiant_Club)) : ?>
                                <img class="team-logo" src="<?= dirname(dirname(plugin_dir_url(__FILE__))) ?>/images/logos_clubs/<?= $classement_hamster_table[6]->Identifiant_Club ?>.webp">
                            <?php endif; ?>
                            <?= $classement_hamster_table[6]->Equipe ?>
                        </a>
                    <?php endif; ?>
                </div>
                <div class="div16">
                    <?php if (isset($classement_hamster_table[7]) && !empty($classement_hamster_table[7])) : ?>
                        <?= $classement_hamster_table[7]->Classement ?>
                    <?php else : ?>
                        <?= "" ?>
                    <?php endif; ?>
                </div>
                <div class="div17">
                    <?php if (isset($classement_hamster_table[7]) && !empty($classement_hamster_table[7])) : ?>
                        <a href="<?= $baselink ?>/equipes/<?= $classement_hamster_table[7]->Identifiant ?>">
                            <?php if (!empty($classement_hamster_table[7]->Identifiant_Club)) : ?>
                                <img class="team-logo" src="<?= dirname(dirname(plugin_dir_url(__FILE__))) ?>/images/logos_clubs/<?= $classement_hamster_table[7]->Identifiant_Club ?>.webp">
                            <?php endif; ?>
                            <?= $classement_hamster_table[7]->Equipe ?>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif ?>
    </div>
</main>
