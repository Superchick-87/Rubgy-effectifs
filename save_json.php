<?php
$data = file_get_contents("php://input");

if ($data) {
  file_put_contents(__DIR__ . "/datas/players.json", $data);
  http_response_code(200);
  echo "OK";
} else {
  http_response_code(400);
  echo "Aucune donnée reçue";
}
?>
