<?php
// Inicia a sessão se ela ainda não existir
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'db.php'; // Garante a conexão

// Função para verificar se o usuário está logado
function estaLogado() {
    return isset($_SESSION['usuario_id']);
}

// Função para verificar se é admin
function ehAdmin() {
    return isset($_SESSION['usuario_tipo']) && $_SESSION['usuario_tipo'] === 'admin';
}

// Função para proteger páginas de Admin (Se não for admin, chuta pro login)
function protegerAdmin() {
    if (!ehAdmin()) {
        header('Location: ../login.php?erro=acesso_negado');
        exit;
    }
}
?>