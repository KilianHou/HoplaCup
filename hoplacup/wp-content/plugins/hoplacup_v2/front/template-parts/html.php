<html lang="<?= $lang ?>">


<head>

    <meta charset="UTF-8">
    <meta http-equiv="Content-Language" content="<?= $lang ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="<?= plugin_dir_url(__FILE__) . 'global.css' ?>">

    <?php //wp_head(); 
    ?>

    <?php foreach ($styles_head as $style_href) : ?>
        <link rel="stylesheet" href="<?= $style_href ?>">
    <?php endforeach ?>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Anton&family=PT+Sans:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet">
    <title><?= $page_title ?></title>

</head>


<body>

    <?php require_once plugin_dir_path(__FILE__) . 'header.php'; ?>

    <section id="navigation" class="container">
        <?php require_once plugin_dir_path(__FILE__) . 'breadcrumbs.php'; ?>
    </section>

    <?php require_once($view_template) ?>

    <?php require_once plugin_dir_path(__FILE__) . 'footer.php'; ?>

</body>

</html>