<?php


?>

<div id="switch-division">
    <ul>
        <?php if ($division === 'u9') : ?>
            <li class="division-active">
                U9
            </li>
            <li>
                <a href="<?= $public_root_url . ($lang ? '/' . $lang : '') . '/u11' . $url_division ?>">U11</a>
            </li>
            <li>
                <a href="<?= $public_root_url . ($lang ? '/' . $lang : '') . '/u13' . $url_division ?>">U13</a>
            </li>

        <?php elseif ($division === 'u11') : ?>
            <li>
                <a href="<?= $public_root_url . ($lang ? '/' . $lang : '') . '/u9' . $url_division ?>">U9</a>
            </li>
            <li class="division-active">
                U11
            </li>
            <li>
                <a href="<?= $public_root_url . ($lang ? '/' . $lang : '') . '/u13' . $url_division ?>">U13</a>
            </li>
        <?php elseif ($division === 'u13') : ?>
            <li>
                <a href="<?= $public_root_url . ($lang ? '/' . $lang : '') . '/u9' . $url_division ?>">U9</a>
            </li>
            <li>
                <a href="<?= $public_root_url . ($lang ? '/' . $lang : '') . '/u11' . $url_division ?>">U11</a>
            </li>
            <li class="division-active">
                U13
            </li>

        <?php endif; ?>
    </ul>
</div>