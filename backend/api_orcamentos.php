<?php

require_once 'config.php';

header('Content-Type: application/json');

$pdo = conectarBanco();

try {
    $pdo->exec("ALTER TABLE orcamentos ADD COLUMN IF NOT EXISTS aprovado BOOLEAN DEFAULT 0");
} catch (Exception $e) {
}

$orcamentos = $pdo->query("SELECT * FROM orcamentos ORDER BY data_criacao DESC")->fetchAll();
$stats = $pdo->query("SELECT status, COUNT(*) as total FROM orcamentos GROUP BY status")->fetchAll();

foreach ($orcamentos as &$o) {
    $o['data_criacao'] = date('d/m/Y H:i', strtotime($o['data_criacao']));
    $o['aprovado'] = (bool)($o['aprovado'] ?? 0);
}

echo json_encode([
    'total' => count($orcamentos),
    'stats' => $stats,
    'orcamentos' => $orcamentos
]);
?>
