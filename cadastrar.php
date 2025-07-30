<?php
session_start();
require 'conexao.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

$usuarioId = $_SESSION['usuario_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['titulo'])) {
    $titulo = $_POST['titulo'];
    $autor = $_POST['autor'];
    $genero = $_POST['genero'];
    $editora = $_POST['editora'];
    $total_paginas = $_POST['total_paginas'];
    $status = $_POST['status'];
    $capa = $_POST['capa'];
    $confirmado = isset($_POST['confirmado']) ? $_POST['confirmado'] : 'nao';

   
    $checkSql = "SELECT id FROM books WHERE titulo = ? AND usuario_id = ?";
    $stmtCheck = $conn->prepare($checkSql);
    $stmtCheck->bind_param("si", $titulo, $usuarioId);
    $stmtCheck->execute();
    $stmtCheck->store_result();

    if ($stmtCheck->num_rows > 0 && $confirmado !== 'sim') {
    ?>
    <!DOCTYPE html>
    <html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <title>Confirmação</title>
        <link rel="stylesheet" href="style.css">
		<link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
		<link href="https://fonts.googleapis.com/css2?family=Dancing+Script&display=swap" rel="stylesheet">
		<link href="https://fonts.googleapis.com/css2?family=Montserrat&display=swap" rel="stylesheet">

        <style>
		  body, html {
			height: 100%;
			margin: 0;
			padding: 0;
			
		  }

		  .container-confirmacao {
			display: flex;
			align-items: center;
			justify-content: center;
			background-color: #f8f9fa;
			height: 100vh;
			background-image: url('images/imagemfundo.png');
		    background-repeat: no-repeat;
		    background-size: cover;
		    background-position: center;
		}

		  .caixa-confirmacao {
			background: #fff3cd;
			border: 1px solid #ffeeba;
			padding: 20px;
			max-width: 500px;
			width: 90%;
			text-align: center;
			border-radius: 8px;
			font-family: Arial, sans-serif;
			box-shadow: 0 0 10px rgba(0,0,0,0.1);
			font-weight: bold;
		  }

		  .caixa-confirmacao button {
			margin: 10px;
			padding: 10px 20px;
			font-weight: bold;
			cursor: pointer;
		  }
		  
		  .caixa-confirmacao button:hover{
			background-color: #FF1493;
		  }
		  .titulo {
		  position: absolute;
		  font-size: 2.5em;
		  font-family: 'Dancing Script', cursive;
		  color: #FAEBD7;
		  text-shadow: 0 0 8px #FFB6C1, 0 0 15px #FF69B4, 0 0 25px #FF1493;
		  margin-top: 20px;
		  text-align: right;
		  width: 100%;
		  top: 30px;
		  right: 50px;
		  margin: 0; 
		}
		.frase{
			position:absolute;
			text-align: left;
			margin-top: 20px;
			width: 100%;
			top: 30px;
			left: 50px;
			margin: 0;
			font-size: 15px;
			color: #C2185B;
			font-family: 'Montserrat', sans-serif;
		}
		</style>

    </head>
    <body>
	<div class="container-confirmacao">
		<div style="text-align: right;">
			<h1 class="titulo">BOOKLOVER</h1>
		</div>
		<div style="text-align: left;">
			<h1 class="frase">You're on your own, kid 💖</h1>
		</div>
        <div class="caixa-confirmacao">
            <p>⚠️ Você já cadastrou esse livro antes.</p>
            <p>Deseja adicionar novamente mesmo assim?</p>
            <form method="POST" action="cadastrar.php">
                <?php
                foreach ($_POST as $chave => $valor) {
                    echo "<input type='hidden' name='" . htmlspecialchars($chave) . "' value='" . htmlspecialchars($valor, ENT_QUOTES) . "'>";
                }
                ?>
                <input type="hidden" name="confirmado" value="sim">
                <button type="submit">Sim, adicionar</button>
                <a href="cadastrar.php"><button type="button">Cancelar</button></a>
            </form>
        </div>
		</div>
    </body>
    </html>
    <?php
    exit;
}

    $sql = "INSERT INTO books (titulo, autor, genero, editora, total_paginas, capa, usuario_id) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssi", $titulo, $autor, $genero, $editora, $total_paginas, $capa, $usuarioId);

    if ($stmt->execute()) {
        $idLivro = $stmt->insert_id;

        $sqlStatus = "INSERT INTO status (id_livro, usuario_id, status) VALUES (?, ?, ?)";
        $stmtStatus = $conn->prepare($sqlStatus);
        $stmtStatus->bind_param("iis", $idLivro, $usuarioId, $status);
        $stmtStatus->execute();

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
  <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Dancing+Script&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Montserrat&display=swap" rel="stylesheet">
  <style>
	body{
	  background-image: url('images/wallpaper.png');
	  background-repeat: no-repeat;
	  background-size: cover;
	  background-position: center;
	}
	 .titulo {
		  position: absolute;
		  font-size: 2.5em;
		  font-family: 'Dancing Script', cursive;
		  color: #FAEBD7;
		  text-shadow: 0 0 8px #FFB6C1, 0 0 15px #FF69B4, 0 0 25px #FF1493;
		  margin-top: 20px;
		  text-align: right;
		  width: 100%;
		  top: 30px;
		  right: 50px;
		  margin: 0; 
		}
		
	.subtitulo{
		font-family: 'Montserrat', sans-serif;
		color: #FF1493;
		text-align: center;
		font-size: 20px;
	}
  </style>

  <script>
  function buscarLivro() {
    const titulo = document.getElementById('titulo').value;
    const apiUrl = `https://www.googleapis.com/books/v1/volumes?q=intitle:${encodeURIComponent(titulo)}`;

    fetch(apiUrl)
      .then(response => response.json())
      .then(data => {
        const resultados = document.getElementById('resultados');
        resultados.innerHTML = '';

        if (!data.items || data.items.length === 0) {
          resultados.innerHTML = '<p>Nenhum livro encontrado.</p>';
          return;
        }

        data.items.forEach((item, index) => {
          const livro = item.volumeInfo;

          const titulo = livro.title || "Sem título";
          const autores = livro.authors ? livro.authors.join(', ') : "Autor desconhecido";
          const genero = livro.categories ? livro.categories.join(', ') : "Gênero indefinido";
          const editora = livro.publisher || "Editora desconhecida";
          const paginas = livro.pageCount || "";
          const capa = livro.imageLinks?.thumbnail || "";

          const livroDiv = document.createElement('div');
          livroDiv.style.border = "1px solid #ccc";
          livroDiv.style.marginBottom = "10px";
          livroDiv.style.padding = "10px";

          livroDiv.innerHTML = `
            <strong>${titulo}</strong><br>
            <em>${autores}</em><br>
            ${capa ? `<img src="${capa}" alt="${titulo}" style="max-width: 100px;"><br>` : ""}
            <button onclick='preencherCampos(${JSON.stringify({
              titulo,
              autores,
              genero,
              editora,
              paginas,
              capa
            })})'>Selecionar</button>
          `;

          resultados.appendChild(livroDiv);
        });
      })
      .catch(error => {
        console.error('Erro ao buscar livro:', error);
      });
  }

  function preencherCampos(livro) {
    document.getElementById('titulo').value = livro.titulo;
    document.getElementById('autor').value = livro.autores;
    document.getElementById('genero').value = livro.genero;
    document.getElementById('editora').value = livro.editora;
    document.getElementById('total_paginas').value = livro.paginas;
    document.getElementById('capa').value = livro.capa;

    const capaImg = document.getElementById('capa-preview');
    if (livro.capa) {
      capaImg.src = livro.capa;
      capaImg.style.display = 'block';
    }    
    document.getElementById('resultados').innerHTML = '';
  }
</script>
</head>

<body>
  <h1 class="titulo">BOOKLOVER</h1>
  
  <form method="POST" action="cadastrar.php">
  <h2 class="subtitulo">Cadastro de Livros</h2>
    <label>Título: <input type="text" name="titulo" id="titulo" required></label>
    <button type="button" id="buscar" onclick="buscarLivro()">Buscar</button><br><br>
	<div id="resultados" style="margin-top: 20px;"></div>
	
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
