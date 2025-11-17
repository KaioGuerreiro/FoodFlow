<?php
session_start();

// 🔒 Garante o login
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit();
}

$userName = $_SESSION['user_name'] ?? 'Visitante';

// Inicializa carrinho se não existir
if (!isset($_SESSION['carrinho'])) {
    $_SESSION['carrinho'] = [];
}

$carrinho = $_SESSION['carrinho'];

// 📦 Ações de adicionar, remover e alterar quantidade
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['aumentar'])) {
        $i = $_POST['aumentar'];
        $_SESSION['carrinho'][$i]['quantidade']++;
    }

    if (isset($_POST['diminuir'])) {
        $i = $_POST['diminuir'];
        if ($_SESSION['carrinho'][$i]['quantidade'] > 1) {
            $_SESSION['carrinho'][$i]['quantidade']--;
        }
    }

    if (isset($_POST['remover'])) {
        $i = $_POST['remover'];
        unset($_SESSION['carrinho'][$i]);
        $_SESSION['carrinho'] = array_values($_SESSION['carrinho']); // reindexa
    }

    // 🧹 Finalizar pedido
    if (isset($_POST['finalizar'])) {
        // cria pedido no banco
        require_once __DIR__ . '/pedidos.php';
        $user_id = $_SESSION['user_id'] ?? null;
        if ($user_id && !empty($_SESSION['carrinho'])) {
            $items = [];
            foreach ($_SESSION['carrinho'] as $it) {
                $items[] = ['produto_id' => $it['id'], 'quantidade' => $it['quantidade']];
            }
            $res = createOrder($user_id, $items);
            if ($res['success']) {
                unset($_SESSION['carrinho']);
                $_SESSION['msg_sucesso'] = 'Pedido #' . $res['pedido_id'] . ' criado com sucesso!';
                header('Location: meus_pedidos.php');
                exit();
            } else {
                $_SESSION['error'] = 'Erro ao criar pedido: ' . ($res['message'] ?? 'Unknown');
            }
        } else {
            $_SESSION['error'] = 'Carrinho vazio ou usuário não autenticado.';
        }
    }

    header('Location: carrinho.php');
    exit();
}

// 🧾 Calcula subtotal e total
$subtotal = 0;
foreach ($carrinho as $item) {
    $subtotal += $item['preco'] * $item['quantidade'];
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Carrinho - FoodFlow</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .menu-container {
            max-width: 900px;
            margin: 60px auto;
            background: white;
            padding: 40px;
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            animation: slideIn 0.5s ease-out;
        }
        h1 {
            text-align: center;
            background: linear-gradient(135deg, #667eea, #764ba2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .cart-table {
            width: 100%;
            margin-top: 30px;
            border-collapse: collapse;
        }
        .cart-table th, .cart-table td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: center;
        }
        .cart-table th {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        .cart-summary {
            margin-top: 30px;
            text-align: right;
            font-weight: bold;
        }
        .login-button {
            width: auto;
            padding: 10px 20px;
            margin-top: 10px;
        }
        .delivery-options, .payment-options {
            text-align: left;
            margin-top: 30px;
            background: #f9f9f9;
            border-radius: 10px;
            padding: 20px;
        }
        .delivery-options h3, .payment-options h3 {
            margin-bottom: 10px;
            color: #333;
        }
        .endereco {
            margin-top: 15px;
            display: none;
        }
        .endereco input {
            width: 100%;
            padding: 8px;
            margin: 5px 0;
            border-radius: 6px;
            border: 1px solid #ccc;
        }
        .empty {
            text-align: center;
            margin-top: 40px;
            color: #666;
        }
        button[name="aumentar"], button[name="diminuir"], button[name="remover"] {
            background: none;
            border: none;
            cursor: pointer;
            font-size: 18px;
            color: #764ba2;
        }
        button[name="remover"] {
            color: red;
        }
    </style>

    <script>
        function toggleEndereco() {
            const delivery = document.getElementById('delivery');
            const endereco = document.getElementById('endereco');
            const taxaEntrega = document.getElementById('taxaEntrega');

            if (delivery.checked) {
                endereco.style.display = 'block';
                taxaEntrega.value = 3.00;
            } else {
                endereco.style.display = 'none';
                taxaEntrega.value = 0.00;
            }

            atualizarTotal();
        }

        function atualizarTotal() {
            let subtotal = parseFloat(document.getElementById('subtotal').value);
            let taxa = parseFloat(document.getElementById('taxaEntrega').value);
            let total = subtotal + taxa;
            document.getElementById('valorTotal').innerText = total.toFixed(2).replace('.', ',');
        }

        window.onload = function() {
            toggleEndereco();
            atualizarTotal();
        };
    </script>
</head>
<body>
    <div class="menu-container">
        <h1>🛒 Carrinho de Compras</h1>

        <div class="menu-actions" style="text-align:center; margin-bottom:20px;">
            <a href="cardapio.php" class="login-button">⬅️ Voltar ao Cardápio</a>
        </div>

        <?php if (empty($carrinho)): ?>
            <p class="empty">Seu carrinho está vazio 😢</p>
        <?php else: ?>
            <form method="POST">
                <table class="cart-table">
                    <thead>
                        <tr>
                            <th>Item</th>
                            <th>Preço Unitário</th>
                            <th>Quantidade</th>
                            <th>Total</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($carrinho as $i => $item): ?>
                            <tr>
                                <td><?= htmlspecialchars($item['nome']) ?></td>
                                <td>R$ <?= number_format($item['preco'], 2, ',', '.') ?></td>
                                <td><?= $item['quantidade'] ?></td>
                                <td>R$ <?= number_format($item['preco'] * $item['quantidade'], 2, ',', '.') ?></td>
                                <td>
                                    <button type="submit" name="aumentar" value="<?= $i ?>">➕</button>
                                    <button type="submit" name="diminuir" value="<?= $i ?>">➖</button>
                                    <button type="submit" name="remover" value="<?= $i ?>">🗑️</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <div class="delivery-options">
                    <h3>🚚 Tipo de Pedido</h3>
                    <label><input type="radio" name="tipo" value="local" checked onclick="toggleEndereco()"> Consumir no local</label><br>
                    <label><input type="radio" name="tipo" value="retirar" onclick="toggleEndereco()"> Retirar no balcão</label><br>
                    <label><input type="radio" name="tipo" id="delivery" value="delivery" onclick="toggleEndereco()"> Delivery (taxa R$ 3,00)</label>

                    <div id="endereco" class="endereco">
                        <h4>Endereço de Entrega</h4>
                        <input type="text" name="bairro" placeholder="Bairro">
                        <input type="text" name="rua" placeholder="Rua">
                        <input type="text" name="numero" placeholder="Número">
                        <input type="text" name="info" placeholder="Informações adicionais">
                    </div>
                </div>

                <div class="payment-options">
                    <h3>💳 Forma de Pagamento</h3>
                    <label><input type="radio" name="pagamento" value="credito" checked> Cartão de Crédito</label><br>
                    <label><input type="radio" name="pagamento" value="debito"> Cartão de Débito</label><br>
                    <label><input type="radio" name="pagamento" value="pix"> Pix</label><br>
                    <label><input type="radio" name="pagamento" value="dinheiro"> Dinheiro</label>
                </div>

                <div class="cart-summary">
                    <input type="hidden" id="subtotal" value="<?= $subtotal ?>">
                    <input type="hidden" id="taxaEntrega" value="0.00">
                    <h3>Subtotal: R$ <?= number_format($subtotal, 2, ',', '.') ?></h3>
                    <h3>Total: R$ <span id="valorTotal"><?= number_format($subtotal, 2, ',', '.') ?></span></h3>
                    <button type="submit" name="finalizar" class="login-button">✅ Finalizar Pedido</button>
                </div>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>
