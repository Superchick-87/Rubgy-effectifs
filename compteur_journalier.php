<?php
$file = 'datas/stats_visites.json';
$cookieName = 'visite_effectis';
$expiration = 60 * 60 * 12; // 12h

// Si pas encore visité récemment
$isNewVisit = !isset($_COOKIE[$cookieName]);

if ($isNewVisit) {
  setcookie($cookieName, '1', time() + $expiration);
}

// Initialiser si vide
if (!file_exists($file)) {
  file_put_contents($file, json_encode([
    'total' => 0,
    'par_jour' => [],
    'historique' => [] // ajout
  ]));
}

$data = json_decode(file_get_contents($file), true);

$datetime = date('Y-m-d H:i:s');
$date = date('Y-m-d');

if ($isNewVisit) {
  // Incrémentations
  $data['total']++;
  $data['par_jour'][$date] = ($data['par_jour'][$date] ?? 0) + 1;

  // Ajouter à l'historique
  $data['historique'][] = [
    'datetime' => $datetime,
    'ip' => $_SERVER['REMOTE_ADDR'],
    'user_agent' => $_SERVER['HTTP_USER_AGENT']
  ];

  file_put_contents($file, json_encode($data));
}

// Réponse
header('Content-Type: application/json');
echo json_encode([
  'total' => $data['total'],
  'aujourd_hui' => $data['par_jour'][$date] ?? 0,
  'date' => $date
]);
