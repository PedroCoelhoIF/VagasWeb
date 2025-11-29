<?php
require_once '../includes/functions.php';
protegerAdmin();

// Consultas SQL
// 1. Total de Usuários Comuns
$sql_users = "SELECT COUNT(*) as total FROM usuarios WHERE tipo = 'user'";
$total_users = $conn->query($sql_users)->fetch_assoc()['total'];

// 2. Total de Vagas Ativas
$sql_ativas = "SELECT COUNT(*) as total FROM vagas WHERE ativo = 1";
$total_ativas = $conn->query($sql_ativas)->fetch_assoc()['total'];

// 3. Total de Vagas Inativas
$sql_inativas = "SELECT COUNT(*) as total FROM vagas WHERE ativo = 0";
$total_inativas = $conn->query($sql_inativas)->fetch_assoc()['total'];

// 4. Total de Candidaturas
$sql_cand = "SELECT COUNT(*) as total FROM candidaturas";
$total_cand = $conn->query($sql_cand)->fetch_assoc()['total'];
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - Admin</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

    <header>
        <div class="container">
            <div class="logo"><i class="fa-solid fa-lock"></i> Painel Admin</div>
            <nav>
                <span style="color: #666; margin-right: 15px;">Olá, <?php echo $_SESSION['usuario_nome']; ?></span>
                <a href="../index.php" target="_blank"><i class="fa-solid fa-external-link-alt"></i> Ver Site</a>
                <a href="../logout.php" style="color: #ff6b6b;"><i class="fa-solid fa-sign-out-alt"></i> Sair</a>
            </nav>
        </div>
    </header>

    <div class="container">
        
    <div class="admin-menu">
    <span><i class="fa-solid fa-bars-progress"></i> Gerenciar:</span>
    
    <a href="dashboard.php" class="btn-menu-dash">
        <i class="fa-solid fa-chart-line"></i> Dashboard
    </a>
    
    <a href="gerenciar_categorias.php" class="btn-menu-cat">
        <i class="fa-solid fa-tags"></i> Categorias
    </a>
    
    <a href="gerenciar_vagas.php" class="btn-menu-vagas">
        <i class="fa-solid fa-briefcase"></i> Vagas
    </a>
    </div>

        <h2>Visão Geral do Sistema</h2>
        <br>

        <div class="dashboard-cards">
            
            <div class="card" style="border-top-color: #0984e3;">
                <div class="card-icon-container">
                    <i class="fa-solid fa-users icon-users"></i>
                </div>
                <h3>Usuários</h3>
                <div class="numero"><?php echo $total_users; ?></div>
                <small>Cadastrados</small>
            </div>

            <div class="card" style="border-top-color: #00b894;">
                <div class="card-icon-container">
                    <i class="fa-solid fa-check-circle icon-vagas"></i>
                </div>
                <h3>Vagas Ativas</h3>
                <div class="numero"><?php echo $total_ativas; ?></div>
                <small>Visíveis no site</small>
            </div>

            <div class="card" style="border-top-color: #636e72;">
                <div class="card-icon-container">
                    <i class="fa-solid fa-eye-slash icon-off"></i>
                </div>
                <h3>Vagas Inativas</h3>
                <div class="numero"><?php echo $total_inativas; ?></div>
                <small>Ocultas / Finalizadas</small>
            </div>

            <div class="card" style="border-top-color: #fdcb6e;">
                <div class="card-icon-container">
                    <i class="fa-solid fa-file-signature icon-cand"></i>
                </div>
                <h3>Candidaturas</h3>
                <div class="numero"><?php echo $total_cand; ?></div>
                <small>Aplicações totais</small>
            </div>

        </div>
    </div>
</body>
</html>