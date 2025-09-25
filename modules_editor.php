<?php
require_once 'config.php';



$modulesFile = 'config/data/modules.json';
if (!file_exists($modulesFile)) {
  die("<p style='color:red'>Fichier modules.json introuvable à $modulesFile</p>");
}
$content = file_get_contents($modulesFile);
if (!$content) {
  die("<p style='color:red'>Fichier trouvé mais vide ou illisible.</p>");
}
$allModules = json_decode($content, true);
if (!is_array($allModules)) {
  die("<p style='color:red'>Échec de lecture JSON. Erreur : " . json_last_error_msg() . "</p>");
}



$modulesFile = 'config/data/modules.json';
$availableModules = json_decode(file_get_contents('config/data/structure.json'), true);
$moduleKeys = array_keys($availableModules);

$allModules = file_exists($modulesFile)
  ? json_decode(file_get_contents($modulesFile), true)
  : [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (isset($_POST['__add_type'])) {
    $name = trim($_POST['new_type']);
    if ($name && !isset($allModules[$name])) {
      $allModules[$name] = [];
    }
  } elseif (isset($_POST['__delete_type'])) {
    $deleteKey = $_POST['__delete_type'];
    unset($allModules[$deleteKey]);
  } else {
    // Enregistrement standard
    $allModules = [];
    foreach ($_POST['modules'] as $type => $mods) {
      $allModules[$type] = array_values(array_filter($mods));
    }
  }

  file_put_contents($modulesFile, json_encode($allModules, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
  header("Location: modules_editor.php?ok");
  exit;
}
?>

<!DOCTYPE html>
<html lang="fr">

 <!-- ---------- insertion du HEAD depuis le fichier Head.php ---------------------->
  <?php 
   $pageTitle = "Modules";
   include HEAD_FILE; ?>
   
   
<body class="container py-4">
  <h1 class="mb-4">🧩 Configuration des types de rapport</h1>

  <?php if (isset($_GET['ok'])): ?>
    <div class="alert alert-success">✔️ Configuration enregistrée.</div>
  <?php endif; ?>

  <!-- Ajouter un type -->
  <form method="post" class="row g-2 align-items-end mb-5">
    <input type="hidden" name="__add_type" value="1">
    <div class="col-md-6">
      <label class="form-label">Nom du nouveau type de rapport :</label>
      <input type="text" name="new_type" class="form-control" placeholder="ex: scolaire" required>
    </div>
    <div class="col-md-2">
      <button type="submit" class="btn btn-success">➕ Ajouter</button>
    </div>
  </form>

  <!-- Modifier les types -->
  <form method="post">
    <?php foreach ($allModules as $type => $mods): ?>
      <div class="mb-4 border p-3 rounded">
        <div class="d-flex justify-content-between">
          <h5><?= htmlspecialchars($type) ?></h5>
          <button type="submit" name="__delete_type" value="<?= $type ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Supprimer ce type ?')">🗑️ Supprimer</button>
        </div>

        <ul class="list-group" id="list-<?= $type ?>">
          <?php foreach ($mods as $mod): ?>
            <li class="list-group-item">
              <select name="modules[<?= $type ?>][]" class="form-select">
                <?php foreach ($moduleKeys as $key): ?>
                  <option value="<?= $key ?>" <?= $key == $mod ? 'selected' : '' ?>><?= $key ?></option>
                <?php endforeach; ?>
              </select>
              <button type="button" class="btn btn-danger btn-sm btn-remove" onclick="this.parentElement.remove()">×</button>
            </li>
          <?php endforeach; ?>
        </ul>

        <button type="button" class="btn btn-secondary mt-2" onclick="addItem('<?= $type ?>')">+ Ajouter un module</button>
      </div>
    <?php endforeach; ?>

    <button type="submit" class="btn btn-primary">💾 Enregistrer</button>
    
    <!-- Boutons scroll -->
<button id="toTop" class="scroll-btn" title="Haut">↑</button>
<button id="toBottom" class="scroll-btn" title="Bas">↓</button>

    <div class="text-end mt-4">
  <a href="index.php" class="btn btn-outline-primary">🏠 Retour à l’accueil</a>
</div>

  </form>

  <script>

  function addItem(key) {
      const list = document.getElementById('list-' + key);
      const li = document.createElement('li');
      li.className = 'list-group-item';
      li.innerHTML = `
        <select name="modules[${key}][]" class="form-select">
          <?php foreach ($moduleKeys as $key): ?>
            <option value="<?= $key ?>"><?= $key ?></option>
          <?php endforeach; ?>
        </select>
        <button type="button" class="btn btn-danger btn-sm btn-remove" onclick="this.parentElement.remove()">×</button>
      `;
      list.appendChild(li);
    }
    
</script> 
</body>
</html>
