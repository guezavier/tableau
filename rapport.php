<?php
require_once 'config.php';

$rapportFile = RAPPORTS_PATH . '/rapports.json';
$rapports = file_exists($rapportFile) ? json_decode(file_get_contents($rapportFile), true) : [];

// Suppression d’un rapport
if (isset($_GET['delete'])) {
  $index = (int) $_GET['delete'];
  if (isset($rapports[$index])) {
    unset($rapports[$index]);
    file_put_contents($rapportFile, json_encode(array_values($rapports), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    header("Location: rapport.php?ok=1");
    exit;
  }
}
?>

<!DOCTYPE html>
<html lang="fr">
<?php 
  $pageTitle = "Gestion des rapports";
  include HEAD_FILE;
?>
<body class="container py-4">
<h1 class="mb-4">📄 Liste des rapports</h1>

<?php if (isset($_GET['ok'])): ?>
  <div class="alert alert-success">✅ Rapport supprimé avec succès.</div>
<?php endif; ?>

<?php if (empty($rapports)): ?>
  <div class="alert alert-warning">Aucun rapport enregistré.</div>
<?php else: ?>
  <div class="table-responsive">
    <table class="table table-striped table-sm align-middle">
      <thead class="table-light">
        <tr>
          <th>Dossier</th>
          <th>Nom</th>
          <th>Adresse</th>
          <th>Date</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($rapports as $i => $r): ?>
          <tr>
            <td><?= htmlspecialchars($r['dossier'] ?? '—') ?></td>
            <td><?= htmlspecialchars($r['nom'] ?? '') ?></td>
            <td><?= htmlspecialchars($r['adresse'] ?? '') ?></td>
            <td><?= htmlspecialchars($r['date'] ?? '') ?></td>
            <td>
              <a href="formulaire.php?edit=<?= $i ?>" class="btn btn-sm btn-outline-primary">📝 Modifier</a>
              <a href="generate.php?id=<?= $i ?>" class="btn btn-sm btn-outline-success" target="_blank">📄 Word</a>
              <a href="rapport.php?delete=<?= $i ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Supprimer ce rapport ?')">🗑</a>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
<?php endif; ?>

<div class="text-end mt-4">
  <a href="index.php" class="btn btn-outline-secondary">🏠 Retour à l’accueil</a>
</div>
</body>
</html>
