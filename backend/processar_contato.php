<?php

require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../contato.html');
    exit;
}

try {
    $pdo = conectarBanco();
    
    $stmt = $pdo->prepare("INSERT INTO contatos (nome, email, telefone, mensagem, data_criacao) 
                           VALUES (:nome, :email, :telefone, :mensagem, NOW())");
    
    $stmt->execute([
        ':nome' => sanitize($_POST['nome'] ?? ''),
        ':email' => sanitize($_POST['email'] ?? ''),
        ':telefone' => sanitize($_POST['telefone'] ?? ''),
        ':mensagem' => sanitize($_POST['mensagem'] ?? '')
    ]);
    
    header('Location: ../obrigado.html?tipo=contato');
    exit;
    
} catch (Exception $e) {
    header('Location: ../erro.html?msg=' . urlencode($e->getMessage()));
    exit;
}

