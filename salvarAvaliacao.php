<?php
session_start();
require 'conexao.php';

if (!isset($_POST['livro_id'], $_POST['avaliacao'])) {
    echo "dados_invalidos";
    exit;
}

$livroId = (int)$_POST['livro_id'];
$avaliacao = (int)$_POST['avaliacao'];

// Garante que a nota esteja entre 1 e 5
if ($avaliacao < 1 || $avaliacao > 5) {
    echo "avaliacao_invalida";
    exit;
}

// Atualiza a avaliação no banco de dados
$stmt = $conn->prepare("UPDATE books SET avaliacao = ? WHERE id = ?");
$stmt->bind_param("ii", $avaliacao, $livroId);

if ($stmt->execute()) {
    echo "ok";
} else {
    echo "erro_banco";
}

$stmt->close();
$conn->close();
