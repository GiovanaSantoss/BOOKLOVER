<?php
require 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_livro = intval($_POST['id_livro']);
    $paginas_lidas = intval($_POST['paginas_lidas']);


    if ($id_livro > 0 && $paginas_lidas >= 0) {
        $stmt = $conn->prepare("INSERT INTO status (id_livro, paginas_lidas, data_leitura) VALUES (?, ?, NOW())");
        $stmt->bind_param("ii", $id_livro, $paginas_lidas);
        if ($stmt->execute()) {
            header('Location: index.php');
            exit();
        } else {
            echo "Erro ao atualizar progresso: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Dados inválidos.";
    }
} else {
    echo "Método inválido.";
}
$conn->close();
?>
