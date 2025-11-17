<?php
require_once __DIR__ . '/database.php';

/**
 * $items => array of ['produto_id' => int, 'quantidade' => int]
 */
function createOrder($user_id, array $items) {
    $dbInstance = Database::getInstance();
    $pdo = $dbInstance->getConnection();

    try {
        $pdo->beginTransaction();

        // calcular total
        $total = 0.0;
        $prodStmt = $pdo->prepare('SELECT id, preco FROM produtos WHERE id = ?');
        foreach ($items as $it) {
            $prodStmt->execute([$it['produto_id']]);
            $p = $prodStmt->fetch();
            if (!$p) {
                throw new Exception("Produto {$it['produto_id']} não encontrado.");
            }
            $q = max(1, (int)$it['quantidade']);
            $total += $p['preco'] * $q;
        }

        // criar pedido
        $stmt = $pdo->prepare('INSERT INTO pedidos (user_id, total, status) VALUES (?, ?, ?)');
        $stmt->execute([$user_id, $total, 'pendente']);
        $pedido_id = $pdo->lastInsertId();

        // inserir items
        $itemStmt = $pdo->prepare('INSERT INTO pedido_items (pedido_id, produto_id, quantidade, preco_unitario) VALUES (?, ?, ?, ?)');
        foreach ($items as $it) {
            $prodStmt->execute([$it['produto_id']]);
            $p = $prodStmt->fetch();
            $q = max(1, (int)$it['quantidade']);
            $itemStmt->execute([$pedido_id, $it['produto_id'], $q, $p['preco']]);
        }

        $pdo->commit();
        return ['success' => true, 'pedido_id' => $pedido_id, 'total' => $total];
    } catch (Exception $e) {
        $pdo->rollBack();
        return ['success' => false, 'message' => $e->getMessage()];
    }
}

function listOrdersByUser($user_id) {
    $db = Database::getInstance()->getConnection();
    $stmt = $db->prepare('SELECT * FROM pedidos WHERE user_id = ? ORDER BY criado_em DESC');
    $stmt->execute([$user_id]);
    return $stmt->fetchAll();
}

function getOrderDetails($pedido_id) {
    $db = Database::getInstance()->getConnection();
    $stmt = $db->prepare('SELECT * FROM pedidos WHERE id = ? LIMIT 1');
    $stmt->execute([$pedido_id]);
    $pedido = $stmt->fetch();
    if (!$pedido) return null;

    $stmt2 = $db->prepare('SELECT pi.*, p.nome FROM pedido_items pi JOIN produtos p ON p.id = pi.produto_id WHERE pi.pedido_id = ?');
    $stmt2->execute([$pedido_id]);
    $items = $stmt2->fetchAll();

    $pedido['items'] = $items;
    return $pedido;
}

// Listar todos os pedidos (para admin)
function listAllOrders() {
    $db = Database::getInstance()->getConnection();
    $stmt = $db->prepare('SELECT p.id, p.user_id, p.total, p.status, p.criado_em, u.nome FROM pedidos p JOIN users u ON u.id = p.user_id ORDER BY p.criado_em DESC');
    $stmt->execute();
    return $stmt->fetchAll();
}

?>
