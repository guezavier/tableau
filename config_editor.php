<?php
require_once 'config.php';

$structureFile = 'config/data/structure.json';
$structure = json_decode(file_get_contents($structureFile), true);

// ➕ AJOUT D'UN MODULE
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['__add_module'])) {
  $id = trim($_POST['id']);
  $label = trim($_POST['label']) ?: ucfirst($id);
  $type = trim($_POST['type']) ?: 'fieldset';
  $source = trim($_POST['source']) ?: "$id.json";

  if ($id && !isset($structure[$id])) {
    $structure[$id] = [
      'label' => $label,
      'type' => $type,
      'source' => $source
    ];
    file_put_contents($structureFile, json_encode($structure, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

    $targetPath = 'config/data/' . $source;
    if (!file_exists($targetPath)) {
      file_put_contents($targetPath, json_encode([$id => []], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }

    header("Location: config_editor.php?ok=1");
    exit;
  }
}

// 💾 ENREGISTREMENT DES DONNÉES DE CHAQUE MODULE
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['__add_module'])) {
  foreach ($_POST as $key => $values) {
    if (!isset($structure[$key])) continue;
    $source = 'config/data/' . $structure[$key]['source'];
    $mainKey = pathinfo($source, PATHINFO_FILENAME);

    // Cas particulier : organismes => tableau associatif { domaine: { organisme: compteur } }
    if ($key === 'organismes') {
      $formatted = [];
      foreach ($values as $domaine => $liste) {
        foreach ($liste as $org) {
          $org = trim($org);
          if ($org !== '') {
            if (!isset($formatted[$domaine][$org])) {
              $formatted[$domaine][$org] = 1;
            } else {
              $formatted[$domaine][$org]++;
            }
          }
        }
      }
      file_put_contents($source, json_encode($formatted, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    } else {
      $content = [ $mainKey => array_values(array_filter($values)) ];
      file_put_contents($source, json_encode($content, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }
  }

  header("Location: config_editor.php?ok=1");
  exit;
}

?>

<!DOCTYPE html>
<html lang="fr">
 <!-- ---------- insertion du HEAD depuis le fichier Head.php ---------------------->
  <?php 
   $pageTitle = "Éditeur de configuration";
   include HEAD_FILE; ?>
   
   
<body class="container py-4">
  <h1 class="mb-4">🛠️ Éditeur de configuration dynamique</h1>

  <?php if (isset($_GET['ok'])): ?>
    <div class="alert alert-success">✔️ Modifications enregistrées.</div>
  <?php endif; ?>

  <h2 class="mt-4 mb-3">➕ Ajouter un nouveau module</h2>
  <form method="post" class="row g-2 align-items-end mb-5">
    <input type="hidden" name="__add_module" value="1">

    <div class="col-md-3">
      <label class="form-label">Nom interne *</label>
      <input type="text" name="id" class="form-control" placeholder="ex: securite" required>
    </div>
    <div class="col-md-3">
      <label class="form-label">Label (facultatif)</label>
      <input type="text" name="label" class="form-control" placeholder="ex: Sécurité incendie">
    </div>
    <div class="col-md-2">
      <label class="form-label">Type</label>
      <select name="type" class="form-select">
        <option value="fieldset">fieldset</option>
        <option value="liste">liste</option>
      </select>
    </div>
    <div class="col-md-3">
      <label class="form-label">Nom du fichier (facultatif)</label>
      <input type="text" name="source" class="form-control" placeholder="ex: securite.json">
    </div>
    <div class="col-md-1">
      <button type="submit" class="btn btn-success w-100">Ajouter</button>
    </div>
  </form>

  <form method="post">
<?php foreach ($structure as $key => $info): 
  $file = "config/data/" . $info['source'];
  $data = file_exists($file) ? json_decode(file_get_contents($file), true) : [];
  $mainKey = array_keys($data)[0] ?? $key;

  // === CAS PARTICULIER : module "organismes"
  if ($key === 'organismes') {
    $domaines = array_keys($data); ?>

    <div class="mb-4">
      <h5><?= $info['label'] ?> (organismes par domaine)</h5>
      <?php foreach ($domaines as $domaine): 
        $liste = array_keys($data[$domaine]); ?>
        <div class="mb-3">
          <label class="form-label"><?= ucfirst($domaine) ?></label>
          <ul class="list-group" id="list-<?= $key ?>-<?= $domaine ?>">
            <?php foreach ($liste as $item): ?>
              <li class="list-group-item">
                <input type="text" name="<?= $key ?>[<?= $domaine ?>][]" class="form-control" value="<?= htmlspecialchars($item) ?>">
                <button type="button" class="btn btn-danger btn-sm" onclick="this.parentElement.remove()">×</button>
              </li>
            <?php endforeach; ?>
          </ul>
          <button type="button" class="btn btn-secondary mt-2" onclick="addItem('<?= $key ?>-<?= $domaine ?>', '<?= $key ?>[<?= $domaine ?>][]')">+ Ajouter</button>
        </div>
      <?php endforeach; ?>
    </div>

  <?php
  // === CAS STANDARD
  } else {
    $liste = array_values($data[$mainKey] ?? []); ?>
    <div class="mb-4">
      <h5><?= $info['label'] ?></h5>
      <ul class="list-group" id="list-<?= $key ?>">
        <?php foreach ($liste as $item): ?>
          <li class="list-group-item">
            <input type="text" name="<?= $key ?>[]" class="form-control" value="<?= htmlspecialchars($item) ?>">
            <button type="button" class="btn btn-danger btn-sm" onclick="this.parentElement.remove()">×</button>
          </li>
        <?php endforeach; ?>
      </ul>
      <button type="button" class="btn btn-secondary mt-2" onclick="addItem('<?= $key ?>', '<?= $key ?>[]')">+ Ajouter</button>
    </div>
  <?php } ?>
<?php endforeach; ?>


    <button type="submit" class="btn btn-primary">💾 Enregistrer toutes les listes</button>
  </form>
<!-- Boutons scroll -->
<button id="toTop" class="scroll-btn" title="Haut">↑</button>
<button id="toBottom" class="scroll-btn" title="Bas">↓</button>

<div class="text-end mt-4">
  <a href="index.php" class="btn btn-outline-primary">🏠 Retour à l’accueil</a>
</div>
<script>
function addItem(listId, inputName = null) {
  const ul = document.getElementById('list-' + listId);
  const li = document.createElement('li');
  li.className = 'list-group-item';
  const nameAttr = inputName || listId + '[]';
  li.innerHTML = `
    <input type="text" name="${nameAttr}" class="form-control">
    <button type="button" class="btn btn-danger btn-sm" onclick="this.parentElement.remove()">×</button>
  `;
  ul.appendChild(li);
}
</script>




</body>
</html>
