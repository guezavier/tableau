<?php
$chauffageFile = ZSLUX_DATA . '/chauffage.json';
$data = json_decode(file_get_contents($chauffageFile), true);
$criteres = $data['chauffage'] ?? [];

// Rendre les données de l’édition accessibles
if (!isset($existingData)) {
  global $existingData;
}
?>

<fieldset class="card p-3 mb-3">
  <legend>Moyens de chauffage</legend>

  <div class="row">
    <?php foreach ($criteres as $index => $label): 
      $name = "chauffage_$index";
      $checked = ($existingData[$name] ?? '') === 'Oui' ? 'checked' : '';
    ?>
      <div class="col-md-6 form-check mb-2">
        <input type="checkbox" class="form-check-input" id="<?= $name ?>" name="<?= $name ?>" value="Oui" <?= $checked ?>>
        <label class="form-check-label" for="<?= $name ?>"><?= htmlspecialchars($label) ?></label>
      </div>
    <?php endforeach; ?>
  </div>

  <?php $protection = $existingData['protection_bruleures'] ?? ''; ?>
  <div class="mt-3">
    <label class="form-label">Protection contre brûlures :</label>
    <input type="text" name="protection_bruleures" class="form-control"
           placeholder="Ex : cache tuyau, garde-corps..."
           value="<?= htmlspecialchars($protection) ?>">
  </div>
</fieldset>
