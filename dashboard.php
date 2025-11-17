<?php
session_start();
require_once __DIR__ . '/pedidos.php';
require_once __DIR__ . '/produtos.php';

// Verifica se o usuário está logado e é admin
if (!isset($_SESSION['user_id']) || !isset($_SESSION['logged_in']) || ($_SESSION['tipo_usuario'] ?? '') !== 'admin') {
    header('Location: login.php');
    exit();
}

// Dados do usuário
$nome = $_SESSION['user_name'] ?? 'Administrador';
$tipo_usuario = $_SESSION['tipo_usuario'] ?? 'admin';

// Carrega estatísticas do banco
$db = Database::getInstance()->getConnection();

// Contar pedidos de hoje
$stmt = $db->prepare("SELECT COUNT(*) as total, SUM(total) as faturamento FROM pedidos WHERE DATE(criado_em) = DATE('now')");
$stmt->execute();
$pedidos_hoje = $stmt->fetch() ?? ['total' => 0, 'faturamento' => 0];

// Contar usuários
$stmt = $db->prepare('SELECT COUNT(*) as total FROM users');
$stmt->execute();
$usuarios = $stmt->fetch() ?? ['total' => 0];

// Contar produtos
$stmt = $db->prepare('SELECT COUNT(*) as total FROM produtos');
$stmt->execute();
$produtos = $stmt->fetch() ?? ['total' => 0];
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - FoodFlow</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: "Inter", sans-serif;
            background: #f5f7fa;
            min-height: 100vh;
        }

        .navbar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 1rem 0;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        .navbar-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            font-size: 26px;
            font-weight: 800;
            display: flex;
            align-items: center;
            gap: .5rem;
        }

        .logout-btn {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            border: 2px solid rgba(255, 255, 255, 0.3);
            padding: 8px 20px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            text-decoration: none;
            transition: 0.3s;
        }

        .logout-btn:hover {
            background: rgba(255, 255, 255, 0.3);
        }

        .container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 2rem;
        }

        .welcome-section {
            background: white;
            padding: 2rem;
            border-radius: 16px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            margin-bottom: 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .badge {
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
        }

        .badge-admin {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: white;
            padding: 1.5rem;
            border-radius: 16px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            transition: 0.3s;
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
        }

        .stat-card h3 {
            color: #666;
            font-size: 14px;
            margin-bottom: .5rem;
        }

        .stat-value {
            font-size: 32px;
            font-weight: 800;
            color: #333;
        }

        .quick-actions {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 1rem;
            margin-top: 2rem;
        }

        .action-btn {
            background: white;
            border: 2px solid #e0e0e0;
            padding: 1.5rem;
            border-radius: 12px;
            text-align: center;
            text-decoration: none;
            color: #333;
            font-weight: 600;
            transition: all 0.3s;
        }

        .action-btn:hover {
            border-color: #667eea;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            transform: translateY(-3px);
        }

        .action-icon {
            font-size: 32px;
            display: block;
            margin-bottom: .5rem;
        }

        @media (max-width: 768px) {
            .welcome-section {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
            }

            .container {
                padding: 1rem;
            }
        }
    </style>
</head>

<body>
    <nav class="navbar">
        <div class="navbar-content">
            <div class="logo">FoodFlow - Dashboard</div>
            <a href="logout.php" class="logout-btn">Sair</a>
        </div>
    </nav>

    <div class="container">
        <div class="welcome-section">
            <div>
                <h1>Bem-vindo, <?= htmlspecialchars($nome) ?> 👋</h1>
                <p>Resumo do restaurante hoje:</p>
                <span class="badge badge-admin"><?= ucfirst($tipo_usuario) ?></span>
            </div>
            <div id="clock" style="font-size: 28px; font-weight: 700; color:#333;">--:--</div>
        </div>

        <!-- Dados do banco -->
        <div class="stats-grid">
            <div class="stat-card">
                <h3>Pedidos de Hoje</h3>
                <div class="stat-value"><?= (int) $pedidos_hoje['total'] ?></div>
                <p>Faturamento: R$ <?= number_format($pedidos_hoje['faturamento'] ?? 0, 2, ',', '.') ?></p>
            </div>
            <div class="stat-card">
                <h3>Usuários Cadastrados</h3>
                <div class="stat-value"><?= (int) $usuarios['total'] ?></div>
                <p>Total no sistema</p>
            </div>
            <div class="stat-card">
                <h3>Produtos Cadastrados</h3>
                <div class="stat-value"><?= (int) $produtos['total'] ?></div>
                <p>Pratos e bebidas</p>
            </div>
        </div>

        <!-- Ações rápidas -->
        <h2 style="margin-bottom:1rem;">⚡ Ações Rápidas</h2>
        <div class="quick-actions">
            <a href="cardapio.php" class="action-btn">
                <span class="action-icon">📋</span>
                <span>Ver Cardápio</span>
            </a>

            <a href="cadastrar_prato.php" class="action-btn">
                <span class="action-icon">🍽️</span>
                <span>Cadastrar Pratos</span>
            </a>

            <a href="cadastrar_bebida.php" class="action-btn">
                <span class="action-icon">🥤</span>
                <span>Cadastrar Bebidas</span>
            </a>

            <a href="admin_pedidos.php" class="action-btn">
                <span class="action-icon">📦</span>
                <span>Ver Todos os Pedidos</span>
            </a>

            <a href="relatorios.php" class="action-btn">
                <span class="action-icon">📊</span>
                <span>Ver Relatórios</span>
            </a>
        </div>
    </div>

    <script>
        function updateClock() {
            const now = new Date();
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            document.getElementById('clock').textContent = `${hours}:${minutes}`;
        }
        updateClock();
        setInterval(updateClock, 1000);
    </script>
</body>

</html>