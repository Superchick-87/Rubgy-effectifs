<?php
// Sert à exporter les données affichées dans admin_visites.html
header("Content-Type: text/csv");
header("Content-Disposition: attachment; filename=export.csv");

$type = $_GET['type'] ?? 'jour';
$debut = $_GET['debut'] ?? null;
$fin = $_GET['fin'] ?? null;

// Convertit en timestamps si présents
$startTs = $debut ? strtotime($debut . ' 00:00:00') : null;
$endTs = $fin ? strtotime($fin . ' 23:59:59') : null;

function inDateRange($timestamp, $startTs, $endTs) {
    if ($startTs && $timestamp < $startTs) return false;
    if ($endTs && $timestamp > $endTs) return false;
    return true;
}

if ($type === 'jour') {
    $json = json_decode(file_get_contents('datas/stats_visites.json'), true);
    echo "Date,Visites\n";
    foreach ($json['par_jour'] ?? [] as $date => $count) {
        $ts = strtotime($date);
        if (inDateRange($ts, $startTs, $endTs)) {
            echo "$date,$count\n";
        }
    }

} elseif ($type === 'historique') {
    $json = json_decode(file_get_contents('datas/stats_visites.json'), true);
    echo "Date,IP,Navigateur\n";
    foreach ($json['historique'] ?? [] as $entry) {
        $ts = strtotime($entry['datetime']);
        if (inDateRange($ts, $startTs, $endTs)) {
            $line = sprintf("%s,%s,%s\n",
                $entry['datetime'],
                $entry['ip'],
                str_replace(",", " ", $entry['user_agent']) // éviter les virgules
            );
            echo $line;
        }
    }

} elseif ($type === 'temps') {
    $json = json_decode(file_get_contents('datas/temps_visites.json'), true);
    echo "Date,Durée (secondes),IP\n";
    foreach ($json as $entry) {
        $ts = strtotime($entry['datetime']);
        if (inDateRange($ts, $startTs, $endTs)) {
            $line = sprintf("%s,%d,%s\n",
                $entry['datetime'],
                $entry['duree_secondes'],
                $entry['ip']
            );
            echo $line;
        }
    }

} else {
    echo "Type de données inconnu.";
}
