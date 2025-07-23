<?php
// Récupère les données JSON envoyées via la méthode POST (par fetch() ou navigator.sendBeacon)
$data = json_decode(file_get_contents('php://input'), true);

// Si aucune donnée ou si les champs attendus ne sont pas présents, on arrête l'exécution
if (!$data || !isset($data['datetime']) || !isset($data['duree_secondes'])) exit;

// Conversion UTC → Heure locale (Europe/Paris)
$dateUtc = new DateTime($data['datetime'], new DateTimeZone('UTC'));
$dateUtc->setTimezone(new DateTimeZone('Europe/Paris'));
$datetimeLocal = $dateUtc->format('Y-m-d H:i:s');

// Nom du fichier où stocker les données de temps de visite
$filename = 'datas/temps_visites.json';

// Si le fichier existe, on le lit et on le décode en tableau PHP, sinon on part d'un tableau vide
$entries = file_exists($filename) ? json_decode(file_get_contents($filename), true) : [];

// On ajoute une nouvelle entrée
$entries[] = [
  'datetime' => $datetimeLocal, // heure locale désormais
  'duree_secondes' => $data['duree_secondes'],
  'ip' => $_SERVER['REMOTE_ADDR'] ?? 'inconnu'
];

// On enregistre le tableau mis à jour dans le fichier JSON, avec un format lisible
file_put_contents($filename, json_encode($entries, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
?>


