<?php
require_once '../includes/functions.php';
protegerAdmin();

$erro = '';
$sucesso = '';

// Buscar categorias para preencher o SELECT
$sql_cat = "SELECT * FROM categorias ORDER BY nome ASC";
$res_cat = $conn->query($sql_cat);

// Processar o formulário
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $titulo = $_POST['titulo'];
    $descricao = $_POST['descricao'];
    $salario = $_POST['salario'];
    $categoria_id = $_POST['categoria_id'];
    
    // Lógica de Upload de Imagem
    $nome_imagem = 'default.png'; // Imagem padrão se não enviar nada
    
    if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] == 0) {
        $extensao = pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION);
        // Gera nome único para não sobreescrever (ex: vaga_1643242.jpg)
        $novo_nome = "vaga_" . time() . "." . $extensao;
        $destino = "../uploads/" . $novo_nome;
        
        if (move_uploaded_file($_FILES['imagem']['tmp_name'], $destino)) {
            $nome_imagem = $novo_nome;
        } else {
            $erro = "Erro ao fazer upload da imagem.";
        }
    }

    if (!$erro) {
        $sql = "INSERT INTO vagas (titulo, descricao, salario, categoria_id, imagem, ativo) VALUES (?, ?, ?, ?, ?, 1)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssdis", $titulo, $descricao, $salario, $categoria_id, $nome_imagem);
        
        if ($stmt->execute()) {
            $sucesso = "Vaga publicada com sucesso!";
        } else {
            $erro = "Erro ao cadastrar no banco: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Nova Vaga</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="container" style="max-width: 600px; margin-top: 30px;">
        <h2>Cadastrar Nova Vaga</h2>
        
        <?php if ($erro) echo "<div class='alerta erro'>$erro</div>"; ?>
        <?php if ($sucesso) echo "<div class='alerta sucesso'>$sucesso <a href='gerenciar_vagas.php'>Ver Vagas</a></div>"; ?>

        <form method="POST" enctype="multipart/form-data">
            
            <label>Título da Vaga:</label>
            <input type="text" name="titulo" required placeholder="Ex: Desenvolvedor PHP Júnior">
            
            <label>Categoria:</label>
            <select name="categoria_id" required>
                <option value="">Selecione uma categoria...</option>
                <?php while($cat = $res_cat->fetch_assoc()): ?>
                    <option value="<?php echo $cat['id']; ?>"><?php echo $cat['nome']; ?></option>
                <?php endwhile; ?>
            </select>
            
            <label>Salário (R$):</label>
            <input type="number" name="salario" step="0.01" placeholder="Ex: 2500.00">
            
            <label>Descrição Detalhada:</label>
            <textarea name="descricao" rows="5" required style="width:100%; padding:10px; margin:10px 0; border:1px solid #ddd;"></textarea>
            
            <label>Imagem da Vaga (Banner):</label>
            <input type="file" name="imagem" accept="image/*">
            
            <button type="submit" class="btn" style="width: 100%; margin-top: 10px;">Publicar Vaga</button>
            <a href="gerenciar_vagas.php" class="btn btn-danger" style="display:block; text-align:center; margin-top:10px; text-decoration:none; background:#6c757d;">Cancelar</a>
        </form>
    </div>
</body>
</html>