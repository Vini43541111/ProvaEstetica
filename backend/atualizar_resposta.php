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
    $respondido = $data['respondido'] ?? null;
    
    if ($id <= 0 || $respondido === null) {
        throw new Exception('Parâmetros inválidos');
    }
    
    $pdo = conectarBanco();
    
    $stmt = $pdo->prepare("UPDATE contatos SET respondido = :respondido WHERE id = :id");
    $stmt->execute([
        ':respondido' => $respondido ? 1 : 0,
        ':id' => $id
    ]);
    
    if ($stmt->rowCount() > 0) {
        echo json_encode([
            'success' => true,
            'message' => 'Mensagem marcada como ' . ($respondido ? 'respondida' : 'não respondida') . ' com sucesso',
            'respondido' => (bool)$respondido
        ]);
    } else {
        throw new Exception('Contato não encontrado');
    }
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Erro ao atualizar resposta: ' . $e->getMessage()
    ]);
}
