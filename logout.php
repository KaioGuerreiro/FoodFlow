<?php
session_start();

// Limpa todas as variáveis de sessão
$_SESSION = array();

// Remove o cookie de sessão
if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time() - 42000, '/');
}

// Remove o cookie "Lembrar-me" se existir
if (isset($_COOKIE['remember_token'])) {
    setcookie('remember_token', '', time() - 42000, '/');
}

// Destrói a sessão
session_destroy();

// Redireciona para a página de login
header('Location: login.php');
exit();
?>