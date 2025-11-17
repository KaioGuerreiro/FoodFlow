<?php
require_once __DIR__ . '/database.php';

function createProduct($nome, $descricao = '', $preco = 0.0, $categoria = 'prato') {
    $db = Database::getInstance();
    return $db->create('produtos', [
        'nome' => $nome,
        'descricao' => $descricao,
        'preco' => (float)$preco,
        'categoria' => $categoria
    ]);
}

function updateProduct($id, $data = []) {
    $db = Database::getInstance();
    return $db->update('produtos', $data, ['id' => $id]);
}

function deleteProduct($id) {
    $db = Database::getInstance();
    return $db->delete('produtos', ['id' => $id]);
}

function listProducts($categoria = null) {
    $db = Database::getInstance();
    $conds = [];
    if ($categoria !== null) $conds['categoria'] = $categoria;
    return $db->read('produtos', $conds);
}

function getProduct($id) {
    $db = Database::getInstance();
    $res = $db->read('produtos', ['id' => $id], 1);
    if ($res['success'] && $res['count'] > 0) return $res['data'][0];
    return null;
}

?>
