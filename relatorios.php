<?php
session_start();
require_once __DIR__ . '/database.php';

// Verifica login
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit();
}

$nome = $_SESSION['user_name'] ?? 'Usuário';
$tipo_usuario = $_SESSION['tipo_usuario'] ?? 'admin';

// Conexão com banco
$db = Database::getInstance()->getConnection();

// 1. Faturamento do mês atual
$stmt = $db->prepare("
    SELECT COALESCE(SUM(total), 0) as faturamento_mes 
    FROM pedidos 
    WHERE strftime('%Y-%m', criado_em) = strftime('%Y-%m', 'now')
    AND status != 'cancelado'
");
$stmt->execute();
$faturamento = $stmt->fetch();
$faturamento_mes = $faturamento['faturamento_mes'];

// 2. Total de pedidos realizados
$stmt = $db->prepare("SELECT COUNT(*) as total_pedidos FROM pedidos");
$stmt->execute();
$pedidos_total = $stmt->fetch();
$total_pedidos = $pedidos_total['total_pedidos'];

// 3. Clientes novos (cadastrados no mês atual)
$stmt = $db->prepare("
    SELECT COUNT(*) as novos_clientes 
    FROM users 
    WHERE strftime('%Y-%m', datetime('now')) = strftime('%Y-%m', datetime('now'))
");
$stmt->execute();
$clientes = $stmt->fetch();
$clientes_novos = $clientes['novos_clientes'];

// 4. Taxa de cancelamento
$stmt = $db->prepare("
    SELECT 
        COUNT(CASE WHEN status = 'cancelado' THEN 1 END) * 100.0 / NULLIF(COUNT(*), 0) as taxa_cancelamento
    FROM pedidos
");
$stmt->execute();
$cancelamento = $stmt->fetch();
$taxa_cancelamento = round($cancelamento['taxa_cancelamento'] ?? 0, 1);

// 5. Pedidos por dia da semana
$stmt = $db->prepare("
    SELECT 
        CASE CAST(strftime('%w', criado_em) AS INTEGER)
            WHEN 0 THEN 'Domingo'
            WHEN 1 THEN 'Segunda'
            WHEN 2 THEN 'Terça'
            WHEN 3 THEN 'Quarta'
            WHEN 4 THEN 'Quinta'
            WHEN 5 THEN 'Sexta'
            WHEN 6 THEN 'Sábado'
        END as dia_semana,
        COUNT(*) as total
    FROM pedidos
    WHERE criado_em >= date('now', '-30 days')
    GROUP BY strftime('%w', criado_em)
    ORDER BY total DESC
    LIMIT 1
");
$stmt->execute();
$dia_popular = $stmt->fetch();
$dia_mais_vendas = $dia_popular['dia_semana'] ?? 'N/A';

// 6. Média de pedidos por dia
$stmt = $db->prepare("
    SELECT 
        AVG(pedidos_dia) as media_dia
    FROM (
        SELECT DATE(criado_em) as data, COUNT(*) as pedidos_dia
        FROM pedidos
        WHERE criado_em >= date('now', '-30 days')
        GROUP BY DATE(criado_em)
    )
");
$stmt->execute();
$media = $stmt->fetch();
$media_pedidos_dia = round($media['media_dia'] ?? 0);

// 7. Pratos mais vendidos
$stmt = $db->prepare("
    SELECT 
        p.nome,
        SUM(pi.quantidade) as total_vendido
    FROM pedido_items pi
    INNER JOIN produtos p ON pi.produto_id = p.id
    INNER JOIN pedidos ped ON pi.pedido_id = ped.id
    WHERE ped.status != 'cancelado'
    GROUP BY p.id, p.nome
    ORDER BY total_vendido DESC
    LIMIT 3
");
$stmt->execute();
$pratos_populares = $stmt->fetchAll();

// 8. Métodos de pagamento (simulado - adicionar coluna 'metodo_pagamento' se necessário)
// Por enquanto, vamos simular com dados fixos
$metodo_credito = 52;
$metodo_pix = 31;
$metodo_dinheiro = 17;
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Relatórios - FoodFlow</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
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
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            border-radius: 12px;
            margin: 1rem;
        }

        .navbar .brand {
            font-weight: 700;
            font-size: 22px;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: white;
        }

        .navbar div:last-child {
            display: flex;
            gap: 1rem;
        }

        .navbar a {
            color: white;
            text-decoration: none;
            font-weight: 600;
            background: rgba(255, 255, 255, 0.2);
            padding: 8px 16px;
            border-radius: 8px;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            gap: 0.3rem;
        }

        .navbar a:hover {
            background: rgba(255, 255, 255, 0.35);
            transform: translateY(-2px);
        }

        .container {
            max-width: 1100px;
            margin: 2rem auto;
            background: white;
            border-radius: 16px;
            padding: 2rem;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
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
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
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
            box-shadow: 0 6px 15px rgba(102, 126, 234, 0.4);
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
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.2);
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
        <div class="brand">FoodFlow - Relatórios</div>
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
                <div class="stat-value">R$ <?= number_format($faturamento_mes, 2, ',', '.') ?></div>
                <div class="stat-label">Faturamento do Mês</div>
            </div>
            <div class="stat-box">
                <div class="stat-value"><?= $total_pedidos ?></div>
                <div class="stat-label">Pedidos Realizados</div>
            </div>
            <div class="stat-box">
                <div class="stat-value"><?= $clientes_novos ?></div>
                <div class="stat-label">Clientes Novos</div>
            </div>
            <div class="stat-box">
                <div class="stat-value"><?= $taxa_cancelamento ?>%</div>
                <div class="stat-label">Taxa de Cancelamento</div>
            </div>
        </div>

        <div class="report-section">
            <div class="report-card">
                <div class="report-title">📅 Desempenho Semanal</div>
                <div class="report-content">
                    <?php if ($dia_mais_vendas != 'N/A'): ?>
                        <?= $dia_mais_vendas ?> é o dia com maior volume de vendas.
                        Média de <?= $media_pedidos_dia ?> pedidos/dia nos últimos 30 dias.
                    <?php else: ?>
                        Não há dados suficientes para análise semanal.
                    <?php endif; ?>
                </div>
            </div>

            <div class="report-card">
                <div class="report-title">🥘 Pratos Mais Vendidos</div>
                <div class="report-content">
                    <?php if (count($pratos_populares) > 0): ?>
                        <?php foreach ($pratos_populares as $index => $prato): ?>
                            <?= ($index + 1) ?>️⃣ <?= htmlspecialchars($prato['nome']) ?> — <?= (int) $prato['total_vendido'] ?>
                            unidades <br>
                        <?php endforeach; ?>
                    <?php else: ?>
                        Nenhum prato vendido ainda.
                    <?php endif; ?>
                </div>
            </div>

        </div>

        <div style="text-align: center;">
            <a href="dashboard.php" class="back-btn">Voltar ao Dashboard</a>
        </div>
    </div>
</body>

</html>