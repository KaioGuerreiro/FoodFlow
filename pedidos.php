<?php
session_start();

// 🔒 Verifica se é admin
if (!isset($_SESSION['logged_in']) || $_SESSION['tipo_usuario'] !== 'admin') {
    header('Location: login.php');
    exit();
}

// 📦 Pedidos pré-definidos (dados simulados)
$pedidos = [
    [
        'id' => '1001',
        'usuario_nome' => 'Maria Oliveira',
        'data' => '08/11/2025 14:35',
        'valor_total' => 72.40,
        'itens' => [
            ['nome' => 'Spaghetti à Bolonhesa', 'quantidade' => 2, 'preco' => 29.90],
            ['nome' => 'Suco Natural de Laranja', 'quantidade' => 1, 'preco' => 8.60],
        ],
    ],
    [
        'id' => '1002',
        'usuario_nome' => 'João Silva',
        'data' => '09/11/2025 12:50',
        'valor_total' => 49.00,
        'itens' => [
            ['nome' => 'Filé de Frango Grelhado', 'quantidade' => 1, 'preco' => 25.50],
            ['nome' => 'Refrigerante Lata', 'quantidade' => 2, 'preco' => 6.75],
            ['nome' => 'Água Mineral', 'quantidade' => 1, 'preco' => 4.00],
        ],
    ],
    [
        'id' => '1003',
        'usuario_nome' => 'Ana Costa',
        'data' => '10/11/2025 18:10',
        'valor_total' => 92.80,
        'itens' => [
            ['nome' => 'Risoto de Camarão', 'quantidade' => 2, 'preco' => 38.00],
            ['nome' => 'Água Mineral', 'quantidade' => 2, 'preco' => 4.40],
            ['nome' => 'Suco Natural de Laranja', 'quantidade' => 1, 'preco' => 8.40],
        ],
    ],
];
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Pedidos - Painel Admin | FoodFlow</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .orders-list {
            display: flex;
            flex-direction: column;
            gap: 25px;
            margin-top: 30px;
        }

        .order-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
            animation: slideIn 0.4s ease-out;
        }

        .order-header h3 {
            background: linear-gradient(135deg, #667eea, #764ba2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            font-size: 20px;
            margin-bottom: 10px;
        }

        .order-info p {
            margin: 3px 0;
            color: #444;
            font-size: 14px;
        }

        .order-items ul {
            list-style: none;
            margin-top: 8px;
            padding-left: 0;
            font-size: 14px;
            color: #333;
        }

        .order-items li {
            background: #f7f7ff;
            padding: 6px 10px;
            border-radius: 6px;
            margin-bottom: 4px;
        }

        .menu-container {
            max-width: 950px;
            margin: 60px auto;
            background: #fff;
            padding: 40px;
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            text-align: center;
        }

        .menu-actions {
            margin-bottom: 30px;
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 15px;
        }

        h1 {
            background: linear-gradient(135deg, #667eea, #764ba2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            font-size: 28px;
        }
    </style>
</head>
<body>
<div class="menu-container">
    <h1>📦 Pedidos dos Clientes</h1>

    <div class="menu-actions">
        <a href="dashboard.php" class="login-button">⬅️ Voltar ao Dashboard</a>
        <a href="cardapio.php" class="login-button">📋 Ver Cardápio</a>
    </div>

    <?php if (empty($pedidos)): ?>
        <p class="empty">Nenhum pedido foi realizado ainda 😴</p>
    <?php else: ?>
        <div class="orders-list">
            <?php foreach ($pedidos as $pedido): ?>
                <div class="order-card">
                    <div class="order-header">
                        <h3>Pedido #<?= htmlspecialchars($pedido['id']) ?></h3>
                    </div>

                    <div class="order-info">
                        <p><strong>Cliente:</strong> <?= htmlspecialchars($pedido['usuario_nome']) ?></p>
                        <p><strong>Data:</strong> <?= htmlspecialchars($pedido['data']) ?></p>
                        <p><strong>Valor Total:</strong> R$ <?= number_format($pedido['valor_total'], 2, ',', '.') ?></p>
                    </div>

                    <div class="order-items">
                        <h4>Itens:</h4>
                        <ul>
                            <?php foreach ($pedido['itens'] as $item): ?>
                                <li>
                                    <?= htmlspecialchars($item['quantidade']) ?>x <?= htmlspecialchars($item['nome']) ?>
                                    — R$ <?= number_format($item['preco'] * $item['quantidade'], 2, ',', '.') ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
</body>
</html>
