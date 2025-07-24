<?php
require 'conexao.php';

$sql = "SELECT * FROM livros ORDER BY data_cadastro DESC";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Lista de Livros</title>
	<link rel="stylesheet" href="style.css">
	<link href="https://fonts.googleapis.com/css2?family=Dancing+Script&display=swap" rel="stylesheet">

</head>
<body>
    <h1>BOOKLOVER</h1>
	<h2>Meus Livros</h2>
	
	<a href="cadastrar.php">Cadastrar novo livro</a>

    <?php if ($result && $result->num_rows > 0): ?>
        <ul>
            <?php while ($livro = $result->fetch_assoc()): ?>
                <li>
                    <strong><?= htmlspecialchars($livro['titulo']) ?></strong> -
                    Autor: <?= htmlspecialchars($livro['autor']) ?> -
                    Páginas: <?= $livro['total_paginas'] ?> -
                    Status: <?= htmlspecialchars($livro['status']) ?>
					<a href="excluir.php?id=<?= $livro['id'] ?>" onclick="return confirm('Tem certeza que quer excluir esse livro?')">🗑️ Excluir</a>
					<a href="alterar.php?id=<?= $livro['id'] ?>">✏️ Editar</a>
                </li>
            <?php endwhile; ?>
        </ul>
    <?php else: ?>
        <p>Nenhum livro cadastrado ainda.</p>
    <?php endif; ?>

</body>
</html>
