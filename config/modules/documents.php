<?php
$documentsFile = __DIR__ . '/../data/documents.json';
$organismesFile = __DIR__ . '/../data/organismes.json';

$docsData = json_decode(file_get_contents($documentsFile), true);
$organismesData = file_exists($organismesFile) ? json_decode(file_get_contents($organismesFile), true) : [];

$allDocs = $docsData['documents'] ?? [];
$docsForType = $docsData['par_type'][$type ?? 'ONE'] ?? $allDocs;

// S’assurer d’avoir accès aux données du rapport si en mode édition
if (!isset($existingData)) {
  global $existingData;
}
?>

<fieldset class="card p-3 mb-3">
  <legend>Documents de contrôle</legend>

  <div class="row g-3">
    <?php foreach ($docsForType as $doc): ?>
      <?php
        $dateValue = $existingData[$doc . '_date'] ?? '';
        $resultValue = $existingData[$doc . '_result'] ?? '';
        $orgValue = $existingData[$doc . '_organisme'] ?? '';

        $orgForDoc = $organismesData[$doc] ?? [];
        arsort($orgForDoc);
        $orgNames = array_keys($orgForDoc);
      ?>
      <div class="col-md-4">
        <label class="form-label"><?= ucfirst($doc) ?> — Date</label>
        <input type="date" name="<?= $doc ?>_date" class="form-control"
               value="<?= htmlspecialchars($dateValue) ?>">
      </div>
      <div class="col-md-4">
        <label class="form-label">Résultat</label>
        <select name="<?= $doc ?>_result" class="form-select">
          <option value="" <?= $resultValue === '' ? 'selected' : '' ?>>—</option>
          <option value="Oui" <?= $resultValue === 'Oui' ? 'selected' : '' ?>>Oui</option>
          <option value="Non" <?= $resultValue === 'Non' ? 'selected' : '' ?>>Non</option>
        </select>
      </div>
      <div class="col-md-4">
        <label class="form-label">Organisme</label>
        <select name="<?= $doc ?>_organisme" class="form-select">
          <option value="">—</option>
          <?php foreach ($orgNames as $orgName): ?>
            <option value="<?= htmlspecialchars($orgName) ?>"
              <?= $orgValue === $orgName ? 'selected' : '' ?>>
              <?= htmlspecialchars($orgName) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>
    <?php endforeach; ?>
  </div>
</fieldset>
