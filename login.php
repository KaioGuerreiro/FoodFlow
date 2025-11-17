<?php
session_start();

// Se já estiver logado, redireciona conforme tipo de usuário
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    if (($_SESSION['tipo_usuario'] ?? '') === 'admin') {
        header('Location: dashboard.php');
    } else {
        header('Location: cardapio.php');
    }
    exit();
}

// Mensagem de erro (se houver)
$erro = $_SESSION['error'] ?? '';
unset($_SESSION['error']);
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FoodFlow - Login</title>
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

        .login-footer a {
            color: #667eea;
            font-weight: 600;
            text-decoration: none;
        }

        .login-footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <h1 class="brand">FoodFlow</h1>
                <p class="subtitle">Gerencie seu restaurante com facilidade</p>
            </div>

            <?php if ($erro): ?>
                <div class="error-message"><?= htmlspecialchars($erro) ?></div>
            <?php endif; ?>

            <form action="autenticar.php" method="POST" class="login-form" autocomplete="off">
                <div class="form-group">
                    <label for="username">E-mail</label>
                    <input type="email" id="username" name="username" placeholder="Digite seu e-mail" required autofocus>
                </div>

                <div class="form-group">
                    <label for="password">Senha</label>
                    <input type="password" id="password" name="password" placeholder="Digite sua senha" required>
                </div>

                <div class="form-options">
                    <label class="remember-me">
                        <input type="checkbox" name="remember">
                        <span>Lembrar-me</span>
                    </label>
                </div>

                <button type="submit" class="login-button">Entrar</button>
            </form>

            <div class="login-footer">
                <p>Não tem uma conta? <a href="cadastro.php">Cadastre-se</a></p>
            </div>
        </div>
    </div>
</body>
</html>
