<?php
require_once '../includes/functions.php';
protegerAdmin();

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // Tenta excluir
    $stmt = $conn->prepare("DELETE FROM categorias WHERE id = ?");
    $stmt->bind_param("i", $id);
    
    // Se tiver vagas usando essa categoria, o banco vai bloquear (Foreign Key)
    if ($stmt->execute()) {
        header('Location: gerenciar_categorias.php');
    } else {
        // Se der erro (ex: tem vaga vinculada), volta e avisa (modo simples)
        echo "<script>alert('Não é possível excluir esta categoria pois há vagas vinculadas a ela!'); window.location='gerenciar_categorias.php';</script>";
    }
} else {
    header('Location: gerenciar_categorias.php');
}
?>