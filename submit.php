<?php
require_once 'config.php';

$data = $_POST;
$file = RAPPORTS_PATH . '/rapports.json';

// === Ajout des compteurs d'organismes par domaine ===

function ajouterOrganisme($domaine, $organisme) {
  if (empty($domaine) || empty($organisme)) return;

  $fichier = ZSLUX_DATA . '/organismes.json';
  $data = file_exists($fichier) ? json_decode(file_get_contents($fichier), true) : [];

  if (!isset($data[$domaine])) $data[$domaine] = [];
  if (!isset($data[$domaine][$organisme])) {
    $data[$domaine][$organisme] = 1;
  } else {
    $data[$domaine][$organisme]++;
  }

  file_put_contents($fichier, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}

// Charger la liste des documents selon le type
$docsData = json_decode(file_get_contents(ZSLUX_DATA . '/documents.json'), true);
$type = $_POST['type'] ?? 'ONE';
$domaines = $docsData['par_type'][$type] ?? $docsData['documents'] ?? [];

// Mettre à jour les compteurs pour chaque domaine
foreach ($domaines as $domaine) {
  $organisme = $_POST[$domaine . '_organisme'] ?? '';
  ajouterOrganisme($domaine, trim($organisme));
}

// === Enregistrement du rapport ===
$rapports = file_exists($file) ? json_decode(file_get_contents($file), true) : [];
if (isset($_POST['__edit_index']) && is_numeric($_POST['__edit_index'])) {
  $index = (int) $_POST['__edit_index'];
  $rapports[$index] = $data;
} else {
  $rapports[] = $data;
}

file_put_contents($file, json_encode($rapports, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

// 🌶️ DEV: affichage des données reçues
echo '<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">';
echo "<div class='container py-4'>";
echo "<h2 class='mb-3'>Données reçues</h2>";

if (!empty($_POST)) {
  echo "<div class='table-responsive'><table class='table table-bordered table-striped table-sm'>";
  echo "<thead class='table-light'><tr><th>Champ</th><th>Valeur</th></tr></thead><tbody>";

  foreach ($_POST as $key => $value) {
    $val = is_array($value) ? implode(", ", $value) : $value;
    echo "<tr><td>" . htmlspecialchars($key) . "</td><td>" . nl2br(htmlspecialchars($val)) . "</td></tr>";
  }

  echo "</tbody></table></div>";
} else {
  echo "<div class='alert alert-warning'>Aucune donnée reçue.</div>";
}

echo "<p class='mt-3'>✅ Données enregistrées avec succès.</p>";
echo "<a href='index.php' class='btn btn-secondary mt-3'>⬅ Retour au formulaire</a>";
echo "</div>";
?>
