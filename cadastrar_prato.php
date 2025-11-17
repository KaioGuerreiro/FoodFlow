<?php
session_start();

if (!isset($_SESSION['logged_in']) || $_SESSION['tipo_usuario'] !== 'admin') {
    header('Location: login.php');
    exit();
}

require_once __DIR__ . '/produtos.php';

$erro = '';
$sucesso = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $descricao = trim($_POST['descricao'] ?? '');
    $preco = $_POST['preco'] ?? 0;

    if ($nome === '' || $preco === '') {
        $erro = 'Preencha o nome e o preço do prato.';
    } else {
        $res = createProduct($nome, $descricao, (float) $preco, 'prato');
        if ($res['success']) {
            $sucesso = true;
        } else {
            $erro = 'Erro ao cadastrar prato: ' . ($res['message'] ?? 'Unknown');
        }
    }
}

$listRes = listProducts('prato');
$pratos = [];
if ($listRes['success'])
    $pratos = $listRes['data'];
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Cadastrar Prato | FoodFlow</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .pratos-lista {
            margin-top: 30px;
        }

        .prato-card {
            background: #fff;
            border-radius: 12px;
            padding: 15px;
            margin-bottom: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .prato-info h4 {
            margin-bottom: 5px;
        }

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
                <h1 class="brand">Cadastrar Prato</h1>
                <p class="subtitle">Adicione novos pratos ao cardápio 🍝</p>
            </div>

            <?php if ($erro): ?>
                <div class="error-message"><?= htmlspecialchars($erro) ?></div>
            <?php elseif ($sucesso): ?>
                <div class="success-message"
                    style="background: #e6ffed; color: #0c8a1f; padding: 10px; border-radius: 8px; margin-bottom: 12px; text-align: center; font-weight: 600;">
                    ✅ Prato cadastrado com sucesso!
                </div>
            <?php endif; ?>

            <form method="POST" class="login-form">
                <div class="form-group">
                    <label>Nome do prato *</label>
                    <input type="text" name="nome" placeholder="Ex: Lasanha à bolonhesa" required>
                </div>

                <div class="form-group">
                    <label>Descrição</label>
                    <input type="text" name="descricao" placeholder="Breve descrição do prato">
                </div>

                <div class="form-group">
                    <label>Preço (R$) *</label>
                    <input type="number" step="0.01" name="preco" placeholder="Ex: 25.90" required>
                </div>

                <button type="submit" class="login-button">Cadastrar Prato</button>

                <div class="login-footer">
                    <p><a href="dashboard.php">Voltar ao Dashboard</a></p>
                </div>
            </form>

            <div class="pratos-lista">
                <h3 style="text-align:center; margin-bottom:10px;">🍽️ Pratos Cadastrados</h3>
                <?php foreach ($pratos as $p): ?>
                    <div class="prato-card">
                        <div class="prato-info">
                            <h4><?= htmlspecialchars($p['nome']); ?></h4>
                            <p><?= htmlspecialchars($p['descricao']); ?></p>
                            <p><strong>R$ <?= number_format($p['preco'], 2, ',', '.'); ?></strong></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</body>

</html>