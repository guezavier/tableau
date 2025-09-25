<?php
require_once 'config.php';

// Mode édition
$editIndex = $_GET['edit'] ?? null;
$existingData = null;
if ($editIndex !== null) {
  $file = RAPPORTS_PATH . '/rapports.json';
  $rapports = json_decode(file_get_contents($file), true);
  $existingData = $rapports[$editIndex] ?? null;
}

// Charger les modules en fonction du type sélectionné
$modulesFile = 'config/data/modules.json';
$modules = json_decode(file_get_contents($modulesFile), true);
$types = array_keys($modules);

// Organismes triés dynamiquement par usage
$orgFile = 'config/data/organismes.json';
$orgCounts = file_exists($orgFile) ? json_decode(file_get_contents($orgFile), true) : [];
arsort($orgCounts);
$organismes = array_keys($orgCounts);
?>
<!DOCTYPE html>
<html lang="fr">

 <!-- ---------- insertion du HEAD depuis le fichier Head.php ---------------------->
  <?php 
   $pageTitle = "Formualaire d'encodage";
   include HEAD_FILE; ?>
   <!-- ----------  FIN insertion du HEAD depuis le fichier Head.php ----------------------> 
<body class="container py-4">

  <form method="post" action="submit.php" id="formulaire">
<input type="hidden" name="__edit_index" value="<?= htmlspecialchars($editIndex) ?>">

    <div class="mb-3">
      <label class="form-label">Type de rapport</label>
      <select name="type" class="form-select" onchange="location.href='?type=' + this.value">
        <?php
        //$selectedType = $_GET['type'] ?? $types[0];
        $selectedType = $_GET['type'] ?? ($existingData['type'] ?? $types[0]);

        foreach ($types as $type) {
          echo "<option value=\"$type\"" . ($type === $selectedType ? ' selected' : '') . ">$type</option>";
        }
        ?>
      </select>
    </div>

    <?php
        $selectedModules = $modules[$selectedType] ?? [];

        // Forcer 'general' au début et 'remarques' à la fin
        $allModules = array_merge(
          ['general'],
          array_diff($selectedModules, ['general', 'remarques']),
          ['remarques']
        );

        foreach ($allModules as $module) {
          $file = "config/modules/$module.php";
          if (file_exists($file)) {
            include $file;
          } else {
            echo "<p class='text-danger'>Module manquant : $module</p>";
          }
        }

    ?>

    <div class="mt-4">
      <button type="submit" class="btn btn-success">💾 Enregistrer</button>
      <button type="reset" class="btn btn-secondary">Vider</button>
    </div>

  </form>

<button id="toTop" class="top-btn">↑ Haut</button>
<button id="toBottom" class="bottom-btn">↓ Bas</button>

    <div class="text-end mt-4">
  <a href="index.php" class="btn btn-outline-primary">🏠 Retour à l’accueil</a>
</div>

</body>
</html>
