<?php

require_once 'config.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Método não permitido']);
    exit;
}

try {
    $data = json_decode(file_get_contents('php://input'), true);
    
    $id = $data['id'] ?? 0;
    $aprovado = $data['aprovado'] ?? null;
    
    if ($id <= 0 || $aprovado === null) {
        throw new Exception('Parâmetros inválidos');
    }
    
    $pdo = conectarBanco();
    
    $stmt = $pdo->prepare("UPDATE orcamentos SET aprovado = :aprovado WHERE id = :id");
    $stmt->execute([
        ':aprovado' => $aprovado ? 1 : 0,
        ':id' => $id
    ]);
    
    if ($stmt->rowCount() > 0) {
        echo json_encode([
            'success' => true,
            'message' => 'Orçamento ' . ($aprovado ? 'aprovado' : 'não aprovado') . ' com sucesso',
            'aprovado' => (bool)$aprovado
        ]);
    } else {
        throw new Exception('Orçamento não encontrado');
    }
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
