<?php
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['user_id']) || !isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit();
}

// Pega os dados do usuário
$nome = $_SESSION['nome'] ?? 'Usuário';
$username = $_SESSION['username'] ?? '';
$tipo_usuario = $_SESSION['tipo_usuario'] ?? 'funcionario';
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - FoodFlow</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
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

        /* Navbar */
        .navbar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 1rem 0;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .navbar-content {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            font-size: 28px;
            font-weight: 800;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }

        .user-profile {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            font-weight: 700;
        }

        .user-details {
            text-align: right;
        }

        .user-name {
            font-weight: 600;
            font-size: 14px;
        }

        .user-role {
            font-size: 12px;
            opacity: 0.9;
        }

        .logout-btn {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            border: 2px solid rgba(255, 255, 255, 0.3);
            padding: 8px 20px;
            border-radius: 8px;
            cursor: pointer;
            font-family: "Inter", sans-serif;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-block;
        }

        .logout-btn:hover {
            background: rgba(255, 255, 255, 0.3);
            border-color: rgba(255, 255, 255, 0.5);
        }

        /* Container */
        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 2rem;
        }

        /* Welcome Section */
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

        .welcome-content h1 {
            color: #333;
            font-size: 28px;
            margin-bottom: 0.5rem;
        }

        .welcome-content p {
            color: #666;
            font-size: 15px;
        }

        .badge {
            display: inline-block;
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
            margin-top: 0.5rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .badge-admin {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .badge-gerente {
            background: linear-gradient(135deg, #f59e0b 0%, #ef4444 100%);
            color: white;
        }

        .badge-funcionario {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
        }

        .welcome-time {
            text-align: right;
            color: #666;
        }

        .welcome-time .time {
            font-size: 32px;
            font-weight: 700;
            color: #333;
        }

        .welcome-time .date {
            font-size: 14px;
            margin-top: 0.25rem;
        }

        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: white;
            padding: 1.75rem;
            border-radius: 16px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }

        .stat-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 1rem;
        }

        .stat-icon {
            font-size: 48px;
            opacity: 0.9;
        }

        .stat-trend {
            font-size: 12px;
            padding: 4px 8px;
            border-radius: 6px;
            font-weight: 600;
        }

        .trend-up {
            background: #d1fae5;
            color: #065f46;
        }

        .trend-down {
            background: #fee2e2;
            color: #991b1b;
        }

        .stat-content h3 {
            color: #666;
            font-size: 14px;
            font-weight: 500;
            margin-bottom: 0.5rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .stat-value {
            font-size: 36px;
            font-weight: 800;
            color: #333;
            margin-bottom: 0.25rem;
        }

        .stat-detail {
            font-size: 13px;
            color: #999;
        }

        /* Charts Section */
        .charts-section {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .chart-card {
            background: white;
            padding: 2rem;
            border-radius: 16px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .chart-card h2 {
            color: #333;
            font-size: 18px;
            margin-bottom: 1.5rem;
            font-weight: 700;
        }

        .chart-placeholder {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            height: 300px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 18px;
            font-weight: 600;
        }

        /* Recent Activity */
        .activity-section {
            background: white;
            padding: 2rem;
            border-radius: 16px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .activity-section h2 {
            color: #333;
            font-size: 18px;
            margin-bottom: 1.5rem;
            font-weight: 700;
        }

        .activity-list {
            list-style: none;
        }

        .activity-item {
            padding: 1rem 0;
            border-bottom: 1px solid #f0f0f0;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .activity-item:last-child {
            border-bottom: none;
        }

        .activity-icon {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
        }

        .activity-content {
            flex: 1;
        }

        .activity-title {
            color: #333;
            font-weight: 600;
            font-size: 14px;
            margin-bottom: 0.25rem;
        }

        .activity-time {
            color: #999;
            font-size: 12px;
        }

        /* Quick Actions */
        .quick-actions {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-top: 2rem;
        }

        .action-btn {
            background: white;
            border: 2px solid #e0e0e0;
            padding: 1.25rem;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.3s;
            text-align: center;
            text-decoration: none;
            color: #333;
            font-weight: 600;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.75rem;
        }

        .action-btn:hover {
            border-color: #667eea;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
        }

        .action-icon {
            font-size: 32px;
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .charts-section {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 768px) {
            .navbar-content {
                padding: 0 1rem;
            }

            .logo {
                font-size: 24px;
            }

            .container {
                padding: 1rem;
            }

            .welcome-section {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
            }

            .welcome-time {
                text-align: left;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .user-details {
                display: none;
            }
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar">
        <div class="navbar-content">
            <div class="logo">🍔 FoodFlow</div>
            <div class="user-info">
                <div class="user-profile">
                    <div class="user-avatar"><?php echo strtoupper(substr($nome, 0, 1)); ?></div>
                    <div class="user-details">
                        <div class="user-name"><?php echo htmlspecialchars($nome); ?></div>
                        <div class="user-role">@<?php echo htmlspecialchars($username); ?></div>
                    </div>
                </div>
                <a href="logout.php" class="logout-btn">Sair</a>
            </div>
        </div>
    </nav>

    <!-- Main Container -->
    <div class="container">
        <!-- Welcome Section -->
        <div class="welcome-section">
            <div class="welcome-content">
                <h1>Bem-vindo de volta, <?php echo htmlspecialchars(explode(' ', $nome)[0]); ?>! 👋</h1>
                <p>Aqui está o resumo do seu restaurante hoje</p>
                <span class="badge badge-<?php echo $tipo_usuario; ?>">
                    <?php echo ucfirst($tipo_usuario); ?>
                </span>
            </div>
            <div class="welcome-time">
                <div class="time" id="clock">--:--</div>
                <div class="date" id="date">--</div>
            </div>
        </div>

        <!-- Stats Grid -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-icon">📊</div>
                    <span class="stat-trend trend-up">↑ 12%</span>
                </div>
                <div class="stat-content">
                    <h3>Pedidos Hoje</h3>
                    <div class="stat-value">47</div>
                    <div class="stat-detail">+5 desde ontem</div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-icon">💰</div>
                    <span class="stat-trend trend-up">↑ 8%</span>
                </div>
                <div class="stat-content">
                    <h3>Faturamento</h3>
                    <div class="stat-value">R$ 2.847</div>
                    <div class="stat-detail">Meta: R$ 3.000</div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-icon">👥</div>
                    <span class="stat-trend trend-up">↑ 15%</span>
                </div>
                <div class="stat-content">
                    <h3>Clientes Ativos</h3>
                    <div class="stat-value">128</div>
                    <div class="stat-detail">+18 novos esta semana</div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-icon">🍽️</div>
                    <span class="stat-trend trend-down">↓ 3%</span>
                </div>
                <div class="stat-content">
                    <h3>Mesas Ocupadas</h3>
                    <div class="stat-value">8/15</div>
                    <div class="stat-detail">53% de ocupação</div>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="charts-section">
            <div class="chart-card">
                <h2>📈 Vendas da Semana</h2>
                <div class="chart-placeholder">Gráfico de Vendas</div>
            </div>

            <div class="chart-card">
                <h2>🥘 Pratos Mais Vendidos</h2>
                <div class="chart-placeholder">Top Pratos</div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="activity-section">
            <h2>⚡ Atividades Recentes</h2>
            <ul class="activity-list">
                <li class="activity-item">
                    <div class="activity-icon">🛎️</div>
                    <div class="activity-content">
                        <div class="activity-title">Novo pedido #1247 - Mesa 5</div>
                        <div class="activity-time">Há 2 minutos</div>
                    </div>
                </li>
                <li class="activity-item">
                    <div class="activity-icon">✅</div>
                    <div class="activity-content">
                        <div class="activity-title">Pedido #1246 finalizado</div>
                        <div class="activity-time">Há 15 minutos</div>
                    </div>
                </li>
                <li class="activity-item">
                    <div class="activity-icon">👤</div>
                    <div class="activity-content">
                        <div class="activity-title">Novo cliente cadastrado: Maria Silva</div>
                        <div class="activity-time">Há 30 minutos</div>
                    </div>
                </li>
                <li class="activity-item">
                    <div class="activity-icon">💳</div>
                    <div class="activity-content">
                        <div class="activity-title">Pagamento recebido - R$ 156,00</div>
                        <div class="activity-time">Há 45 minutos</div>
                    </div>
                </li>
                <li class="activity-item">
                    <div class="activity-icon">📦</div>
                    <div class="activity-content">
                        <div class="activity-title">Estoque atualizado - 15 itens adicionados</div>
                        <div class="activity-time">Há 1 hora</div>
                    </div>
                </li>
            </ul>
        </div>

        <!-- Quick Actions -->
        <div class="quick-actions">
            <a href="#" class="action-btn">
                <span class="action-icon">➕</span>
                <span>Novo Pedido</span>
            </a>
            <a href="#" class="action-btn">
                <span class="action-icon">📋</span>
                <span>Ver Cardápio</span>
            </a>
            <a href="#" class="action-btn">
                <span class="action-icon">📊</span>
                <span>Relatórios</span>
            </a>
            <a href="#" class="action-btn">
                <span class="action-icon">⚙️</span>
                <span>Configurações</span>
            </a>
        </div>
    </div>

    <script>
        // Atualiza relógio em tempo real
        function updateClock() {
            const now = new Date();

            // Atualiza hora
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            document.getElementById('clock').textContent = `${hours}:${minutes}`;

            // Atualiza data
            const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            const dateStr = now.toLocaleDateString('pt-BR', options);
            document.getElementById('date').textContent = dateStr.charAt(0).toUpperCase() + dateStr.slice(1);
        }

        // Atualiza a cada segundo
        updateClock();
        setInterval(updateClock, 1000);
    </script>
</body>

</html>