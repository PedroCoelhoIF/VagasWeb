<?php
require_once '../includes/functions.php';
protegerAdmin();

if (!isset($_GET['id'])) {
    header('Location: gerenciar_vagas.php');
    exit;
}

$vaga_id = $_GET['id'];

// 1. Busca informações da Vaga (só para mostrar o título no topo)
$stmt_vaga = $conn->prepare("SELECT titulo FROM vagas WHERE id = ?");
$stmt_vaga->bind_param("i", $vaga_id);
$stmt_vaga->execute();
$vaga = $stmt_vaga->get_result()->fetch_assoc();

if (!$vaga) {
    die("Vaga não encontrada.");
}

// 2. Busca os candidatos dessa vaga
// JOIN: Pega dados da tabela candidaturas E dados da tabela usuarios
$sql = "SELECT u.nome, u.email, u.foto, u.linkedin, c.data_candidatura 
        FROM candidaturas c 
        JOIN usuarios u ON c.usuario_id = u.id 
        WHERE c.vaga_id = ? 
        ORDER BY c.data_candidatura DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $vaga_id);
$stmt->execute();
$candidatos = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Inscritos na Vaga</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        .perfil-mini { width: 40px; height: 40px; border-radius: 50%; object-fit: cover; }
    </style>
</head>
<body>
    <header>
        <div class="container">
            <div class="logo">Candidatos</div>
            <nav>
                <a href="gerenciar_vagas.php">Voltar para Vagas</a>
            </nav>
        </div>
    </header>

    <div class="container">
        <h2>Inscritos para: <span style="color: #007bff;"><?php echo $vaga['titulo']; ?></span></h2>
        <p>Total de candidatos: <strong><?php echo $candidatos->num_rows; ?></strong></p>
        <br>

        <?php if ($candidatos->num_rows > 0): ?>
            <table border="1" cellpadding="10" cellspacing="0" width="100%" style="border-collapse: collapse; border-color: #ddd;">
                <tr style="background: #eee;">
                    <th>Foto</th>
                    <th>Nome</th>
                    <th>E-mail</th>
                    <th>LinkedIn</th>
                    <th>Data Inscrição</th>
                </tr>
                <?php while ($cand = $candidatos->fetch_assoc()): ?>
                <tr>
                    <td>
                        <img src="../uploads/<?php echo $cand['foto'] ? $cand['foto'] : 'default.png'; ?>" class="perfil-mini">
                    </td>
                    <td><?php echo $cand['nome']; ?></td>
                    <td><?php echo $cand['email']; ?></td>
                    <td>
                        <?php if($cand['linkedin']): ?>
                            <a href="<?php echo $cand['linkedin']; ?>" target="_blank">Ver Perfil</a>
                        <?php else: ?>
                            -
                        <?php endif; ?>
                    </td>
                    <td><?php echo date('d/m/Y H:i', strtotime($cand['data_candidatura'])); ?></td>
                </tr>
                <?php endwhile; ?>
            </table>
        <?php else: ?>
            <div class="alerta erro">Nenhum candidato se inscreveu nesta vaga ainda.</div>
        <?php endif; ?>
    </div>
</body>
</html>