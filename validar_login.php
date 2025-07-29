
<?php
session_start();
require 'conexao.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    // Busca o usuário pelo e-mail
    $sql = "SELECT * FROM usuarios WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        $usuario = $resultado->fetch_assoc();

 
        if (password_verify($senha, $usuario['senha'])) {
           
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['usuario_email'] = $usuario['email'];
            $_SESSION['usuario_nome'] = $usuario['nome'];

            header("Location: index.php");
            exit;
        } else {
            // Senha incorreta
            $_SESSION['mensagem'] = "Senha incorreta. Tente novamente.";
            header("Location: login.php");
            exit;
        }
    } else {
        // E-mail não encontrado - redireciona para cadastro
        $_SESSION['mensagem'] = "E-mail não encontrado. Faça seu cadastro!";
        header("Location: cadastro.php");
        exit;
    }

    $stmt->close();
}
$conn->close();
?>
