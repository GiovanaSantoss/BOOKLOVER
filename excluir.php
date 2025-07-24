<?php
require 'conexao.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
	
	$stmt = $conn->prepare("DELETE FROM progresso WHERE id_livro = ?");
	$stmt->bind_param("i", $id);
	$stmt->execute();
	$stmt->close();

    $sql = "DELETE FROM livros WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: index.php");
        exit();
    } else {
        echo "Erro ao excluir: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    echo "ID do livro não informado.";
}
?>
