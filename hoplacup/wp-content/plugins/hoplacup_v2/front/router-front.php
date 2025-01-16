<?php

/**
 * Déclaration de la règle de réécriture d'URLs du plugin
 */
function hoplacup_rewrite_rule()
{
    add_rewrite_rule(
        '^tournois(?:/(en|de))?/?((u13|u9|u11)(?:/([^/]+))?(?:/([A-Za-z0-9]+))?/?)?$',
        'index.php?hoplacup_param=1&lang=$matches[1]&division=$matches[3]&rubrique=$matches[4]&id_resource=$matches[5]',
        'top'
    );
    flush_rewrite_rules(false);
}
add_action('init', 'hoplacup_rewrite_rule');

/**
 * Déclaration des paramètres de requête personnalisés
 */
function hoplacup_query_vars($vars)
{
    $vars[] = 'hoplacup_param';
    $vars[] = 'lang';
    $vars[] = 'division';
    $vars[] = 'rubrique'; // contient la page spécifique d'une division à afficher
    $vars[] = 'id_resource'; // parametre optionnel pour la page spécifique (exemple : l'identifiant d'une équipe )
    return $vars;
}
add_filter('query_vars', 'hoplacup_query_vars');

/**
 * Fonction qui s'occupe "d'overrider" l'affichage par défaut de Wordpress
 */
function hoplacup_template_redirect()
{
    global $public_root_slug;
    $public_root_slug = HoplaCupV2::PUBLIC_ROOT_SLUG ;

    $hoplacup_param = get_query_var('hoplacup_param');
    $lang           = get_query_var('lang');
    $division       = get_query_var('division');
    $rubrique       = get_query_var('rubrique');
    $id_resource    = get_query_var('id_resource');

    error_log('hoplacup_param: ' . print_r($hoplacup_param, true));
    error_log('lang: ' . print_r($lang, true));
    error_log('division: ' . print_r($division, true));
    error_log('rubrique: ' . print_r($rubrique, true));
    error_log('id_resource: ' . print_r($id_resource, true));

    if ($hoplacup_param) {
        // Charger le builder de la vue
        require_once plugin_dir_path(__FILE__) . 'view_builder.php';

        $page_parameters = array();
        $page_parameters['public_root_slug'] = $public_root_slug;
        $page_parameters['public_root_url'] = site_url() . '/tournois';
        $page_parameters['url'] = site_url() . '/tournois';
        $page_parameters['url_division'] = '';
        $page_parameters['lang'] = '';
        $page_parameters['division'] = '';
        $page_parameters['division_name'] = '';
        $page_parameters['page_title'] = 'Hopla Cup';
        $page_parameters['view'] = '';
        $page_parameters['display_switch_division'] = false;
        $page_parameters['id_resource'] = $id_resource;
        $page_parameters['breadcrumbs'] = array();

        // Première étape du fil d'ariane
        $page_parameters['breadcrumbs']['tournois'] = ['title' => 'Tournois', 'link' => $page_parameters['url']]; // ajout d'une étape au fil d'ariane

        // Si pas de langue spécifiée, langue = FR
        $page_parameters['lang'] = !empty($lang) ? $lang : '';
        switch ($page_parameters['lang']) {
            case 'de':
                require_once plugin_dir_path(__FILE__) . 'translations/text-de.php';
                break;
            case 'en':
                require_once plugin_dir_path(__FILE__) . 'translations/text-en.php';
                break;
            default:
                require_once plugin_dir_path(__FILE__) . 'translations/text-fr.php';
                break;
        }
        if ($lang != 'fr') {
            $page_parameters['url'] .= '/' . $lang;
        }
        $page_parameters['breadcrumbs']['tournois']['title'] = TOURNOIS;

        // Si pas de division, on affiche l'accueil des Tournois
        if (!isset($division) || $division == '') {
            load_view('home-tournois', $page_parameters);
            exit;
        }

        if ($division === 'u9') {
            $page_parameters['division_name'] = 'U-9'; // nom de division pour affichage
        } elseif ($division === 'u11') {
            $page_parameters['division_name'] = 'U-11'; // nom de division pour affichage
        } else {
            $page_parameters['division_name'] = 'U-13';
        }
        $page_parameters['url'] .= '/' . $division;
        $page_parameters['division'] = $division;
        $page_parameters['breadcrumbs']['division'] = ['title' => $page_parameters['division_name'], 'link' => $page_parameters['url']];

        // Si pas de page spécifiée, on affiche l'accueil de la division
        if (!isset($rubrique) || $rubrique == '') {
            load_view('home-division', $page_parameters);
            exit;
        }

        // On cherche une rubrique qui pourrait matcher, charger sa view si c'est le cas
        switch ($rubrique) {
            case 'programme':
                $page_parameters['url'] .= '/programme';
                $page_parameters['url_division'] .= '/programme';
                $page_parameters['breadcrumbs'][] = ['title' => PROGRAMME2, 'link' => $page_parameters['url']];
                // Charger la vue programme
                load_view('programme', $page_parameters);
                exit;

            case 'equipes':
                $page_parameters['url'] .= '/equipes';
                $page_parameters['url_division'] .= '/equipes';
                $page_parameters['breadcrumbs'][] = ['title' => LISTEEQUIPE, 'link' => $page_parameters['url']];
                load_view('equipes', $page_parameters);
                exit;

            case 'poules':
                $page_parameters['url'] .= '/poules';
                $page_parameters['url_division'] .= '/poules';
                $page_parameters['breadcrumbs'][] = ['title' => POULES, 'link' => $page_parameters['url']];
                load_view('poules', $page_parameters);
                exit;

            case 'playoffs':
                $page_parameters['url'] .= '/playoffs';
                $page_parameters['url_division'] .= '/playoffs';
                $page_parameters['breadcrumbs'][] = ['title' => 'Playoffs', 'link' => $page_parameters['url']];
                load_view('playoffs', $page_parameters);
                exit;

            case 'classement-final':
                $page_parameters['url'] .= '/classement-final';
                $page_parameters['url_division'] .= '/classement-final';
                $page_parameters['breadcrumbs'][] = ['title' => CLASSEMENTFINAL, 'link' => $page_parameters['url']];
                load_view('classement-final', $page_parameters);
                exit;

            case 'screen-info':
                $page_parameters['url'] .= '/screen-info';
                $page_parameters['url_division'] .= '/screen-info';
                $page_parameters['breadcrumbs'][] = ['title' => 'screenInfo', 'link' => $page_parameters['url']];
                load_view('screen-info', $page_parameters);
                exit;

            case 'screen-diapo':
                $page_parameters['url'] .= '/screen-diapo';
                $page_parameters['url_division'] .= '/screen-diapo';
                $page_parameters['breadcrumbs'][] = ['title' => 'screenDiapo', 'link' => $page_parameters['url']];
                load_view('screen-diapo', $page_parameters);
                exit;

            default:
                //echo "Page inconnue - Langue : $lang, Division : $division, ID de la ressource : $id_resource";
                break;
        }
    }
}
add_action('template_redirect', 'hoplacup_template_redirect');
