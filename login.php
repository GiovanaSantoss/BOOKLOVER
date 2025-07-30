
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Login - BOOKLOVER</title>
  <link href="https://fonts.googleapis.com/css2?family=Dancing+Script&display=swap" rel="stylesheet">
  <link href="css/styleLogin.css" rel="stylesheet">
  <style>
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
    

  </style>
  
</head>
<body>
  <div class="container">
    <h1 class="nome">BOOKLOVER</h1>
    <h2 class="titulo">LOGIN</h2>
    <form action="validar_login.php" method="POST">
      <label for="email">E-mail:</label>
      <input type="text" name="email" id="email" required>
	
      <label for="senha">Senha:</label>
      <input type="password" name="senha" id="senha" required>

      <button type="submit" class="botao">Entrar</button>
    </form>
    <a href="landing.php" class="voltar">← Voltar para o início</a>
	<?php
	session_start();
	if (isset($_SESSION['mensagem'])) {
		echo "<p style='color: red; text-align: center;'>" . $_SESSION['mensagem'] . "</p>";
		unset($_SESSION['mensagem']);
	}
	?>
  </div>

  <img src="./images/iconelivro.png" alt="Ícone de livro" class="icone-fixo">


</body>
</html>
