<?php
// Chemin système pour les inclusions PHP
define('ZSLUX_ROOTPHP', dirname(__DIR__));

// Chemin URL pour le navigateur (à adapter)
define('ZSLUX_URL', '/ZS');

// Chemins système (PHP)
define('ZSLUX_DATA', ZSLUX_ROOTPHP . '/ZS/config/data');
define('ZSLUX_MODULES', ZSLUX_ROOTPHP . '/ZS/config/modules');
define('ZSLUX_ASSETS', ZSLUX_ROOTPHP . '/ZS/assets');
define('INCLUDES_PATH', ZSLUX_ROOTPHP . '/ZS/includes');
define('RAPPORTS_PATH', ZSLUX_ROOTPHP . '/ZS/data');

// Chemins URL (HTML/CSS/JS)
define('CSS_PATH', ZSLUX_URL . '/assets/css');
define('JS_PATH', ZSLUX_URL . '/assets/js');

// Fichier head.php (système)
define('HEAD_FILE', INCLUDES_PATH . '/head.php');

// Charge dynamiquement un fichier JSON
function load_json($filename) {
  $path = ZSLUX_DATA . '/' . $filename;
  if (!file_exists($path)) return [];
  $data = json_decode(file_get_contents($path), true);
  return json_last_error() === JSON_ERROR_NONE ? $data : [];
}

// Type de rapport
$type = $_GET['type'] ?? 'ONE';

// Chargement des modules à afficher
$modules_config = load_json('modules.json');
$modules = $modules_config[$type] ?? [];

// Fonction utilitaire pour charger un module PHP
function include_module($module) {
  $path = ZSLUX_MODULES . '/' . $module . '.php';
  if (file_exists($path)) include $path;
  else echo "<div class='alert alert-danger'>Module manquant : $module</div>";
}

// Fonction utilitaire pour charger les données JSON d’un module
function load_module_data($module) {
  return load_json($module . '.json');
}