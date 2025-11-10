<?php 
session_start();

if (!isset($_SESSION['logged_in']) || $_SESSION['tipo_usuario'] !== 'admin') {
    header('Location: login.php');
    exit();
}

// Bebidas iguais ao cardápio
$bebidas = [
    ['nome' => 'Suco Natural de Laranja', 'tamanho' => '500ml', 'preco' => 8.00],
    ['nome' => 'Refrigerante Lata', 'tamanho' => '350ml', 'preco' => 6.00],
    ['nome' => 'Água Mineral', 'tamanho' => '500ml', 'preco' => 4.00],
];
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
    </style>
</head>
<body>
<div class="login-container">
    <div class="login-card">
        <div class="login-header">
            <h1 class="brand">Cadastrar Bebida</h1>
            <p class="subtitle">Adicione novas bebidas ao cardápio 🥤 (Simulação)</p>
        </div>

        <form method="POST" class="login-form" onsubmit="alert('Simulação: cadastro não funcional'); return false;">
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
            <h3 style="text-align:center; margin-bottom:10px;">🍹 Bebidas Cadastradas (Exemplo)</h3>
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
