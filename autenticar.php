<?php
session_start();

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: login.php');
    exit();
}

// Sanitiza os dados de entrada
$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';
$remember = isset($_POST['remember']);

// Validação básica
if (empty($username) || empty($password)) {
    $_SESSION['error'] = 'Por favor, preencha todos os campos.';
    header('Location: login.php');
    exit();
}

// Usuários de exemplo (em produção, isso viria do banco de dados)
// Senhas: admin123, teste123, gerente123
$usuarios = [
    'admin' => [
        'id' => 1,
        'username' => 'admin',
        'password' => '$2a$12$pbZZY/Dh5klhX5OEiTpleeF6qj00V1YIU1P1t0OFZP4ik72e7LbUi', // admin123
        'nome' => 'Administrador',
        'email' => 'admin@foodflow.com',
        'tipo_usuario' => 'admin',
        'ativo' => true
    ],
    'teste' => [
        'id' => 2,
        'username' => 'teste',
        'password' => '$2a$12$fDkEjK1lRRAfiwQHDdClge0hfB8LKhzX72cQd8WwK0/..wnm8i6/S', // teste123
        'nome' => 'Usuário Teste',
        'email' => 'teste@foodflow.com',
        'tipo_usuario' => 'funcionario',
        'ativo' => true
    ],
    'gerente' => [
        'id' => 3,
        'username' => 'gerente',
        'password' => '$2a$12$wxcpdnoRJURbWSyfoyTVWeYcRfU1SXX4SzEQ2YQrn/yVlGmk2W3se', // gerente123
        'nome' => 'Gerente',
        'email' => 'gerente@foodflow.com',
        'tipo_usuario' => 'gerente',
        'ativo' => true
    ]
];

// Busca o usuário
$user = null;
foreach ($usuarios as $u) {
    if ($u['username'] === $username || $u['email'] === $username) {
        $user = $u;
        break;
    }
}

// Verifica se o usuário existe
if (!$user) {
    $_SESSION['error'] = 'Usuário ou senha inválidos.';
    header('Location: login.php');
    exit();
}

// Verifica se a conta está ativa
if (!$user['ativo']) {
    $_SESSION['error'] = 'Sua conta está inativa. Entre em contato com o administrador.';
    header('Location: login.php');
    exit();
}

// Verifica a senha
if (!password_verify($password, $user['password'])) {
    $_SESSION['error'] = 'Usuário ou senha inválidos.';
    header('Location: login.php');
    exit();
}

// Regenera o ID da sessão para prevenir session fixation
session_regenerate_id(true);

// Armazena os dados do usuário na sessão
$_SESSION['user_id'] = $user['id'];
$_SESSION['username'] = $user['username'];
$_SESSION['nome'] = $user['nome'];
$_SESSION['email'] = $user['email'];
$_SESSION['tipo_usuario'] = $user['tipo_usuario'];
$_SESSION['logged_in'] = true;
$_SESSION['login_time'] = time();

// Se marcou "Lembrar-me", cria um cookie (opcional)
if ($remember) {
    $token = bin2hex(random_bytes(32));
    $expiry = time() + (30 * 24 * 60 * 60); // 30 dias

    // Define o cookie
    setcookie(
        'remember_token',
        $token,
        $expiry,
        '/',
        '',
        false, // true em produção com HTTPS
        true   // httponly
    );
}

// Redireciona para o dashboard
$_SESSION['success'] = 'Login realizado com sucesso!';
header('Location: dashboard.php');
exit();
?>