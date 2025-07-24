<?php
require 'conexao.php';

$sql = "SELECT * FROM livros ORDER BY data_cadastro DESC";
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
	<link href="https://fonts.googleapis.com/css2?family=Dancing+Script&display=swap" rel="stylesheet">

</head>
<body>
    <h1>BOOKLOVER</h1>
	<h2>Meus Livros</h2>
	
	<a href="cadastrar.php">Cadastrar novo livro</a>
	
	<?php
    function exibirLivros($tituloSessao, $livros) {
        if (!empty($livros)) {
            echo "<section>";
            echo "<h3>$tituloSessao</h3>";
            echo "<ul>";
            foreach ($livros as $livro) {
                echo "<li>";
                echo "<strong>" . htmlspecialchars($livro['titulo']) . "</strong> - ";
                echo "Autor: " . htmlspecialchars($livro['autor']) . " - ";
                echo "Páginas: " . $livro['total_paginas'] . " ";
                echo "<a href='excluir.php?id={$livro['id']}' onclick='return confirm(\"Tem certeza que quer excluir esse livro?\")'>🗑️ </a> ";
                echo "<a href='alterar.php?id={$livro['id']}'>✏️ </a>";
                echo "</li>";
            }
            echo "</ul>";
            echo "</section>";
        }
    }
	?>

    <div class="container-livros">
<?php
    exibirLivros("📚 Quero Ler", $queroLer);
    exibirLivros("📖 Lendo", $lendo);
    exibirLivros("✅ Lidos", $lidos);
?>
	</div>

<?php
    if (empty($queroLer) && empty($lendo) && empty($lidos)) {
        echo "<p>Nenhum livro cadastrado ainda.</p>";
    }
?>
	
	
	

</body>
</html>
