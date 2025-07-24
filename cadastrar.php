<?php
require 'conexao.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['titulo']) && isset($_POST['status'])) {
    $titulo = $_POST['titulo'];
    $autor = $_POST['autor'];
    $genero = $_POST['genero'];
    $editora = $_POST['editora'];
    $total_paginas = $_POST['total_paginas'];
    $status = $_POST['status'];
	$capa = $_POST['capa'];

    $sql = "INSERT INTO livros (titulo, autor, genero, editora, total_paginas, status, capa) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssss", $titulo, $autor, $genero, $editora, $total_paginas, $status, $capa);

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
	  <script>
		function buscarLivro() {
		  const titulo = document.getElementById('titulo').value;
		  const apiUrl = `https://www.googleapis.com/books/v1/volumes?q=intitle:${encodeURIComponent(titulo)}`;

		  fetch(apiUrl)
			.then(response => response.json())
			.then(data => {
			  if (data.totalItems > 0) {
				const livro = data.items[0].volumeInfo;

				document.getElementById('autor').value = livro.authors ? livro.authors.join(', ') : '';
				document.getElementById('genero').value = livro.categories ? livro.categories.join(', ') : '';
				document.getElementById('editora').value = livro.publisher || '';
				document.getElementById('total_paginas').value = livro.pageCount || '';

				if (livro.imageLinks && livro.imageLinks.thumbnail) {
				  document.getElementById('capa').value = livro.imageLinks.thumbnail;
				  const capaImg = document.getElementById('capa-preview');
				  capaImg.src = livro.imageLinks.thumbnail;
				  capaImg.style.display = 'block';
				}
			  } else {
				alert('Livro não encontrado!');
			  }
			})
			.catch(error => {
			  console.error('Erro ao buscar livro:', error);
			});
		}
	  </script>
	</head>
	<body>
	  <h1>Cadastro de Livro</h1>
	  
	  <form method="POST" action="cadastrar.php">
		<label>Título: <input type="text" name="titulo" id="titulo" required></label>
		<button type="button" id="buscar" onclick="buscarLivro()">Buscar</button><br><br>

		<label>Autor: <input type="text" name="autor" id="autor" required></label><br><br>
		<label>Gênero: <input type="text" name="genero" id="genero" required></label><br><br>
		<label>Editora: <input type="text" name="editora" id="editora" required></label><br><br>
		<label>Número de páginas: <input type="text" name="total_paginas" id="total_paginas" required></label><br><br>

		<label>Status:
		  <select name="status" required>
			<option value="">Selecione</option>
			<option value="Lido">Li</option>
			<option value="Lendo">Lendo</option>
			<option value="Quero ler">Quero ler</option>
		  </select>
		</label><br><br>

		<input type="hidden" name="capa" id="capa">
		<img id="capa-preview" src="" alt="Capa do livro" style="max-width: 150px; display: none; margin-top: 10px;"><br><br>

		<button type="submit">Cadastrar</button>
	  </form>

	  <br><a href="index.php">Voltar para a lista</a>
	</body>
	</html>
