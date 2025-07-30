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
  <style>
    .nome{
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
    .icone-fixo {
      position: fixed;
      bottom: 15px;
      right: 15px;
      height: 80px;
      z-index: 999;
      transition: transform 0.3s;
    }

    .icone-fixo:hover {
      transform: scale(1.1) rotate(3deg);
    }
    .pagina {
        display: flex;
        justify-content: center;
        align-items: center; 
        gap: 40px;
        height: 100vh; 
        padding: 40px;
        box-sizing: border-box;
        margin-right: 30px;
        position: relative;
    }


    .container {
        background-color: #fff8f8;
        padding: 30px;
        border-radius: 15px;
        box-shadow: 0 0 15px rgba(255, 182, 193, 0.3);
        max-width: 400px;   
    }

  .dica-livro {
        position: absolute;
        top: 50%;
        left: 30px;
        transform: translateY(-50%);
        background-color: rgba(237, 51, 175, 0.2);
        padding: 20px;
        border-radius: 15px;
        backdrop-filter: blur(6px);
        box-shadow: 0 0 10px rgba(255, 192, 203, 0.6);
        max-width: 300px;
        text-align: center;
        animation: entrarSuave 1s ease forwards;
    }   


    .dica-livro h3 {
        margin-bottom: 10px;
        font-size: 1.5em;
        color: rgba(211, 23, 164, 0.76);
    }

    .capa-livro {
        width: 150px;
        height: auto;
        border-radius: 10px;
        margin-bottom: 10px;
        box-shadow: 0 0 8px #ffb6c1;
    }

    @keyframes entrarSuave {
    0% {
        opacity: 0;
        transform: translateY(0px);
    }
    100% {
        opacity: 1;
        transform: translateY(-200px );
    }
    }
    
  </style>
</head>

<body>
    <h1 class="nome">BOOKLOVER</h1>

  <div class="dica-livro">
        <h3>Dica de Romance 💘</h3>
        <img src="./images/eueessemeucoracao.png" alt="Capa do livro Eu e esse meu coração" class="capa-livro">
        <p><strong>Eu e esse meu coração</strong><br>C. C. Hunter</p>
        <p>★★★★★</p>
  </div>

<div class="pagina">
  <div class="container">
    <h2 class="titulo">Cadastro</h2>
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
    
</div> 
    <img src="./images/iconelivro.png" alt="Ícone de livro" class="icone-fixo">
</body>


