<?php
session_start();

// Verifica login
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit();
}

$nome = $_SESSION['user_name'] ?? 'Usuário';
$tipo_usuario = $_SESSION['tipo_usuario'] ?? 'admin';
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Relatórios - FoodFlow</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            background: #f5f7fa;
            font-family: "Inter", sans-serif;
        }

        .navbar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .navbar .brand {
            font-weight: 700;
            font-size: 22px;
        }

        .navbar a {
            color: white;
            text-decoration: none;
            font-weight: 600;
            background: rgba(255,255,255,0.2);
            padding: 8px 16px;
            border-radius: 8px;
            transition: background 0.3s;
        }

        .navbar a:hover {
            background: rgba(255,255,255,0.35);
        }

        .container {
            max-width: 1100px;
            margin: 2rem auto;
            background: white;
            border-radius: 16px;
            padding: 2rem;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
        }

        h1 {
            color: #333;
            font-size: 26px;
            margin-bottom: 1rem;
            text-align: center;
        }

        .report-section {
            margin-top: 2rem;
        }

        .report-card {
            background: #f9fafb;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            transition: transform 0.2s;
        }

        .report-card:hover {
            transform: translateY(-3px);
        }

        .report-title {
            font-weight: 700;
            color: #333;
            font-size: 18px;
            margin-bottom: 0.5rem;
        }

        .report-content {
            color: #555;
            font-size: 15px;
            line-height: 1.5;
        }

        .chart {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-align: center;
            border-radius: 12px;
            padding: 2rem;
            margin-top: 1rem;
            font-weight: 600;
            font-size: 16px;
        }

        .back-btn {
            display: inline-block;
            margin-top: 2rem;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-decoration: none;
            padding: 12px 24px;
            border-radius: 8px;
            font-weight: 600;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .back-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(102,126,234,0.4);
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-top: 1.5rem;
        }

        .stat-box {
            background: white;
            border: 2px solid #e0e0e0;
            border-radius: 12px;
            padding: 1.5rem;
            text-align: center;
            transition: all 0.3s;
        }

        .stat-box:hover {
            border-color: #667eea;
            box-shadow: 0 5px 15px rgba(102,126,234,0.2);
        }

        .stat-value {
            font-size: 32px;
            font-weight: 800;
            color: #333;
        }

        .stat-label {
            font-size: 14px;
            color: #777;
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="brand">📊 FoodFlow - Relatórios</div>
        <div>
            <a href="dashboard.php">🏠 Dashboard</a>
            <a href="cardapio.php">🍽️ Cardápio</a>
            <a href="logout.php">🚪 Sair</a>
        </div>
    </nav>

    <div class="container">
        <h1>Relatórios Gerais do Restaurante</h1>

        <div class="grid">
            <div class="stat-box">
                <div class="stat-value">R$ 12.850</div>
                <div class="stat-label">Faturamento do Mês</div>
            </div>
            <div class="stat-box">
                <div class="stat-value">482</div>
                <div class="stat-label">Pedidos Realizados</div>
            </div>
            <div class="stat-box">
                <div class="stat-value">57</div>
                <div class="stat-label">Clientes Novos</div>
            </div>
            <div class="stat-box">
                <div class="stat-value">8%</div>
                <div class="stat-label">Taxa de Cancelamento</div>
            </div>
        </div>

        <div class="report-section">
            <div class="report-card">
                <div class="report-title">📅 Desempenho Semanal</div>
                <div class="report-content">
                    Segunda e sexta-feira são os dias com maior volume de vendas.  
                    Média de 68 pedidos/dia durante a semana e 95 aos fins de semana.
                </div>
                <div class="chart">Gráfico de Vendas Semanais (Simulado)</div>
            </div>

            <div class="report-card">
                <div class="report-title">🥘 Pratos Mais Vendidos</div>
                <div class="report-content">
                    1️⃣ Strogonoff de Frango — 142 unidades <br>
                    2️⃣ X-Burger Especial — 128 unidades <br>
                    3️⃣ Pizza de Calabresa — 95 unidades
                </div>
                <div class="chart">Ranking de Pratos (Simulado)</div>
            </div>

            <div class="report-card">
                <div class="report-title">💳 Métodos de Pagamento</div>
                <div class="report-content">
                    - Cartão de Crédito: 52% <br>
                    - Pix: 31% <br>
                    - Dinheiro: 17%
                </div>
                <div class="chart">Distribuição de Pagamentos (Simulado)</div>
            </div>
        </div>

        <div style="text-align: center;">
            <a href="dashboard.php" class="back-btn">⬅️ Voltar ao Dashboard</a>
        </div>
    </div>
</body>
</html>
