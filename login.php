<?php
require_once 'includes/functions.php';

$erro = '';

// Se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    // SQL seguro contra invasão
    $sql = "SELECT id, nome, senha, tipo FROM usuarios WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows === 1) {
        $usuario = $resultado->fetch_assoc();
        
        // Verifica se a senha bate com o hash do banco
        if (password_verify($senha, $usuario['senha'])) {
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['usuario_nome'] = $usuario['nome'];
            $_SESSION['usuario_tipo'] = $usuario['tipo'];

            // Redirecionamento inteligente
            if ($usuario['tipo'] === 'admin') {
                header('Location: admin/dashboard.php');
            } else {
                header('Location: index.php');
            }
            exit;
        } else {
            $erro = "Senha incorreta!";
        }
    } else {
        $erro = "E-mail não encontrado!";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Login - Sistema de Vagas</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<div class="container" style="max-width: 400px; margin-top: 50px;">
    <h2 style="text-align: center;">Entrar</h2>
    
    <?php if ($erro): ?>
        <div class="alerta erro"><?php echo $erro; ?></div>
    <?php endif; ?>
    
    <?php if (isset($_GET['erro']) && $_GET['erro'] == 'acesso_negado'): ?>
        <div class="alerta erro">Acesso restrito a administradores!</div>
    <?php endif; ?>

    <form method="POST" action="">
        <label>E-mail:</label>
        <input type="email" name="email" required placeholder="admin@sistema.com">
        
        <label>Senha:</label>
        <input type="password" name="senha" required placeholder="admin">
        
        <button type="submit" class="btn" style="width: 100%;">Entrar</button>
    </form>
    
    <p style="text-align: center; margin-top: 15px;">
        Não tem conta? <a href="cadastro.php">Cadastre-se</a>
    </p>
    <p style="text-align: center; margin-top: 5px;">
        <a href="index.php">Voltar para Home</a>
    </p>
</div>

</body>
</html>