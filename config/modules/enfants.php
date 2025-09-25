<?php
$data = json_decode(file_get_contents(__DIR__ . '/../data/enfants.json'), true);
$questions = $data['questions'] ?? [];

if (!isset($existingData)) {
  global $existingData;
}

$fields = [
  'rdc' => 'Rez-de-chaussée',
  'etage1' => '1er étage'
];
?>

<fieldset class="card p-3 mb-3">
  <legend>Espaces enfants</legend>

  <div class="row">
    <?php foreach ($fields as $name => $label): 
      $checked = ($existingData[$name] ?? '') === 'Oui' ? 'checked' : '';
    ?>
      <div class="col-md-6 form-check mb-2">
        <input type="checkbox" class="form-check-input" id="<?= $name ?>" name="<?= $name ?>" value="Oui" <?= $checked ?>>
        <label class="form-check-label" for="<?= $name ?>">
          <?= htmlspecialchars($label) ?>
        </label>
      </div>
    <?php endforeach; ?>
  </div>
</fieldset>
