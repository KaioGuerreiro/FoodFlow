<?php
session_start();

if (!isset($_SESSION['logged_in']) || $_SESSION['tipo_usuario'] !== 'admin') {
    header('Location: login.php');
    exit();
}

// Pratos iguais ao cardápio
$pratos = [
    ['nome' => 'Spaghetti à Bolonhesa', 'descricao' => 'Delicioso macarrão com molho de carne e tomate 🍅', 'preco' => 29.90],
    ['nome' => 'Filé de Frango Grelhado', 'descricao' => 'Acompanhado de arroz, feijão e salada fresca 🥗', 'preco' => 25.50],
    ['nome' => 'Risoto de Camarão', 'descricao' => 'Risoto cremoso com camarões suculentos 🍤', 'preco' => 38.00],
];
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
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .prato-info h4 {
            margin-bottom: 5px;
        }
    </style>
</head>
<body>
<div class="login-container">
    <div class="login-card">
        <div class="login-header">
            <h1 class="brand">Cadastrar Prato</h1>
            <p class="subtitle">Adicione novos pratos ao cardápio 🍝 (Simulação)</p>
        </div>

        <form method="POST" class="login-form" onsubmit="alert('Simulação: cadastro não funcional'); return false;">
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
                <p><a href="dashboard.php">⬅️ Voltar ao Dashboard</a></p>
            </div>
        </form>

        <div class="pratos-lista">
            <h3 style="text-align:center; margin-bottom:10px;">🍽️ Pratos Cadastrados (Exemplo)</h3>
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
