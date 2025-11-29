<?php
require_once '../includes/functions.php';
protegerAdmin();

$msg = '';

// Lógica de CADASTRO (Insert)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['nova_categoria'])) {
    $nome = trim($_POST['nome']);
    
    if (!empty($nome)) {
        $stmt = $conn->prepare("INSERT INTO categorias (nome) VALUES (?)");
        $stmt->bind_param("s", $nome);
        if ($stmt->execute()) {
            $msg = "<div class='alerta sucesso'><i class='fa-solid fa-check-circle'></i> Categoria criada com sucesso!</div>";
        } else {
            $msg = "<div class='alerta erro'>Erro ao criar: " . $conn->error . "</div>";
        }
    } else {
        $msg = "<div class='alerta erro'>O nome não pode ser vazio.</div>";
    }
}

// Buscar todas as categorias para listar
$sql = "SELECT * FROM categorias ORDER BY id DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Gerenciar Categorias</title>
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

        <?php echo $msg; ?>

        <div class="card" style="border-top: 5px solid #007bff; text-align: left; margin-bottom: 20px;">
            <h3><i class="fa-solid fa-plus-circle"></i> Nova Categoria</h3>
            <form method="POST" style="display: flex; gap: 10px; align-items: center;">
                <input type="text" name="nome" placeholder="Ex: Tecnologia, Saúde..." required style="margin: 0;">
                <button type="submit" name="nova_categoria" class="btn">Adicionar</button>
            </form>
        </div>

        <h2><i class="fa-solid fa-list"></i> Categorias Existentes</h2>
        
        <table border="0" cellpadding="0" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th width="200">Ações</th>
                </tr>
            </thead>
            <tbody>
            <?php while ($cat = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $cat['id']; ?></td>
                <td><?php echo htmlspecialchars($cat['nome']); ?></td>
                <td>
                    <a href="editar_categoria.php?id=<?php echo $cat['id']; ?>" class="btn" style="background: #ffc107; color: #000; padding: 8px 15px; font-size: 0.8rem;">
                        <i class="fa-solid fa-pen-to-square"></i> Editar
                    </a>
                    
                    <a href="excluir_categoria.php?id=<?php echo $cat['id']; ?>" class="btn btn-danger" style="padding: 8px 15px; font-size: 0.8rem;" onclick="return confirm('Tem certeza?');">
                        <i class="fa-solid fa-trash"></i>
                    </a>
                </td>
            </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>