<?php
require 'conexao.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $sql = "SELECT * FROM livros WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $livro = $resultado->fetch_assoc();

    if (!$livro) {
        echo "Livro não encontrado!";
        exit();
    }

    $stmt->close();
} else {
    echo "ID não informado!";
    exit();
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $titulo = $_POST['titulo'];
    $autor = $_POST['autor'];
    $genero = $_POST['genero'];
    $editora = $_POST['editora'];
    $total_paginas = $_POST['total_paginas'];

    $sql = "UPDATE livros SET titulo=?, autor=?, genero=?, editora=?, total_paginas=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssii", $titulo, $autor, $genero, $editora, $total_paginas, $id);

    if ($stmt->execute()) {
        header("Location: index.php");
        exit();
    } else {
        echo "Erro ao atualizar: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Editar Livro</title>
	<link rel="stylesheet" href="style.css">

</head>
<body>
    <h1>Editar Livro</h1>
    <form method="POST" action="">
        <label>Título: <input type="text" name="titulo" value="<?= htmlspecialchars($livro['titulo']) ?>" required></label><br><br>
        <label>Autor: <input type="text" name="autor" value="<?= htmlspecialchars($livro['autor']) ?>" required></label><br><br>
        <label>Gênero: <input type="text" name="genero" value="<?= htmlspecialchars($livro['genero']) ?>" required></label><br><br>
        <label>Editora: <input type="text" name="editora" value="<?= htmlspecialchars($livro['editora']) ?>" required></label><br><br>
        <label>Total de páginas: <input type="number" name="total_paginas" value="<?= $livro['total_paginas'] ?>" required></label><br><br>
        <button type="submit">Salvar Alterações</button>
    </form>
    <br>
    <a href="index.php">Voltar</a>
</body>
</html>
