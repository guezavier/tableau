<?php
$piecesFile = __DIR__ . '/../data/pieces.json';
$pieces = json_decode(file_get_contents($piecesFile), true)['pieces'] ?? [];
?>

<fieldset class="card p-3 mb-3">
  <legend>Détecteurs d’incendie</legend>

  <?php foreach ($pieces as $piece): ?>
    <div class="mb-2">
      <label><?= ucfirst(str_replace('_', ' ', $piece)) ?> :
        <select name="detecteur_<?= $piece ?>" class="form-select d-inline w-auto ms-2">
          <option value="" selected>—</option>
          <option value="Oui">Oui</option>
          <option value="Non">Non</option>
        </select>
      </label>
    </div>
  <?php endforeach; ?>

  <div class="mt-3">
    <label>Conforme (CE / EN 14604 / BOSEC) :
      <select name="conforme" class="form-select d-inline w-auto ms-2">
          <option value="" selected>—</option>
          <option value="Oui">Oui</option>
          <option value="Non">Non</option>
      </select>
    </label>
  </div>

  <div class="mt-2">
    <label>Bon état :
      <select name="etat" class="form-select d-inline w-auto ms-2">
           <option value="" selected>—</option>
          <option value="Oui">Oui</option>
          <option value="Non">Non</option>
      </select>
    </label>
  </div>

  <div class="mt-2">
    <label>Remarques détecteurs :
      <input type="text" name="remarques_detecteurs" class="form-control" placeholder="Remarque éventuelle">
    </label>
  </div>
</fieldset>
