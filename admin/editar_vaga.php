<?php
require_once '../includes/functions.php';
protegerAdmin();

if (!isset($_GET['id'])) {
    header('Location: gerenciar_vagas.php');
    exit;
}

$id = $_GET['id'];
$erro = '';

// Buscar dados atuais da vaga
$stmt = $conn->prepare("SELECT * FROM vagas WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$vaga = $stmt->get_result()->fetch_assoc();

if (!$vaga) {
    die("Vaga não encontrada.");
}

// Buscar categorias para o select
$res_cat = $conn->query("SELECT * FROM categorias ORDER BY nome ASC");

// Processar Atualização
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $titulo = $_POST['titulo'];
    $descricao = $_POST['descricao'];
    $salario = $_POST['salario'];
    $categoria_id = $_POST['categoria_id'];
    
    // Lógica da Imagem: Só muda se o usuário enviou uma nova
    $nome_imagem = $vaga['imagem']; // Mantém a antiga por padrão
    
    if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] == 0) {
        $extensao = pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION);
        $novo_nome = "vaga_" . time() . "." . $extensao;
        $destino = "../uploads/" . $novo_nome;
        
        if (move_uploaded_file($_FILES['imagem']['tmp_name'], $destino)) {
            $nome_imagem = $novo_nome; // Atualiza para a nova
        }
    }

    $sql = "UPDATE vagas SET titulo=?, descricao=?, salario=?, categoria_id=?, imagem=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssdisi", $titulo, $descricao, $salario, $categoria_id, $nome_imagem, $id);
    
    if ($stmt->execute()) {
        header('Location: gerenciar_vagas.php');
        exit;
    } else {
        $erro = "Erro ao atualizar.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Editar Vaga</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="container" style="max-width: 600px; margin-top: 30px;">
        <h2>Editar Vaga</h2>
        <?php if ($erro) echo "<div class='alerta erro'>$erro</div>"; ?>

        <form method="POST" enctype="multipart/form-data">
            
            <label>Título da Vaga:</label>
            <input type="text" name="titulo" value="<?php echo htmlspecialchars($vaga['titulo']); ?>" required>
            
            <label>Categoria:</label>
            <select name="categoria_id" required>
                <?php while($cat = $res_cat->fetch_assoc()): ?>
                    <option value="<?php echo $cat['id']; ?>" <?php if($cat['id'] == $vaga['categoria_id']) echo 'selected'; ?>>
                        <?php echo $cat['nome']; ?>
                    </option>
                <?php endwhile; ?>
            </select>
            
            <label>Salário (R$):</label>
            <input type="number" name="salario" step="0.01" value="<?php echo $vaga['salario']; ?>">
            
            <label>Descrição:</label>
            <textarea name="descricao" rows="5" required style="width:100%; padding:10px; margin:10px 0; border:1px solid #ddd;"><?php echo htmlspecialchars($vaga['descricao']); ?></textarea>
            
            <label>Imagem Atual:</label><br>
            <?php if($vaga['imagem']): ?>
                <img src="../uploads/<?php echo $vaga['imagem']; ?>" style="width: 100px; border-radius: 5px; margin-bottom: 10px;">
            <?php endif; ?>
            <br>
            <label>Trocar Imagem (Opcional):</label>
            <input type="file" name="imagem" accept="image/*">
            
            <button type="submit" class="btn" style="width: 100%; margin-top: 10px;">Salvar Alterações</button>
            <a href="gerenciar_vagas.php" class="btn btn-danger" style="display:block; text-align:center; margin-top:10px; text-decoration:none; background:#6c757d;">Cancelar</a>
        </form>
    </div>
</body>
</html>