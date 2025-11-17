<?php
// Script rápido para criar um usuário admin (execute uma vez pela web ou CLI)
require_once __DIR__ . '/database.php';

$nome = 'Admin';
$email = 'admin@foodflow.local';
$senha = 'admin123';
$hash = password_hash($senha, PASSWORD_DEFAULT);

$res = Database::getInstance()->create('users', [
    'nome' => $nome,
    'email' => $email,
    'senha' => $hash,
    'tipo_usuario' => 'admin'
]);

if ($res['success']) {
    echo "✅ Admin criado com sucesso! ID: " . $res['id'];
} else {
    echo "❌ Erro: " . $res['message'];
}

?>

