
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Login - BOOKLOVER</title>
  <link href="https://fonts.googleapis.com/css2?family=Dancing+Script&display=swap" rel="stylesheet">
  <link href="css/styleLogin.css" rel="stylesheet">
  
</head>
<body>
  <div class="container">
    <h1 class="titulo">LOGIN</h1>
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
</body>
</html>
