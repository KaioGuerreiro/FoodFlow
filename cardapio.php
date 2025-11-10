<?php
session_start();

// 🔒 Bloqueia totalmente acesso sem login
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.php');
    exit();
}

$userName = $_SESSION['nome'] ?? 'Usuário';
$tipoUsuario = $_SESSION['tipo_usuario'] ?? 'usuario';

// 🛒 Inicializa o carrinho se não existir
if (!isset($_SESSION['carrinho'])) {
    $_SESSION['carrinho'] = [];
}

// 🧾 Itens pré-definidos
$itens = [
    ['nome' => 'Spaghetti à Bolonhesa', 'descricao' => 'Delicioso macarrão com molho de carne e tomate 🍅', 'preco' => 29.90, 'categoria' => 'prato'],
    ['nome' => 'Filé de Frango Grelhado', 'descricao' => 'Acompanhado de arroz, feijão e salada fresca 🥗', 'preco' => 25.50, 'categoria' => 'prato'],
    ['nome' => 'Risoto de Camarão', 'descricao' => 'Risoto cremoso com camarões suculentos 🍤', 'preco' => 38.00, 'categoria' => 'prato'],
    ['nome' => 'Suco Natural de Laranja', 'descricao' => 'Feito na hora, sem conservantes 🍊', 'preco' => 8.00, 'categoria' => 'bebida'],
    ['nome' => 'Refrigerante Lata', 'descricao' => '250ml - Várias opções 🥤', 'preco' => 6.00, 'categoria' => 'bebida'],
    ['nome' => 'Água Mineral', 'descricao' => 'Com ou sem gás 💧', 'preco' => 4.00, 'categoria' => 'bebida']
];

// 🧩 Adicionar ao carrinho
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['produto'])) {
    $produto = $_POST['produto'];
    foreach ($itens as $item) {
        if ($item['nome'] === $produto) {
            $existe = false;

            foreach ($_SESSION['carrinho'] as &$carrItem) {
                if ($carrItem['nome'] === $produto) {
                    $carrItem['quantidade']++;
                    $existe = true;
                    break;
                }
            }

            if (!$existe) {
                $item['quantidade'] = 1;
                $_SESSION['carrinho'][] = $item;
            }

            $mensagem = "✅ {$item['nome']} foi adicionado ao carrinho!";
            break;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cardápio - FoodFlow</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .menu-container {
            max-width: 1000px;
            margin: 60px auto;
            background: white;
            padding: 40px;
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            text-align: center;
            animation: slideIn 0.5s ease-out;
        }

        .menu-container h1 {
            background: linear-gradient(135deg, #667eea, #764ba2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            font-size: 28px;
            margin-bottom: 30px;
        }

        .menu-actions {
            margin-bottom: 30px;
            display: flex;
            justify-content: center;
            gap: 15px;
            flex-wrap: wrap;
        }

        .menu-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(230px, 1fr));
            gap: 20px;
            margin-top: 25px;
        }

        .menu-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .menu-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 30px rgba(0,0,0,0.3);
        }

        form button {
            margin-top: 10px;
            padding: 10px 16px;
            font-size: 14px;
            font-weight: 600;
            color: white;
            background: rgba(255,255,255,0.2);
            border: 2px solid rgba(255,255,255,0.4);
            border-radius: 8px;
            cursor: pointer;
            transition: 0.3s;
        }

        form button:hover {
            background: white;
            color: #667eea;
        }

        .message {
            background: #e7ffe7;
            color: #0a8f08;
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-weight: 600;
        }

        .logout {
            display: block;
            margin-top: 40px;
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s ease;
        }

        .logout:hover {
            color: #764ba2;
        }
    </style>
</head>
<body>
    <div class="menu-container">
        <h1>🍽️ Cardápio - Bem-vindo(a), <?= htmlspecialchars($userName) ?>!</h1>

        <?php if (!empty($mensagem)): ?>
            <div class="message"><?= htmlspecialchars($mensagem) ?></div>
        <?php endif; ?>

        <div class="menu-actions">
            <a href="carrinho.php" class="login-button">🛒 Ver Carrinho (<?= count($_SESSION['carrinho']) ?>)</a>
            <a href="meus_pedidos.php" class="login-button">📦 Meus Pedidos</a>
            <?php if ($tipoUsuario === 'admin'): ?>
                <a href="dashboard.php" class="login-button">⚙️ Dashboard</a>
            <?php endif; ?>
        </div>

        <h2>🍝 Pratos</h2>
        <div class="menu-grid">
            <?php foreach ($itens as $item): ?>
                <?php if ($item['categoria'] === 'prato'): ?>
                    <div class="menu-card">
                        <h3><?= htmlspecialchars($item['nome']) ?></h3>
                        <p><?= htmlspecialchars($item['descricao']) ?></p>
                        <p class="price">R$ <?= number_format($item['preco'], 2, ',', '.') ?></p>
                        <form method="POST">
                            <input type="hidden" name="produto" value="<?= htmlspecialchars($item['nome']) ?>">
                            <button type="submit">Adicionar ao Carrinho</button>
                        </form>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>

        <h2 style="margin-top:40px;">🥤 Bebidas</h2>
        <div class="menu-grid">
            <?php foreach ($itens as $item): ?>
                <?php if ($item['categoria'] === 'bebida'): ?>
                    <div class="menu-card">
                        <h3><?= htmlspecialchars($item['nome']) ?></h3>
                        <p><?= htmlspecialchars($item['descricao']) ?></p>
                        <p class="price">R$ <?= number_format($item['preco'], 2, ',', '.') ?></p>
                        <form method="POST">
                            <input type="hidden" name="produto" value="<?= htmlspecialchars($item['nome']) ?>">
                            <button type="submit">Adicionar ao Carrinho</button>
                        </form>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>

        <a href="logout.php" class="logout">🚪 Sair</a>
    </div>
</body>
</html>
