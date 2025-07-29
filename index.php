<?php

require 'conexao.php';

$sql = "SELECT * FROM books ORDER BY data_cadastro DESC";
$result = $conn->query($sql);

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
    <title>Lista de Livros</title>
	<link rel="stylesheet" href="style.css">
	<link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">


</head>
<body>
    
	<h2>Meus Livros</h2>
	
	<a href="cadastrar.php">Cadastrar novo livro</a>
	
	<?php
    function exibirlivros($conn, $tituloSessao, $livro) {
       
            echo "<section>";
            echo "<h3>$tituloSessao</h3>";
            echo "<div style='display: grid; grid-template-columns: repeat(2, 1fr); gap: 10px;'>";

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
						padding: 0;
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
				
				 if ($tituloSessao === '📖 Lendo') {
               
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
			echo "<p style='color: #d35477; font-style: italic; font-family: \"Poppins\", sans-serif; font-size: 15px; font-weight: bold;'>";
			echo "Ops, ainda não tem nenhum livro por aqui... Que tal adicionar um? 💖";
			echo "</p>";
			echo "</div>";
		}
            echo "</div>";
            echo "</section>";
        
    }
	?>

    <div class="container-livros">
<?php

    exibirlivros($conn, "📚 TBR", $queroLer);
    exibirlivros($conn, "📖 Lendo", $lendo);
    exibirlivros($conn, "✅ Lidos", $lidos);
?>
	</div>

	
	
</body>
</html>
