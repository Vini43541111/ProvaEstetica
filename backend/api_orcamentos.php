<?php

require_once 'config.php';

header('Content-Type: application/json');

$pdo = conectarBanco();

try {
    $pdo->exec("ALTER TABLE orcamentos ADD COLUMN IF NOT EXISTS aprovado BOOLEAN DEFAULT 0");
} catch (Exception $e) {
}

$orcamentos = $pdo->query("SELECT * FROM orcamentos ORDER BY data_criacao DESC")->fetchAll();

foreach ($orcamentos as &$o) {
    $o['data_criacao'] = date('d/m/Y H:i', strtotime($o['data_criacao']));
    $o['aprovado'] = (bool)($o['aprovado'] ?? 0);
}

echo json_encode([
    'orcamentos' => $orcamentos
]);
?>
