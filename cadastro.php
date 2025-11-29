<?php
require_once 'includes/db.php';

$erro = '';
$sucesso = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = $_POST['senha'];
    $linkedin = $_POST['linkedin'];
    
    // Verificar se e-mail já existe
    $check = $conn->query("SELECT id FROM usuarios WHERE email = '$email'");
    if ($check->num_rows > 0) {
        $erro = "Este e-mail já está cadastrado.";
    } else {
        // Upload da Foto de Perfil
        $nome_foto = 'default.png';
        if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
            $extensao = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
            $novo_nome = "user_" . time() . "." . $extensao;
            $destino = "uploads/" . $novo_nome;
            if (move_uploaded_file($_FILES['foto']['tmp_name'], $destino)) {
                $nome_foto = $novo_nome;
            }
        }

        // Criptografar Senha
        $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

        $sql = "INSERT INTO usuarios (nome, email, senha, linkedin, foto, tipo) VALUES (?, ?, ?, ?, ?, 'user')";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssss", $nome, $email, $senha_hash, $linkedin, $nome_foto);
        
        if ($stmt->execute()) {
            $sucesso = "Cadastro realizado! <a href='login.php'>Faça login aqui</a>";
        } else {
            $erro = "Erro ao cadastrar: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Crie sua conta</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <header>
        <div class="container">
            <div class="logo">VagasWeb</div>
            <nav>
                <a href="index.php">Voltar para Home</a>
                <a href="login.php">Login</a>
            </nav>
        </div>
    </header>

    <div class="container" style="max-width: 500px;">
        <h2>Crie sua conta de candidato</h2>
        <?php if ($erro) echo "<div class='alerta erro'>$erro</div>"; ?>
        <?php if ($sucesso) echo "<div class='alerta sucesso'>$sucesso</div>"; ?>

        <form method="POST" enctype="multipart/form-data">
            <label>Nome Completo:</label>
            <input type="text" name="nome" required>
            
            <label>E-mail:</label>
            <input type="email" name="email" required>
            
            <label>Senha:</label>
            <input type="password" name="senha" required>
            
            <label>Link do LinkedIn:</label>
            <input type="url" name="linkedin" placeholder="https://linkedin.com/in/seu-perfil">
            
            <label>Foto de Perfil:</label>
            <input type="file" name="foto" accept="image/*">
            
            <button type="submit" class="btn" style="width: 100%;">Cadastrar</button>
        </form>
    </div>
</body>
</html>