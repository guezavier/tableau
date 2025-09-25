<!DOCTYPE html>
<?php
require_once 'config.php';

?>


<html lang="fr">

 <!-- ---------- insertion du HEAD depuis le fichier Head.php ---------------------->
  <?php 
   $pageTitle = "Accueil - Prévention - Visites";
   include HEAD_FILE; ?>
   
   
<body class="container py-5">

  <h1 class="mb-4 text-center">🗂️ Gestion des visites</h1>

  <div class="d-grid gap-3 col-6 mx-auto">
    <a href="formulaire.php" class="btn btn-info btn-lg">➕ Remplir un nouveau formulaire</a>
    <a href="rapport.php" class="btn btn-info btn-lg">📄 Gérer les rapports</a>
    <a href="config_editor.php" class="btn btn-warning btn-lg">⚙️ Modifier les données des listes (types, pièces, organismes...)</a>
    <a href="modules_editor.php" class="btn btn-warning btn-lg">🛠️ Modifier les modules disponibles (chauffage, documents...)</a>
  </div>

</body>
</html>
