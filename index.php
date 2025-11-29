<?php
require_once 'includes/functions.php';

// Filtros de Pesquisa
$busca = isset($_GET['q']) ? $_GET['q'] : '';
$filtro_cat = isset($_GET['cat']) ? $_GET['cat'] : '';

// Montagem da Query SQL Dinâmica
$sql = "SELECT v.*, c.nome as categoria_nome 
        FROM vagas v 
        JOIN categorias c ON v.categoria_id = c.id 
        WHERE v.ativo = 1"; // Só mostra ativas

if ($busca) {
    $sql .= " AND (v.titulo LIKE '%$busca%' OR v.descricao LIKE '%$busca%')";
}

if ($filtro_cat) {
    $sql .= " AND v.categoria_id = $filtro_cat";
}

$sql .= " ORDER BY v.id DESC";
$result = $conn->query($sql);

// Buscar todas categorias para o menu lateral
$cats = $conn->query("SELECT * FROM categorias ORDER BY nome ASC");
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Sistema de Vagas</title>
    <link rel="stylesheet" href="assets/css/style.css">
    </head>
<body>

    <header>
        <div class="container">
            <div class="logo"><i class="fa-solid fa-briefcase"></i> VagasWeb</div>
            <nav>
                <?php if (estaLogado()): ?>
                    <span>Olá, <strong><?php echo $_SESSION['usuario_nome']; ?></strong></span>
                    <?php if (ehAdmin()): ?>
                        <a href="admin/dashboard.php" style="color: var(--primary);">Painel Admin</a>
                    <?php endif; ?>
                    <a href="logout.php" style="color: #ff6b6b;">Sair</a>
                <?php else: ?>
                    <a href="login.php">Login</a>
                    <a href="cadastro.php" class="btn" style="color: white; margin-left: 10px;">Cadastre-se</a>
                <?php endif; ?>
            </nav>
        </div>
    </header>

    <div class="container">
        <div class="hero-section">
            <h1>Encontre seu próximo emprego aqui</h1>
            <p>Busque vagas nas melhores empresas</p>
            <br>
            <form action="index.php" method="GET" class="search-box" style="display: flex; max-width: 500px; margin: 0 auto; gap: 10px;">
                <input type="text" name="q" placeholder="Ex: PHP, Designer..." value="<?php echo htmlspecialchars($busca); ?>" style="margin: 0; border: none;">
                <button type="submit" class="btn" style="background: var(--bg-card); color: var(--text-main);">Buscar</button>
            </form>
        </div>

        <div style="display: flex; gap: 20px;">
            
            <aside style="width: 250px; flex-shrink: 0;">
                <div class="card" style="text-align: left; border-left: none;">
                    <h3>Categorias</h3>
                    <ul style="list-style: none; margin-top: 10px;">
                        <li style="margin-bottom: 8px;">
                            <a href="index.php" style="color: var(--text-main); text-decoration: none; font-weight: bold;">Todas as vagas</a>
                        </li>
                        <?php while($c = $cats->fetch_assoc()): ?>
                            <li style="margin-bottom: 8px;">
                                <a href="index.php?cat=<?php echo $c['id']; ?>" style="color: var(--primary); text-decoration: none;">
                                    <?php echo $c['nome']; ?>
                                </a>
                            </li>
                        <?php endwhile; ?>
                    </ul>
                </div>
            </aside>

            <main style="flex-grow: 1;">
                <h2><?php echo $busca ? "Resultados para: '$busca'" : "Vagas Recentes"; ?></h2>
                <br>

                <?php if ($result->num_rows > 0): ?>
                    <?php while($vaga = $result->fetch_assoc()): ?>
                        <div class="vaga-card" style="display: flex; gap: 15px;">
                            <?php if($vaga['imagem']): ?>
                                <img src="uploads/<?php echo $vaga['imagem']; ?>" class="vaga-img">
                            <?php endif; ?>
                            
                            <div style="flex: 1;">
                                <h3><?php echo $vaga['titulo']; ?></h3>
                                <div style="margin: 5px 0;">
                                    <span class="badge"><?php echo $vaga['categoria_nome']; ?></span>
                                    <?php if($vaga['salario']): ?>
                                        <span class="badge badge-success">R$ <?php echo number_format($vaga['salario'], 2, ',', '.'); ?></span>
                                    <?php endif; ?>
                                </div>
                                <p style="font-size: 0.9em; color: var(--text-light);">
                                    <?php echo substr($vaga['descricao'], 0, 100); ?>...
                                </p>
                                <br>
                                <a href="ver_vaga.php?id=<?php echo $vaga['id']; ?>" class="btn action-btn">Ver Detalhes</a>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p style="color: var(--text-light);">Nenhuma vaga encontrada.</p>
                <?php endif; ?>
            </main>
        </div>
    </div>
</body>
</html>