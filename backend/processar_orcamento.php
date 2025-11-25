<?php

require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../orcamentos.html');
    exit;
}

try {
    $pdo = conectarBanco();
    
    $stmt = $pdo->prepare("INSERT INTO orcamentos (nome, email, telefone, servico, mensagem, data_criacao) 
                           VALUES (:nome, :email, :telefone, :servico, :mensagem, NOW())");
    
    $stmt->execute([
        ':nome' => sanitize($_POST['nome'] ?? ''),
        ':email' => sanitize($_POST['email'] ?? ''),
        ':telefone' => sanitize($_POST['telefone'] ?? ''),
        ':servico' => sanitize($_POST['servico'] ?? ''),
        ':mensagem' => sanitize($_POST['mensagem'] ?? '')
    ]);
    
    header('Location: ../obrigado.html?tipo=orcamento');
    exit;
    
} catch (Exception $e) {
    header('Location: ../erro.html?msg=' . urlencode($e->getMessage()));
    exit;
}

