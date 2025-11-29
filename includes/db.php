<?php
// Configurações do XAMPP padrão
$host = 'localhost';
$user = 'root';     // Usuário padrão do XAMPP
$pass = '';         // Senha padrão do XAMPP
$db   = 'vagas_db'; // Nome do banco 

// Criar conexão 
$conn = new mysqli($host, $user, $pass, $db);

// Checar se deu erro
if ($conn->connect_error) {
    die("Falha na conexão com o banco de dados: " . $conn->connect_error);
}

// Define o charset para aceitar acentos (UTF-8)
$conn->set_charset("utf8");
?>