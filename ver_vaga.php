<?php
require_once 'includes/functions.php';

if (!isset($_GET['id'])) {
    header('Location: index.php');
    exit;
}

$id = $_GET['id'];
$mensagem = '';

// Buscar Detalhes da Vaga
$sql = "SELECT v.*, c.nome as categoria_nome FROM vagas v JOIN categorias c ON v.categoria_id = c.id WHERE v.id = ? AND v.ativo = 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$vaga = $stmt->get_result()->fetch_assoc();

if (!$vaga) {
    die("Vaga não encontrada ou inativa.");
}

// LÓGICA DE CANDIDATURA
$ja_candidatou = false;

if (estaLogado()) {
    $usuario_id = $_SESSION['usuario_id'];
    
    // Verifica se já se candidatou antes
    $check = $conn->query("SELECT id FROM candidaturas WHERE usuario_id = $usuario_id AND vaga_id = $id");
    if ($check->num_rows > 0) {
        $ja_candidatou = true;
    }

    // Processa o clique no botão
    if (isset($_POST['candidatar']) && !$ja_candidatou) {
        $conn->query("INSERT INTO candidaturas (usuario_id, vaga_id) VALUES ($usuario_id, $id)");
        $mensagem = "<div class='alerta sucesso'><i class='fa-solid fa-check'></i> Candidatura enviada com sucesso! Boa sorte.</div>";
        $ja_candidatou = true; // Atualiza status
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title><?php echo $vaga['titulo']; ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <header>
        <div class="container">
            <div class="logo"><i class="fa-solid fa-briefcase"></i> VagasWeb</div>
            <nav>
                <a href="index.php"><i class="fa-solid fa-arrow-left"></i> Voltar para Vagas</a>
            </nav>
        </div>
    </header>

    <div class="container" style="max-width: 800px;">
        <?php echo $mensagem; ?>

        <div class="card">
            
            <?php if($vaga['imagem']): ?>
                <img src="uploads/<?php echo $vaga['imagem']; ?>" style="width: 100%; max-height: 350px; object-fit: cover; border-radius: 8px; margin-bottom: 25px;">
            <?php endif; ?>

            <span class="badge"><?php echo $vaga['categoria_nome']; ?></span>
            
            <h1 style="margin: 15px 0;"><?php echo $vaga['titulo']; ?></h1>
            
            <?php if($vaga['salario']): ?>
                <h3 class="text-glow-green" style="margin-bottom: 20px;">R$ <?php echo number_format($vaga['salario'], 2, ',', '.'); ?></h3>
            <?php endif; ?>

            <h3 style="color: var(--text-light); margin-bottom: 10px;">Descrição da Vaga</h3>
            
            <p style="white-space: pre-wrap; margin-bottom: 30px; color: var(--text-main); font-size: 1.1rem; line-height: 1.8;">
                <?php echo $vaga['descricao']; ?>
            </p>

            <hr style="margin-bottom: 30px; border: 0; border-top: 1px solid var(--border);">

            <div style="text-align: center;">
                <?php if (estaLogado()): ?>
                    <?php if (ehAdmin()): ?>
                        <a href="admin/dashboard.php" class="btn">Voltar ao Painel Admin</a>
                        <p style="margin-top: 10px; color: var(--text-light);">Administradores não se candidatam.</p>
                    
                    <?php elseif ($ja_candidatou): ?>
                        <button class="btn" disabled style="background: var(--bg-body); color: var(--text-light); cursor: not-allowed; box-shadow: none; border: 1px solid var(--border);">
                            <i class="fa-solid fa-check"></i> Você já se candidatou
                        </button>
                    
                    <?php else: ?>
                        <form method="POST">
                            <button type="submit" name="candidatar" class="btn" style="padding: 15px 40px; font-size: 1.2em;">
                                <i class="fa-solid fa-paper-plane"></i> Quero me candidatar!
                            </button>
                        </form>
                    <?php endif; ?>

                <?php else: ?>
                    <p style="margin-bottom: 15px; color: var(--text-light);">Você precisa estar logado para se candidatar.</p>
                    <a href="login.php" class="btn">Fazer Login</a>
                    <a href="cadastro.php" class="btn" style="background: var(--bg-body); border: 1px solid var(--border);">Criar Conta</a>
                <?php endif; ?>
            </div>

        </div>
    </div>
</body>
</html>