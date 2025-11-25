<?php

require_once 'config.php';

header('Content-Type: application/json');

$pdo = conectarBanco();

try {
    $pdo->exec("ALTER TABLE contatos ADD COLUMN IF NOT EXISTS respondido BOOLEAN DEFAULT 0");
} catch (Exception $e) {
}

$contatos = $pdo->query("SELECT * FROM contatos ORDER BY data_criacao DESC")->fetchAll();

foreach ($contatos as &$c) {
    $c['data_criacao'] = date('d/m/Y H:i', strtotime($c['data_criacao']));
    $c['respondido'] = isset($c['respondido']) ? (bool)$c['respondido'] : false;
}

echo json_encode([
    'contatos' => $contatos
]);
?>
