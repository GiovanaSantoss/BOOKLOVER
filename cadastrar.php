<?php
require 'conexao.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $titulo = $_POST['titulo'];
    $autor = $_POST['autor'];
    $genero = $_POST['genero'];
    $editora = $_POST['editora'];
    $total_paginas = $_POST['total_paginas'];

    $sql = "INSERT INTO livros (titulo, autor, genero, editora, total_paginas) VALUES ( ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssi", $titulo, $autor, $genero, $editora, $total_paginas);

    if ($stmt->execute()) {
        header("Location: index.php");
        exit();
    } else {
        echo "Erro ao cadastrar: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastrar Livro</title>
	<link rel="stylesheet" href="style.css">

</head>
<body>
    <h1>Cadastro de Livro</h1>
    <form method="POST" action="cadastrar.php">
        <label>Título: <input type="text" name="titulo" required></label><br><br>
        <label>Autor: <input type="text" name="autor" required></label><br><br>
        <label>Gênero: <input type="text" name="genero" required></label><br><br>
        <label>Editora: <input type="text" name="editora" required></label><br><br>
        <label>Número de páginas: <input type="text" name="total_paginas" required></label><br><br>
        <button type="submit">Cadastrar</button>
    </form>
    <br>
    <a href="index.php">Voltar para a lista</a>
</body>
</html>
