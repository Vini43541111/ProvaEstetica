<?php

require_once 'config.php';

header('Content-Type: application/json');

$pdo = conectarBanco();

try {
    $pdo->exec("ALTER TABLE contatos ADD COLUMN IF NOT EXISTS respondido BOOLEAN DEFAULT 0");
} catch (Exception $e) {
}

$contatos = $pdo->query("SELECT * FROM contatos ORDER BY data_criacao DESC")->fetchAll();
$lidos = $pdo->query("SELECT COUNT(*) FROM contatos WHERE lido = 1")->fetchColumn();

foreach ($contatos as &$c) {
    $c['data_criacao'] = date('d/m/Y H:i', strtotime($c['data_criacao']));
    $c['lido'] = (bool)$c['lido'];
    $c['respondido'] = isset($c['respondido']) ? (bool)$c['respondido'] : false;
}

echo json_encode([
    'total' => count($contatos),
    'lidos' => $lidos,
    'nao_lidos' => count($contatos) - $lidos,
    'contatos' => $contatos
]);
?>
