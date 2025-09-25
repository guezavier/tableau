<?php
$data = json_decode(file_get_contents(__DIR__ . '/../data/cuisine.json'), true);
$cuisine = $data['cuisine'] ?? [];

if (!isset($existingData)) {
  global $existingData;
}
?>

<fieldset class="card p-3 mb-3">
  <legend>Cuisine</legend>

  <div class="row">
    <?php foreach ($cuisine as $nom): 
      $checked = ($existingData[$nom] ?? '') === 'Oui' ? 'checked' : '';
    ?>
      <div class="col-md-6 form-check mb-2">
        <input type="checkbox" class="form-check-input" id="<?= $nom ?>" name="<?= $nom ?>" value="Oui" <?= $checked ?>>
        <label class="form-check-label" for="<?= $nom ?>">
          <?= ucfirst(str_replace('_', ' ', $nom)) ?>
        </label>
      </div>
    <?php endforeach; ?>
  </div>

  <?php $remarqueCuisine = $existingData['remarques_cuisine'] ?? ''; ?>
  <div class="mt-3">
    <label class="form-label">Remarques cuisine :</label>
    <input type="text" name="remarques_cuisine" class="form-control"
           placeholder="Remarque éventuelle"
           value="<?= htmlspecialchars($remarqueCuisine) ?>">
  </div>
</fieldset>
