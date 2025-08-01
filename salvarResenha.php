<?php
session_start();
require 'conexao.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuarioId = $_SESSION['usuario_id'];
    $livroId = $_POST['livro_id'];
    $resenha = trim($_POST['resenha']);

   
        $stmt = $conn->prepare("UPDATE books SET resenha = ? WHERE id = ? AND usuario_id = ?");
        $stmt->bind_param("sii", $resenha, $livroId, $usuarioId);

        if ($stmt->execute()) {
            header("Location: index.php?aba=Lidos&msg=resenha_salva");
            exit;
        } else {
            echo "Erro ao salvar a resenha.";
        }
    
}
?>
