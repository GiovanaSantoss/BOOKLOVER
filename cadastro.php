<?php
session_start();
require 'conexao.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nome = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $senha = trim($_POST['senha']);

    
    $sql = "SELECT id FROM usuarios WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        $_SESSION['mensagem'] = "Esse e-mail já está cadastrado. Faça login!";
        header("Location: login.php");
        exit;
    } else {
        
        $senhaCriptografada = password_hash($senha, PASSWORD_DEFAULT);
        $sql = "INSERT INTO usuarios (nome, email, senha) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $nome, $email, $senhaCriptografada);

        if ($stmt->execute()) {
            $_SESSION['usuario_id'] = $conn->insert_id;
            $_SESSION['usuario_email'] = $email;
            $_SESSION['usuario_nome'] = $nome;

            header("Location: index.php");
            exit;
        } else {
            $_SESSION['mensagem'] = "Erro ao cadastrar. Tente novamente.";
            header("Location: cadastro.php");
            exit;
        }
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Cadastro - BOOKLOVER</title>
  <link href="https://fonts.googleapis.com/css2?family=Dancing+Script&display=swap" rel="stylesheet">
  <link href="css/styleLogin.css" rel="stylesheet">
</head>

<body>
<div class="container">
    <h1 class="titulo">Cadastro</h1>
<?php
if (isset($_SESSION['mensagem'])) {
    echo "
    <div style='
        margin: 20px auto;
        padding: 15px;
        max-width: 400px;
        background-color: #ffe6e6;
        color: #b30000;
        border: 1px solid #ff4d4d;
        border-radius: 10px;
        text-align: center;
        font-family: Arial, sans-serif;
        box-shadow: 0 0 10px rgba(255, 0, 0, 0.2);
    '>
        {$_SESSION['mensagem']}
    </div>";
    unset($_SESSION['mensagem']);
}
?>
		<form action="cadastro.php" method="POST">
		  <label>Nome:</label><br>
		  <input type="text" name="nome" required><br><br>

		  <label>Email:</label><br>
		  <input type="email" name="email" required><br><br>

		  <label>Senha:</label><br>
		  <input type="password" name="senha" required><br><br>

		  <button type="submit" class="botao">Cadastrar</button>
		</form>
		
    <a href="landing.php" class="voltar">← Voltar para o início</a>
  </div>
</body>


