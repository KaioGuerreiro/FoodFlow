<?php
session_start();
require_once __DIR__ . '/database.php';

// Se já estiver logado, redireciona conforme tipo
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    if (($_SESSION['tipo_usuario'] ?? '') === 'admin') {
        header('Location: dashboard.php');
    } else {
        header('Location: cardapio.php');
    }
    exit();
}

$sucesso = false;
$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';
    $confirmar = $_POST['confirmar'] ?? '';

    if ($nome === '' || $email === '' || $senha === '' || $confirmar === '') {
        $erro = 'Preencha todos os campos.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erro = 'E-mail inválido.';
    } elseif (strlen($senha) < 3) {
        $erro = 'A senha deve ter pelo menos 3 caracteres.';
    } elseif ($senha !== $confirmar) {
        $erro = 'As senhas não coincidem.';
    } else {
        $db = Database::getInstance()->getConnection();
        // checa se e-mail já cadastrado
        $stmt = $db->prepare('SELECT id FROM users WHERE email = ? LIMIT 1');
        $stmt->execute([$email]);
        $exists = $stmt->fetch();

        if ($exists) {
            $erro = 'E-mail já cadastrado.';
        } else {
            $hash = password_hash($senha, PASSWORD_DEFAULT);
            $res = Database::getInstance()->create('users', [
                'nome' => $nome,
                'email' => $email,
                'senha' => $hash,
                'tipo_usuario' => 'comum'
            ]);

            if ($res['success']) {
                $sucesso = true;
            } else {
                $erro = 'Erro ao cadastrar: ' . ($res['message'] ?? 'Unknown');
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FoodFlow - Cadastro</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .error-message {
            background: #ffecec;
            color: #b00020;
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 12px;
            text-align: center;
            font-weight: 600;
        }
        .success-message {
            background: #e6ffed;
            color: #0c8a1f;
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 12px;
            text-align: center;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <h1 class="brand">🍔 FoodFlow</h1>
                <p class="subtitle">Crie sua conta para começar</p>
            </div>

            <?php if ($erro): ?>
                <div class="error-message"><?= htmlspecialchars($erro) ?></div>
            <?php elseif ($sucesso): ?>
                <div class="success-message">
                    Cadastro realizado com sucesso! 🎉<br>
                    <a href="login.php" style="color:#667eea; text-decoration:none; font-weight:600;">Clique aqui para fazer login</a>
                </div>
            <?php endif; ?>

            <?php if (!$sucesso): ?>
            <form action="cadastro.php" method="POST" class="login-form">
                <div class="form-group">
                    <label for="nome">Nome</label>
                    <input type="text" id="nome" name="nome" placeholder="Digite seu nome completo" required>
                </div>

                <div class="form-group">
                    <label for="email">E-mail</label>
                    <input type="email" id="email" name="email" placeholder="Digite seu e-mail" required>
                </div>

                <div class="form-group">
                    <label for="senha">Senha</label>
                    <input type="password" id="senha" name="senha" placeholder="Digite sua senha" required>
                </div>

                <div class="form-group">
                    <label for="confirmar">Confirmar Senha</label>
                    <input type="password" id="confirmar" name="confirmar" placeholder="Confirme sua senha" required>
                </div>

                <button type="submit" class="login-button">Cadastrar</button>
            </form>
            <?php endif; ?>

            <div class="login-footer">
                <p>Já tem uma conta? <a href="login.php">Faça login</a></p>
            </div>
        </div>
    </div>
</body>
</html>
