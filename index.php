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
		  font-size: 3.5em;
		  font-family: 'Dancing Script', cursive;
		  color: #FAEBD7;
		  text-shadow: 0 0 8px #FFB6C1, 0 0 15px #FF69B4, 0 0 25px #FF1493;
		  margin-top: 20px;
		  text-align: center;
		  top: 50px;
		  left: 45%;
		  margin: 0; 
		  cursor: pointer;
		}
		.titulo:hover{
			 text-shadow: 0 0 18px #FFB6C1, 0 0 35px #FF69B4, 0 0 50px #FF1493, 0 0 70px #FF1493;
		     color: #ffffff;
		}
		.saudacao{
			font-family: 'Montserrat', sans-serif;
			font-size: 16px;
		}
		.layout-livros {
			display: flex;
			gap: 20px;
			align-items: flex-start;
			padding: 20px;
			box-sizing: border-box;
		}
		.menu-lateral {
			width: 180px;
			display: flex;
			flex-direction: column;
			gap: 10px;
			margin-top: 20px;
		}
		.menu-lateral .botao-secao {
			width: 100%;
			padding: 12px;
			border-radius: 25px;
			background-color: #f7a4c1;
			color: white;
			border: none;
			font-weight: bold;
			cursor: pointer;
			transition: background-color 0.3s ease;
			font-size: 14px;
			text-align: left;
		}
		.menu-lateral .botao-secao:hover {
			background-color: #e1739a;
		}
		.container-livros {
			flex: 1;
			display: flex;
			flex-direction: column;
			background: #fff8f8cc;
			padding: 20px;
			border-radius: 15px;
			box-shadow: 0 0 15px rgba(0,0,0,0.05);
			background-color: 	#D8A7B1;
		}
		.secao-livros section > div {
			display: grid;
			grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
			gap: 20px;
		}
		.secao-livros {
			display: none;
		}
		.secao-livros.ativa {
			display: block;
		}
		.botao-secao.ativo {
			background-color: #e1739a;
			box-shadow: 0 0 5px #e1739a;
		}
		.estrelas {
			display: inline-flex;
			justify-content: center;
			margin-top: 10px;
			cursor: pointer;
		}

		.estrela {
			font-size: 22px;
			color: #ccc;
			transition: color 0.2s;
		}

		.estrela.ativa {
			color: #f39c12;
		}
		.estrelas .estrela:hover,
		.estrelas .estrela:hover ~ .estrela {
			color: gold;
		}
		
		.btn-resenha {
			background: none;
			border: none;
			cursor: pointer;
			font-size: 15px;
			margin-left: 8px;
			transition: transform 0.2s;
		}

		.btn-resenha:hover {
			transform: scale(1.1);
		}



	</style>
</head>
<body>
    <div class="saudacao">
		<h2>Olá, <?= htmlspecialchars($usuarioNome) ?>! 💕</h2>
		<a href="cadastrar.php" style="text-decoration: none; color: #d35477; margin-bottom:13px;">+ Adicionar novo livro</a> |
		<a href="logout.php" style="text-decoration: none; color: #888;">Sair</a>
	</div>
	
	<div class="layout-livros">
	 <div class="menu-lateral">
		<button class="botao-secao ativo" data-id="tbr" style="font-family: ">TBR (To Be Read)</button>
		<button class="botao-secao" data-id="lendo">Lendo</button>
		<button class="botao-secao" data-id="lidos">Lidos</button>									
	</div>

	<div style="text-align: center;">
		 <a href="landing.php" title="Página Inicial"><h1 class="titulo">BOOKLOVER</h1></a> 
	</div>
	
    <div class="container-livros">
        <?php
        function exibirlivros($conn, $tituloSessao, $livro) {
            echo "<section>";
            echo "<h3>$tituloSessao</h3>";
            echo "<div style='display: grid; grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); gap: 10px;'>";

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

                    if ($tituloSessao === 'Lendo') {
                        $stmt = $conn->prepare("SELECT MAX(paginas_lidas) as paginas_lidas FROM status WHERE id_livro = ?");
                        $stmt->bind_param("i", $livro['id']);
                        $stmt->execute();
                        $res = $stmt->get_result()->fetch_assoc();
                        $paginasLidas = $res['paginas_lidas'] ?? 0;
                        $stmt->close();

                        $totalPaginas = (int)$livro['total_paginas'];
                        $percent = $totalPaginas > 0 ? round(($paginasLidas / $totalPaginas) * 100) : 0;

                        echo "<div style='margin:10px 0;'>";
                        echo "<label>Páginas: $paginasLidas / $totalPaginas Progresso ($percent%)</label><br>";
                        echo "<progress value='$paginasLidas' max='$totalPaginas' style='width:100%;'></progress>";
                        echo "</div>";

                        echo "<form method='POST' action='atualizar_progresso.php'>";
                        echo "<input type='hidden' name='id_livro' value='" . $livro['id'] . "'>";
                        echo "<input type='number' name='paginas_lidas' min='$paginasLidas' max='$totalPaginas' placeholder='Páginas lidas' required style='width:60%;'>";
                        echo "<button type='submit'>Atualizar</button>";
                        echo "</form>";
                    }

                   
					if ($tituloSessao === 'Lidos') {
						$livroId = $livro['id'];
						$avaliacaoAtual = $livro['avaliacao'] ?? 0;

						echo "<div class='estrelas' data-id='$livroId'>";
						for ($i = 1; $i <= 5; $i++) {
							$valorEstrela = 6 - $i; 
							$classe = $valorEstrela <= $avaliacaoAtual ? 'estrela ativa' : 'estrela';
							echo "<span class='$classe' data-valor='$valorEstrela'>&#9733;</span>";
						}
						echo "</div>";
						
						$resenha = htmlspecialchars($livro['resenha'] ?? '', ENT_QUOTES, 'UTF-8'); 
						echo "<button class='btn-resenha' data-livro-id='$livroId' data-resenha=\"$resenha\" title='Escrever resenha'>✏️</button>";

					}
					 echo "</div>";
                } 
            } else {
               echo "<div style='grid-column: 2 / span 3; display: flex; justify-content: center; align-items: center; height: 100%; min-height: 200px; text-align: center;'>";
				echo "<p style='color: #d35477; font-style: italic; font-family: \"Poppins\", sans-serif; font-size: 15px; font-weight: bold; max-width: 300px;'>Ops, ainda não tem nenhum livro por aqui... Que tal adicionar um? 💖</p>";
				echo "</div>";

            }

            echo "</div>";
            echo "</section>";
        }

?>
        <div id="tbr" class="secao-livros">
			<?php exibirlivros($conn, "TBR (To Be Read)", $queroLer); ?>
		</div>

		<div id="lendo" class="secao-livros">
			<?php exibirlivros($conn, "Lendo", $lendo); ?>
		</div>

		<div id="lidos" class="secao-livros">
			<?php exibirlivros($conn, "Lidos", $lidos); ?>
		</div>
		
	</div>
	
	<script>
		function mostrarSecao(id) {
		const secoes = document.querySelectorAll('.secao-livros');
		secoes.forEach(secao => secao.classList.remove('ativa'));

		const ativa = document.getElementById(id);
		if (ativa) {
			ativa.classList.add('ativa');
		}
		
		const botoes = document.querySelectorAll('.botao-secao');
		botoes.forEach(botao => botao.classList.remove('ativo'));

		const botaoClicado = Array.from(botoes).find(b => b.getAttribute('data-id') === id);
		if (botaoClicado) botaoClicado.classList.add('ativo');
	}
	
	window.onload = () => {
		mostrarSecao('tbr');
	};
	
	document.querySelectorAll('.botao-secao').forEach(botao => {
		botao.addEventListener('click', () => {
			mostrarSecao(botao.getAttribute('data-id'));
		});
	});

	</script>
	<script>
	document.querySelectorAll('.estrelas').forEach(container => {
		const spans = container.querySelectorAll('.estrela');

		spans.forEach(span => {
			span.addEventListener('click', () => {
				const valor = span.getAttribute('data-valor');
				const livroId = container.getAttribute('data-id');

				fetch('salvarAvaliacao.php', {
					method: 'POST',
					headers: {
						'Content-Type': 'application/x-www-form-urlencoded'
					},
					body: `livro_id=${livroId}&avaliacao=${valor}`
				})
				.then(response => response.text())
				.then(resposta => {
					if (resposta === 'ok') {
						spans.forEach(s => {
							const val = s.getAttribute('data-valor');
							s.classList.toggle('ativa', val <= valor);
						});
					} else {
						alert("Erro ao salvar avaliação");
					}
				});
			});
		});
	});
	</script>
	
	<div id="modal-resenha" style="display: none; position: fixed; top: 30%; left: 50%; transform: translate(-50%, -50%); background: white; padding: 20px; border: 1px solid #ccc; z-index: 999;">
	<h3>Escreva sua resenha</h3>
	<form id="form-resenha" method="POST" action="salvarResenha.php">
		<input type="hidden" name="livro_id" id="livro-id">
		<textarea name="resenha" id="resenha-texto" rows="5" cols="40" placeholder="Digite aqui..."></textarea><br>
		<button type="submit">Salvar</button>
		<button type="button" onclick="fecharModal()">Cancelar</button>
	</form>
</div>

	<script>
	let livroSelecionado = null;

	document.querySelectorAll('.btn-resenha').forEach(btn => {
		btn.addEventListener('click', () => {
			const livroSelecionado = btn.dataset.livroId;
			const resenha = btn.dataset.resenha || '';
			document.getElementById('livro-id').value = livroSelecionado;
			document.getElementById('resenha-texto').value = resenha;
			document.getElementById('modal-resenha').style.display = 'block';
		});
	});

	function fecharModal() {
		document.getElementById('modal-resenha').style.display = 'none';
		document.getElementById('resenha-texto').value = '';
	}

	</script>

</body>
</html>
