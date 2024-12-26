<?php

use MustUse\Cloud\Auth\SignIn;

$signin = new SignIn();

$context = $signin->context($data);

$view = $signin->view($context);

$view = __DIR__ . '/views' . '/' . $view . '.php';

if (!file_exists($view)) {
    return;
}

?>


<div useBlockProps>
    <?php include $view; ?>
</div>