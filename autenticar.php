<?php
session_start();
require_once __DIR__ . '/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: login.php');
    exit();
}

$email = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';

if ($email === '' || $password === '') {
    $_SESSION['error'] = 'Preencha todos os campos.';
    header('Location: login.php');
    exit();
}

$db = Database::getInstance()->getConnection();
$stmt = $db->prepare('SELECT * FROM users WHERE email = ? LIMIT 1');
$stmt->execute([$email]);
$user = $stmt->fetch();

if ($user && password_verify($password, $user['senha'])) {
    $_SESSION['logged_in'] = true;
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_name'] = $user['nome'] ?? $user['email'];
    $_SESSION['tipo_usuario'] = $user['tipo_usuario'] ?? 'comum';
    if ($_SESSION['tipo_usuario'] === 'admin') {
        header('Location: dashboard.php');
    } else {
        header('Location: cardapio.php');
    }
    exit();
} else {
    $_SESSION['error'] = 'E-mail ou senha incorretos.';
    header('Location: login.php');
    exit();
}

?>
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

// Usuários pré-definidos
$usuarios = [
    'admin' => [
        'id' => 1,
        'username' => 'admin',
        'password' => password_hash('admin123', PASSWORD_DEFAULT),
        'nome' => 'Administrador',
        'email' => 'admin@foodflow.com',
        'tipo_usuario' => 'admin',
        'ativo' => true
    ],
    'Matheus' => [
        'id' => 2,
        'username' => 'Matheus',
        'password' => password_hash('12345', PASSWORD_DEFAULT),
        'nome' => 'Matheus',
        'email' => 'matheus@foodflow.com',
        'tipo_usuario' => 'usuario',
        'ativo' => true
    ],
    'Kaio' => [
        'id' => 3,
        'username' => 'Kaio',
        'password' => password_hash('12345', PASSWORD_DEFAULT),
        'nome' => 'Kaio',
        'email' => 'kaio@foodflow.com',
        'tipo_usuario' => 'usuario',
        'ativo' => true
    ]
];

// Busca o usuário
$user = $usuarios[$username] ?? null;

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

// Verifica a senha (com base nas senhas originais)
if (!password_verify($password, $user['password'])) {
    $_SESSION['error'] = 'Usuário ou senha inválidos.';
    header('Location: login.php');
    exit();
}

// Regenera o ID da sessão
session_regenerate_id(true);

// Define variáveis de sessão
$_SESSION['user_id'] = $user['id'];
$_SESSION['username'] = $user['username'];
$_SESSION['nome'] = $user['nome'];
$_SESSION['email'] = $user['email'];
$_SESSION['tipo_usuario'] = $user['tipo_usuario'];
$_SESSION['logged_in'] = true;
$_SESSION['login_time'] = time();

// Cookie de lembrar-me (opcional)
if ($remember) {
    $token = bin2hex(random_bytes(32));
    setcookie('remember_token', $token, time() + (30 * 24 * 60 * 60), '/', '', false, true);
}

// Redireciona conforme o tipo de usuário
$_SESSION['success'] = 'Login realizado com sucesso!';
if ($user['tipo_usuario'] === 'admin') {
    header('Location: dashboard.php');
} else {
    header('Location: cardapio.php');
}
exit();
?>
