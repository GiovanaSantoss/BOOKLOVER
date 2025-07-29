<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Minha Biblioteca</title>
  <link href="https://fonts.googleapis.com/css2?family=Dancing+Script&display=swap" rel="stylesheet">
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      background-color: #FFD1DC;
      font-family: 'Georgia', serif;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
	  background-image: url('images/imagemfundo.png');
	  background-repeat: no-repeat;
	  background-size: cover;
	  background-position: center;
    }

    .container {
      text-align: center;
    }

    .titulo {
      font-size: 4.3em;
      font-family: 'Dancing Script', cursive;
      color: #FAEBD7;
      margin-bottom: 10px;
	  text-shadow: 0 0 8px #FFB6C1, 0 0 15px #FF69B4, 0 0 25px #FF1493;
    }

    .frase {
      font-size: 1.2em;
      color: #555;
      margin-bottom: 30px;
    }

    .botoes {
      display: flex;
      justify-content: center;
      gap: 20px;
    }

    .botao {
      padding: 10px 25px;
      font-size: 1.3em;
      text-decoration: none;
      background-color: ;
      color: white;
      border-radius: 8px;
      transition: background 0.3s;
    }

    .botao:hover {
      background-color: #FAEBD7;
	  color: #FF1493;
    }
  </style>
</head>
<body>
  <div class="container">
    <h1 class="titulo">BOOKLOVER</h1>
    <p class="frase">“Here's our place, we make the rules!”</p>
    <div class="botoes">
      <a href="login.php" class="botao">Login</a>
      <a href="cadastro.php" class="botao">Cadastre-se</a>
    </div>
  </div>
</body>
</html>
