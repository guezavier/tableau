<?php
require_once 'config.php';
require_once __DIR__ . '/vendor/autoload.php';
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;

// Chargement des données nécessaires
$rapports = json_decode(file_get_contents(RAPPORTS_PATH . '/rapports.json'), true);
$modules = json_decode(file_get_contents(ZSLUX_DATA . '/modules.json'), true);
$documentsData = json_decode(file_get_contents(ZSLUX_DATA . '/documents.json'), true);
$allDocs = $documentsData['documents'] ?? [];

// Fonction pour charger les libellés des modules
function getLabels($module) {
    $fichier = ZSLUX_DATA . "/$module.json";
    if (!file_exists($fichier)) return [];
    $data = json_decode(file_get_contents($fichier), true);
    return $data[$module] ?? $data['questions'] ?? []; // cas "enfants"
}

// Rapport ciblé
$id = $_GET['id'] ?? null;
if ($id === null || !isset($rapports[$id])) {
    exit("Rapport introuvable.");
}
$rapport = $rapports[$id];
$type = $rapport['type'] ?? '';
$sections = $modules[$type] ?? [];

$phpWord = new PhpWord();
$section = $phpWord->addSection();

// En-tête
$section->addTitle('RAPPORT DE PRÉVENTION INCENDIE', 1);
$section->addText("Dossier : " . ($rapport['dossier'] ?? ''));
$section->addText("Nom : " . ($rapport['nom'] ?? ''));
$section->addText("Adresse : " . ($rapport['adresse'] ?? ''));
$section->addText("Date : " . ($rapport['date'] ?? ''));
$section->addTextBreak(1);

// Modules
foreach ($sections as $bloc) {
    $section->addTitle(strtoupper($bloc), 2);
    $hasContent = false;

    // --- Traitement spécial pour "documents"
    if ($bloc === 'documents') {
        $docsForType = $documentsData['par_type'][$type] ?? $allDocs;

        // Création du tableau
        $table = $section->addTable(['borderSize' => 6, 'cellMargin' => 80]);
        $table->addRow();
        $table->addCell(3000)->addText("Document", ['bold' => true]);
        $table->addCell(2000)->addText("Date", ['bold' => true]);
        $table->addCell(2000)->addText("Résultat", ['bold' => true]);
        $table->addCell(3000)->addText("Organisme", ['bold' => true]);

        foreach ($docsForType as $doc) {
            $date = $rapport["{$doc}_date"] ?? '';
            $res = $rapport["{$doc}_result"] ?? '';
            $org = $rapport["{$doc}_organisme"] ?? '';

            if ($date || $res || $org) {
                $formattedDate = $date && strtotime($date) ? date("d/m/Y", strtotime($date)) : '';
                $table->addRow();
                $table->addCell(3000)->addText(ucfirst($doc));
                $table->addCell(2000)->addText($formattedDate);
                $table->addCell(2000)->addText($res);
                $table->addCell(3000)->addText($org);
                $hasContent = true;
            }
        }

        if (!$hasContent) {
            $section->addText("(Aucun document encodé)");
        }

        $section->addTextBreak();

    // --- Traitement spécial pour "detecteurs"
} elseif ($bloc === 'detecteurs') {
    $hasContent = false;

    $type = $rapport['type_detection'] ?? '';
    $section->addText("Type de détection : " . ucfirst($type));
    $section->addTextBreak(0.5);

    if ($type === 'autonome') {
        $piecesData = json_decode(file_get_contents(ZSLUX_DATA . '/pieces.json'), true)['pieces'] ?? [];

        foreach ($piecesData as $piece) {
            $cle = "detecteur_$piece";
            $val = $rapport[$cle] ?? '';
            if ($val !== '') {
                $cleLisible = ucfirst(str_replace('_', ' ', $piece));
                $section->addText("- $cleLisible : $val");
                $hasContent = true;
            }
        }

        $conf = $rapport['detection_autonome_conforme'] ?? '';
        $func = $rapport['detection_autonome_fonctionnement'] ?? '';
        if ($conf || $func) {
            $section->addText("- Conforme : $conf");
            $section->addText("- Bon fonctionnement : $func");
            $hasContent = true;
        }

    } elseif ($type === 'centralisee') {
        $conf = $rapport['detection_conforme'] ?? '';
        $date = $rapport['detection_norme_date'] ?? '';
        $func = $rapport['detection_centrale_fonctionnement'] ?? '';
        $conf2 = $rapport['detection_centrale_conforme'] ?? '';

        if ($conf) {
            $section->addText("- Norme : $conf" . ($date ? " ($date)" : ''));
            $hasContent = true;
        }

        if ($conf2) {
            $section->addText("- Conforme : $conf2");
            $hasContent = true;
        }

        if ($func) {
            $section->addText("- Bon fonctionnement : $func");
            $hasContent = true;
        }
    }

    // Remarques générales
    $remarque = $rapport['remarques_detecteurs'] ?? '';
    if ($remarque !== '') {
        $section->addText("- Remarque : $remarque");
        $hasContent = true;
    }

    if (!$hasContent) {
        $section->addText("(Aucune information encodée)");
    }

    $section->addTextBreak();


    // --- Traitement standard
    } else {
        $labels = getLabels($bloc);

        foreach ($rapport as $key => $val) {
            if (strpos($key, $bloc . '_') === 0 && $val !== '') {
                $cleBrute = str_replace($bloc . '_', '', $key);
                $cleLisible = is_numeric($cleBrute) && isset($labels[$cleBrute])
                    ? $labels[$cleBrute]
                    : ucfirst(str_replace('_', ' ', $cleBrute));
                $section->addText("- $cleLisible : $val");
                $hasContent = true;
            }
        }

        if (!$hasContent) {
            $section->addText("(Aucune information encodée)");
        }

        $section->addTextBreak();
    }
}

// Remarques générales
$section->addTitle("REMARQUES", 2);
$remarques = array_filter((array)($rapport['remarques'] ?? []));
if ($remarques) {
    foreach ($remarques as $r) {
        $section->addText("- $r");
    }
} else {
    $section->addText("(aucune)");
}

// Export Word
$filename = 'Rapport_' . ($rapport['dossier'] ?? 'sans_ref') . '.docx';
$outputPath = 'exports/' . $filename;
$writer = IOFactory::createWriter($phpWord, 'Word2007');
$writer->save($outputPath);

header('Content-Description: File Transfer');
header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
header("Content-Disposition: attachment; filename=\"$filename\"");
header('Content-Transfer-Encoding: binary');
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');
header('Content-Length: ' . filesize($outputPath));

readfile($outputPath);
exit;
