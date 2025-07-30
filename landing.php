<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>BOOKLOVER</title>
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
  	  background-position: center;
      background: linear-gradient(-45deg, #ff9a9e, #fad0c4, #fbc2eb, #a6c1ee);
      background-size: hover;
    }

    .container {
      text-align: center;
    }

    .titulo {
      font-size: 6em;
      font-family: 'Dancing Script', cursive;
      color: #FAEBD7;
      margin-bottom: 10px;
	    text-shadow: 0 0 8px #FFB6C1, 0 0 15px #FF69B4, 0 0 25px #FF1493;
      transition: all 0.3s ease-in-out;
      cursor: default;
    }

    .titulo:hover {
      text-shadow: 0 0 12px #FFB6C1, 0 0 25px #FF69B4, 0 0 40px #FF1493, 0 0 60px #FF1493;
      color: #ffffff;
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
      color: white;
      border-radius: 8px;
      transition: background 0.3s;
    }

    .botao:hover {
      background-color: #FAEBD7;
	  color: #FF1493;
    }

    .icone {
      width: 40px;
      margin: 10px;
      filter: drop-shadow(0 0 5px pink);
      transition: transform 0.2s;
    }
    .icone:hover {
      transform: scale(1.2);
    }

  

  </style>
</head>
<body>
  <div class="container">
    <h1 class="titulo">BOOKLOVER</h1>
    <p class="frase">“This is our place, we make the rules!”</p>
    <div class="botoes">
      <a href="login.php" class="botao">Login</a>
      <a href="cadastro.php" class="botao">Cadastre-se</a>
    </div>
    <div class="icones">
      <a href="https://open.spotify.com/intl-pt/artist/06HL4z0CvFAxyc27GXpf02?si=TIAegWSPSWatr1IxdX80Lwz" target="_blank">
        <img src="https://cdn-icons-png.flaticon.com/512/174/174872.png" alt="Spotify" class="icone">
      </a>
    </div>
  </div>


</body>
</html>
