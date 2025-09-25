<fieldset class="card p-3 mb-3">
  <legend>Informations générales</legend>

  <label>Dossier :
    <input id="dossier" type="text" name="dossier" class="form-control"
           maxlength="10" placeholder="PRxx-xxxxx"
           value="<?= htmlspecialchars($existingData['dossier'] ?? '') ?>"
           oninput="formatCode(this)" required>
  </label><br>

  <label>Nom :
    <input type="text" name="nom" class="form-control"
           value="<?= htmlspecialchars($existingData['nom'] ?? '') ?>"
           required>
  </label><br>

  <label>Adresse :
    <input type="text" name="adresse" class="form-control"
           value="<?= htmlspecialchars($existingData['adresse'] ?? '') ?>"
           required>
  </label><br>

  <label class="form-label">Téléphone fixe : </label>
  <input type="tel" name="tel" class="form-control phone-format"
         placeholder="Ex : 061/21.34.09"
         value="<?= htmlspecialchars($existingData['tel'] ?? '') ?>">

  <label class="form-label">GSM : </label>
  <input type="tel" name="gsm" class="form-control phone-format"
         placeholder="Ex : 0499.12.34.56"
         value="<?= htmlspecialchars($existingData['gsm'] ?? '') ?>">

  <label>Date de visite :
    <input type="date" name="date" class="form-control"
           value="<?= htmlspecialchars($existingData['date'] ?? '') ?>"
           required>
  </label>
</fieldset>
