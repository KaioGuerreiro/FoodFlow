<?php
session_start();

// 🔒 Garante que o login ainda funciona
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit();
}

$userName = $_SESSION['user_name'] ?? 'Visitante';

// 📦 Pedidos pré-definidos (apenas visual)
$meusPedidos = [
    [
        'id' => 'PED001',
        'data' => '02/11/2025 19:45',
        'valor_total' => 68.40,
        'itens' => [
            ['nome' => 'Spaghetti à Bolonhesa', 'quantidade' => 1, 'preco' => 29.90],
            ['nome' => 'Suco Natural de Laranja', 'quantidade' => 2, 'preco' => 8.00],
            ['nome' => 'Água Mineral', 'quantidade' => 1, 'preco' => 4.50],
        ]
    ],
    [
        'id' => 'PED002',
        'data' => '28/10/2025 12:30',
        'valor_total' => 44.50,
        'itens' => [
            ['nome' => 'Filé de Frango Grelhado', 'quantidade' => 1, 'preco' => 25.50],
            ['nome' => 'Refrigerante Lata', 'quantidade' => 1, 'preco' => 6.00],
            ['nome' => 'Suco de Abacaxi', 'quantidade' => 1, 'preco' => 13.00],
        ]
    ]
];
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Meus Pedidos - FoodFlow</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .menu-container {
            max-width: 900px;
            margin: 60px auto;
            background: white;
            padding: 40px;
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            text-align: center;
            animation: slideIn 0.5s ease-out;
        }

        .orders-list {
            margin-top: 30px;
            display: grid;
            gap: 20px;
        }

        .order-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 12px;
            padding: 20px;
            text-align: left;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .order-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 30px rgba(0,0,0,0.3);
        }

        .order-header h3 {
            margin-bottom: 10px;
        }

        .order-info {
            font-size: 14px;
            margin-bottom: 10px;
        }

        .order-items ul {
            list-style: none;
            padding-left: 0;
            margin-top: 8px;
        }

        .order-items li {
            font-size: 14px;
            margin-bottom: 4px;
        }
    </style>
</head>
<body>
    <div class="menu-container">
        <h1>📦 Meus Pedidos de <?= htmlspecialchars($userName) ?></h1>

        <div class="menu-actions">
            <a href="cardapio.php" class="login-button">⬅️ Voltar ao Cardápio</a>
            <a href="carrinho.php" class="login-button">🛒 Ver Carrinho</a>
        </div>

        <div class="orders-list">
            <?php foreach ($meusPedidos as $pedido): ?>
                <div class="order-card">
                    <div class="order-header">
                        <h3>Pedido #<?= htmlspecialchars($pedido['id']) ?></h3>
                    </div>

                    <div class="order-info">
                        <p><strong>Data:</strong> <?= htmlspecialchars($pedido['data']) ?></p>
                        <p><strong>Valor Total:</strong> R$ <?= number_format($pedido['valor_total'], 2, ',', '.') ?></p>
                    </div>

                    <div class="order-items">
                        <h4>Itens:</h4>
                        <ul>
                            <?php foreach ($pedido['itens'] as $item): ?>
                                <li>
                                    <?= $item['quantidade'] ?>x <?= htmlspecialchars($item['nome']) ?> —
                                    R$ <?= number_format($item['preco'] * $item['quantidade'], 2, ',', '.') ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>
