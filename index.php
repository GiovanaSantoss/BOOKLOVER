<?php
session_start();
require 'conexao.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

$usuarioId = $_SESSION['usuario_id'];
$usuarioNome = $_SESSION['usuario_nome'];

$sql = "SELECT books.*, status.status 
        FROM books 
        JOIN status ON books.id = status.id_livro 
        WHERE books.usuario_id = ? 
        ORDER BY books.data_cadastro DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $usuarioId);
$stmt->execute();
$result = $stmt->get_result();

$queroLer = [];
$lendo = [];
$lidos = [];

if ($result && $result->num_rows > 0) {
    while ($livro = $result->fetch_assoc()) {
        $status = strtolower($livro['status']); 
        if ($status == 'quero ler') {
            $queroLer[] = $livro;
        } elseif ($status == 'lendo') {
            $lendo[] = $livro;
        } elseif ($status == 'lido') {
            $lidos[] = $livro;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Meus Livros - BOOKLOVER</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Dancing+Script&display=swap" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Montserrat&display=swap" rel="stylesheet">

	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<style>
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
		.saudacao{
			font-family: 'Montserrat', sans-serif;
			font-size: 16px;
		}

	</style>
</head>
<body>
    <div class="saudacao">
		<h2>Olá, <?= htmlspecialchars($usuarioNome) ?>! 💕</h2>
		<a href="cadastrar.php" style="text-decoration: none; color: #d35477; margin-bottom:13px;">+ Adicionar novo livro</a> |
		<a href="logout.php" style="text-decoration: none; color: #888;">Sair</a>
	</div>

	<div style="text-align: right;">
		<h1 class="titulo">BOOKLOVER</h1>
	</div>
	

    <div class="container-livros">
        <?php
        function exibirlivros($conn, $tituloSessao, $livro) {
            echo "<section>";
            echo "<h3>$tituloSessao</h3>";
            echo "<div style='display: grid; grid-template-columns: repeat(2, 1fr); gap: 10px;' background-color: #FFC0CB;>";

            if (!empty($livro)) {
                foreach ($livro as $livro) {
                    echo "<div style='position: relative; border:1px solid #ccc; border-radius:10px; padding:5px; width:180px; text-align:center;'>";

                    echo "<a href='excluir.php?id={$livro['id']}' 
                            onclick='return confirm(\"Tem certeza que quer excluir esse livro?\")' 
                            style='
                                position: absolute;
                                top: 5px;
                                right: 5px;
                                color: red;
                                font-weight: bold;
                                font-size: 15px;
                                text-decoration: none;
                                cursor: pointer;
                                background-color: pink;
                                border-radius: 50%;
                                width: 20px;
                                height: 20px;
                                line-height: 20px;
                                text-align: center;
                            '
                            title='Excluir livro'
                        >×</a>";

                    if (!empty($livro['capa'])) {
                        echo "<img src='" . htmlspecialchars($livro['capa']) . "' alt='Capa de {$livro['titulo']}' style='width:120px; height:auto; margin-bottom:10px;'>";
                    } else {
                        echo "<div style='width:120px; height:180px; background:#eee; display:flex; align-items:center; justify-content:center; margin:0 auto 10px auto;'>Sem capa</div>";
                    }

                    echo "<div><strong>" . htmlspecialchars($livro['titulo']) . "</strong></div>";
                    echo "<div>Autor: " . htmlspecialchars($livro['autor']) . "</div>";
                    echo "<div>Páginas: " . htmlspecialchars($livro['total_paginas']) . "</div>";

                    if ($tituloSessao === ' Lendo') {
                        $stmt = $conn->prepare("SELECT MAX(paginas_lidas) as paginas_lidas FROM status WHERE id_livro = ?");
                        $stmt->bind_param("i", $livro['id']);
                        $stmt->execute();
                        $res = $stmt->get_result()->fetch_assoc();
                        $paginasLidas = $res['paginas_lidas'] ?? 0;
                        $stmt->close();

                        $totalPaginas = (int)$livro['total_paginas'];
                        $percent = $totalPaginas > 0 ? round(($paginasLidas / $totalPaginas) * 100) : 0;

                        echo "<div style='margin:10px 0;'>";
                        echo "<label>Progresso: $paginasLidas / $totalPaginas páginas ($percent%)</label><br>";
                        echo "<progress value='$paginasLidas' max='$totalPaginas' style='width:100%;'></progress>";
                        echo "</div>";

                        echo "<form method='POST' action='atualizar_progresso.php'>";
                        echo "<input type='hidden' name='id_livro' value='" . $livro['id'] . "'>";
                        echo "<input type='number' name='paginas_lidas' min='$paginasLidas' max='$totalPaginas' placeholder='Páginas lidas' required style='width:60%;'>";
                        echo "<button type='submit'>Atualizar</button>";
                        echo "</form>";
                    }

                    echo "</div>";
                }
            } else {
                echo "<div style='grid-column: 1 / span 2; display: flex; justify-content: center; align-items: center; height: 200px;'>";
                echo "<p style='color: #d35477; font-style: italic; font-family: \"Poppins\", sans-serif; font-size: 15px; font-weight: bold;'>Ops, ainda não tem nenhum livro por aqui... Que tal adicionar um? 💖</p>";
                echo "</div>";
            }

            echo "</div>";
            echo "</section>";
        }

        exibirlivros($conn, " TBR", $queroLer);
        exibirlivros($conn, " Lendo", $lendo);
        exibirlivros($conn, " Lidos", $lidos);
        ?>
    </div>
</body>
</html>
