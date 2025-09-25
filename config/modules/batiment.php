<?php
$batimentFile = ZSLUX_DATA . '/batiment.json';
$data  = json_decode(file_get_contents($batimentFile), true);
$criteres = $data['batiment'] ?? [];

if (!isset($existingData)) {
  global $existingData;
}

// Charger les éventuelles autres caractéristiques libres
$autres = $existingData['autre_batiment'] ?? [''];
if (empty($autres)) $autres = [''];
?>

<fieldset class="card p-3 mb-3">
  <legend>Caractéristiques du bâtiment</legend>

  <?php foreach ($criteres as $index => $label): 
    $name = "batiment_$index";
    $checked = ($existingData[$name] ?? '') === "Oui" ? 'checked' : '';
  ?>
    <div class="form-check">
      <input type="checkbox" class="form-check-input" id="<?= $name ?>" name="<?= $name ?>" value="Oui" <?= $checked ?>>
      <label class="form-check-label" for="<?= $name ?>"><?= htmlspecialchars($label) ?></label>
    </div>
  <?php endforeach; ?>

  <hr class="my-3">

  <div id="autres-container">
    <label class="form-label">Autres caractéristiques (libres)</label>

    <?php foreach ($autres as $i => $val): ?>
      <div class="autre-item mb-2">
        <input type="text" name="autre_batiment[]" class="form-control autre-batiment-field"
               placeholder="Ex : Bâtiment mitoyen, structure bois, etc."
               value="<?= htmlspecialchars($val) ?>">
      </div>
    <?php endforeach; ?>
  </div>
</fieldset>


