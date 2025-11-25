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
    
    $tipo = $data['tipo'] ?? ''; // 'orcamento' ou 'contato'
    $id = $data['id'] ?? 0;
    
    if (!in_array($tipo, ['orcamento', 'contato']) || $id <= 0) {
        throw new Exception('Parâmetros inválidos');
    }
    
    $pdo = conectarBanco();
    
    if ($tipo === 'orcamento') {
        $pdo->exec("ALTER TABLE orcamentos ADD COLUMN IF NOT EXISTS visto BOOLEAN DEFAULT 0");
        
        $stmt = $pdo->prepare("UPDATE orcamentos SET visto = 1 WHERE id = :id");
        $stmt->execute([':id' => $id]);
        
    } else if ($tipo === 'contato') {
        $stmt = $pdo->prepare("UPDATE contatos SET lido = 1 WHERE id = :id");
        $stmt->execute([':id' => $id]);
    }
    
    echo json_encode([
        'success' => true,
        'message' => ucfirst($tipo) . ' marcado como visto'
    ]);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
