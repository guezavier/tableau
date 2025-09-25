<?php
$structureFile = __DIR__ . '/data/structure.json';
$structure = json_decode(file_get_contents($structureFile), true);

// $modules => liste des noms de modules, ex: ["general", "batiment", "chauffage"]
$modules = array_keys($structure);

// Chargement des contenus des fichiers JSON listés dans structure.json
$DATA = [];

foreach ($structure as $id => $info) {
  $source = $info['source'] ?? "$id.json";
  $file = __DIR__ . '/data/' . $source;

  if (file_exists($file)) {
    $json = json_decode(file_get_contents($file), true);
    if ($json !== null) {
      $DATA[$id] = $json;
    }
  }
}

extract($DATA); // crée $general, $batiment, $chauffage, ...
