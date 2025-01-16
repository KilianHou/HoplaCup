<html lang="fr">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">


    <?php //wp_head(); 
    ?>

    <?php foreach ($styles_head as $style_href) : ?>
        <link rel="stylesheet" href="<?= $style_href ?>">
    <?php endforeach ?>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Anton&family=PT+Sans:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet">

    <title><?= $page_title ?></title>

</head>


<body>

    <?php require_once($view_template) ?>

</body>

</html>