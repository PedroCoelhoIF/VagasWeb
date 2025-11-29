<?php
require_once '../includes/functions.php';
protegerAdmin();

if (!isset($_GET['id'])) {
    header('Location: gerenciar_categorias.php');
    exit;
}

$id = $_GET['id'];
$msg = '';

// Atualizar (Update)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = trim($_POST['nome']);
    if (!empty($nome)) {
        $stmt = $conn->prepare("UPDATE categorias SET nome = ? WHERE id = ?");
        $stmt->bind_param("si", $nome, $id);
        if ($stmt->execute()) {
            header('Location: gerenciar_categorias.php'); // Volta para a lista
            exit;
        } else {
            $msg = "<div class='alerta erro'>Erro ao atualizar.</div>";
        }
    }
}

// Busca os dados atuais para preencher o input
$stmt = $conn->prepare("SELECT * FROM categorias WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$categoria = $stmt->get_result()->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Editar Categoria</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="container" style="max-width: 500px; margin-top: 50px;">
        <h2>Editar Categoria</h2>
        <?php echo $msg; ?>
        
        <form method="POST">
            <label>Nome da Categoria:</label>
            <input type="text" name="nome" value="<?php echo htmlspecialchars($categoria['nome']); ?>" required>
            
            <button type="submit" class="btn">Salvar Alterações</button>
            <a href="gerenciar_categorias.php" class="btn btn-danger" style="text-decoration: none;">Cancelar</a>
        </form>
    </div>
</body>
</html>