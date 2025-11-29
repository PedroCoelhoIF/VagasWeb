<?php
require_once '../includes/functions.php';
protegerAdmin();

// 1. Lógica de Ações (Ativar/Desativar/Excluir) - Mantida
if (isset($_GET['acao']) && $_GET['acao'] == 'status' && isset($_GET['id'])) {
    $id = $_GET['id'];
    $conn->query("UPDATE vagas SET ativo = NOT ativo WHERE id = $id");
    header('Location: gerenciar_vagas.php');
    exit;
}

if (isset($_GET['acao']) && $_GET['acao'] == 'excluir' && isset($_GET['id'])) {
    $id = $_GET['id'];
    $conn->query("DELETE FROM vagas WHERE id = $id");
    header('Location: gerenciar_vagas.php');
    exit;
}

// 2. Busca Categorias (Para preencher o Select do filtro)
$cats_filtro = $conn->query("SELECT * FROM categorias ORDER BY nome ASC");

// 3. Lógica de Filtro Avançada
$busca = isset($_GET['busca']) ? $_GET['busca'] : '';
$cat_id = isset($_GET['categoria']) ? $_GET['categoria'] : '';

$sql = "SELECT v.*, c.nome as categoria_nome 
        FROM vagas v 
        JOIN categorias c ON v.categoria_id = c.id ";

$condicoes = [];

// Se digitou algo no campo de texto
if ($busca) {
    $condicoes[] = "(v.titulo LIKE '%$busca%')";
}

// Se selecionou uma categoria
if ($cat_id) {
    $condicoes[] = "v.categoria_id = $cat_id";
}

// Se tiver condições, adiciona o WHERE
if (count($condicoes) > 0) {
    $sql .= " WHERE " . implode(' AND ', $condicoes);
}

$sql .= " ORDER BY v.id DESC";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Gerenciar Vagas</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        .status-ativa { color: var(--primary); font-weight: bold; background: #e0e7ff; padding: 4px 8px; border-radius: 4px; }
        .status-inativa { color: #d63031; font-weight: bold; background: #ffeaa7; padding: 4px 8px; border-radius: 4px; }
        .img-mini { width: 50px; height: 50px; object-fit: cover; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .action-btn { padding: 6px 12px; font-size: 0.85rem; margin-right: 5px; }
        
        /* Estilo extra para o Form de Busca ficar alinhado */
        .search-box {
            display: flex;
            gap: 10px;
            align-items: center;
            flex-wrap: wrap;
        }
        .search-box input, .search-box select { margin: 0; flex: 1; }
        .search-box button { margin: 0; }
    </style>
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

        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h2><i class="fa-solid fa-briefcase"></i> Vagas Cadastradas</h2>
            <a href="criar_vaga.php" class="btn"><i class="fa-solid fa-plus"></i> Nova Vaga</a>
        </div>

        <div class="card" style="padding: 15px; margin-bottom: 20px; border-top: 4px solid var(--secondary);">
            <form method="GET" class="search-box">
                
                <div style="flex: 2; min-width: 200px;">
                    <input type="text" name="busca" placeholder="Buscar por título..." value="<?php echo htmlspecialchars($busca); ?>">
                </div>

                <div style="flex: 1; min-width: 150px;">
                    <select name="categoria">
                        <option value="">Todas as Categorias</option>
                        <?php while($c = $cats_filtro->fetch_assoc()): ?>
                            <option value="<?php echo $c['id']; ?>" <?php if($cat_id == $c['id']) echo 'selected'; ?>>
                                <?php echo $c['nome']; ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <button type="submit" class="btn" style="padding: 10px 20px;">
                    <i class="fa-solid fa-search"></i> Filtrar
                </button>

                <?php if($busca || $cat_id): ?>
                    <a href="gerenciar_vagas.php" class="btn" style="background: #636e72; padding: 10px 20px;">
                        <i class="fa-solid fa-times"></i> Limpar
                    </a>
                <?php endif; ?>
            </form>
        </div>
        
        <table border="0" cellpadding="0" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>Img</th>
                    <th>Título</th>
                    <th>Categoria</th>
                    <th>Status</th>
                    <th width="350">Ações</th>
                </tr>
            </thead>
            <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while ($vaga = $result->fetch_assoc()): ?>
                <tr>
                    <td>
                        <?php if($vaga['imagem']): ?>
                            <img src="../uploads/<?php echo $vaga['imagem']; ?>" class="img-mini">
                        <?php else: ?>
                            <i class="fa-solid fa-image" style="font-size: 20px; color: #ccc;"></i>
                        <?php endif; ?>
                    </td>
                    <td><?php echo $vaga['titulo']; ?></td>
                    <td><?php echo $vaga['categoria_nome']; ?></td>
                    <td>
                        <?php if ($vaga['ativo']): ?>
                            <span class="status-ativa"><i class="fa-solid fa-check"></i> Ativa</span>
                        <?php else: ?>
                            <span class="status-inativa"><i class="fa-solid fa-ban"></i> Inativa</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <a href="ver_inscritos.php?id=<?php echo $vaga['id']; ?>" class="btn action-btn" style="background: #6c5ce7;" title="Ver Inscritos">
                            <i class="fa-solid fa-users"></i>
                        </a>
                        <a href="editar_vaga.php?id=<?php echo $vaga['id']; ?>" class="btn action-btn" style="background: #fab1a0; color: #2d3436;" title="Editar Vaga">
                            <i class="fa-solid fa-pen"></i>
                        </a>
                        <a href="?acao=status&id=<?php echo $vaga['id']; ?>" class="btn action-btn" style="background: #74b9ff;" title="Mudar Status">
                            <?php if($vaga['ativo']): ?>
                                <i class="fa-solid fa-eye-slash"></i>
                            <?php else: ?>
                                <i class="fa-solid fa-eye"></i>
                            <?php endif; ?>
                        </a>
                        <a href="?acao=excluir&id=<?php echo $vaga['id']; ?>" class="btn btn-danger action-btn" onclick="return confirm('Apagar permanentemente?');" title="Excluir">
                            <i class="fa-solid fa-trash"></i>
                        </a>
                    </td>
                </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" style="text-align: center; padding: 30px; color: #666;">
                        <i class="fa-solid fa-search" style="font-size: 2rem; margin-bottom: 10px; display:block;"></i>
                        Nenhuma vaga encontrada com estes filtros.
                    </td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>