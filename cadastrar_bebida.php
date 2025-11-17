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
    $tamanho = trim($_POST['tamanho'] ?? '');
    $preco = $_POST['preco'] ?? 0;

    if ($nome === '' || $preco === '') {
        $erro = 'Preencha o nome e o preço da bebida.';
    } else {
        $descricao = $tamanho;
        $res = createProduct($nome, $descricao, (float)$preco, 'bebida');
        if ($res['success']) {
            $sucesso = true;
        } else {
            $erro = 'Erro ao cadastrar bebida: ' . ($res['message'] ?? 'Unknown');
        }
    }
}

$listRes = listProducts('bebida');
$bebidas = [];
if ($listRes['success']) $bebidas = $listRes['data'];
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastrar Bebida | FoodFlow</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .bebidas-lista {
            margin-top: 30px;
        }
        .bebida-card {
            background: #fff;
            border-radius: 12px;
            padding: 15px;
            margin-bottom: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .bebida-info h4 {
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
            <h1 class="brand">Cadastrar Bebida</h1>
            <p class="subtitle">Adicione novas bebidas ao cardápio 🥤</p>
        </div>

        <?php if ($erro): ?>
            <div class="error-message"><?= htmlspecialchars($erro) ?></div>
        <?php elseif ($sucesso): ?>
            <div class="success-message" style="background: #e6ffed; color: #0c8a1f; padding: 10px; border-radius: 8px; margin-bottom: 12px; text-align: center; font-weight: 600;">
                ✅ Bebida cadastrada com sucesso!
            </div>
        <?php endif; ?>

        <form method="POST" class="login-form">
            <div class="form-group">
                <label>Nome da bebida *</label>
                <input type="text" name="nome" placeholder="Ex: Coca-Cola" required>
            </div>

            <div class="form-group">
                <label>Tamanho</label>
                <input type="text" name="tamanho" placeholder="Ex: 350ml, 1L">
            </div>

            <div class="form-group">
                <label>Preço (R$) *</label>
                <input type="number" step="0.01" name="preco" placeholder="Ex: 6.50" required>
            </div>

            <button type="submit" class="login-button">Cadastrar Bebida</button>

            <div class="login-footer">
                <p><a href="dashboard.php">⬅️ Voltar ao Dashboard</a></p>
            </div>
        </form>

        <div class="bebidas-lista">
            <h3 style="text-align:center; margin-bottom:10px;">🍹 Bebidas Cadastradas</h3>
            <?php foreach ($bebidas as $b): ?>
                <div class="bebida-card">
                    <div class="bebida-info">
                        <h4><?= htmlspecialchars($b['nome']); ?></h4>
                        <p><?= htmlspecialchars($b['tamanho']); ?> • R$ <?= number_format($b['preco'], 2, ',', '.'); ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
</body>
</html>
