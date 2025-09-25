

<?php

require_once '../config.php';
echo 'ZSLUX_ROOTPHP = ' . ZSLUX_ROOTPHP ?><br /><?php
echo 'ZSLUX_ROOT = ' . ZSLUX_ROOT;?><br /><?php
echo 'ZSLUX_DATA = ' . ZSLUX_DATA;?><br /><?php
echo 'ZSLUX_MODULES = ' . ZSLUX_MODULES;?><br /><?php
echo 'ZSLUX_ASSETS = ' . ZSLUX_ASSETS;?><br /><?php
echo 'INCLUDES_PATH = ' . INCLUDES_PATH;?><br /><?php
echo 'CSS_PATH = ' . CSS_PATH;?><br /><?php
echo 'JS_PATH = ' . JS_PATH;?><br /><?php
echo 'HEAD_FILE = ' . HEAD_FILE;?><br /><br/>

<head>
  <meta charset="UTF-8">
  <title><?= $pageTitle ?? 'Titre par défaut' ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="<?= CSS_PATH ?>/main.css" rel="stylesheet">
  <script src="<?= JS_PATH ?>/main.js" defer></script>

</head>
