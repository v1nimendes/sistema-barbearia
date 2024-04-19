<?php
session_start();

// Verifica se os campos de usuário e senha foram submetidos
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["username"]) && isset($_POST["password"])) {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Verifica se as credenciais são válidas
    if ($username === "admin" && $password === "admin") {
        $_SESSION["usuario_id"] = 1; // Defina o ID do usuário
        $_SESSION["tipo_usuario"] = "admin"; // Defina o tipo de usuário
        header("Location: formulario_controle_gerencia.php");
        exit;
    } else {
        $error = '<script>alert("Login ou senha inválidos!");</script>';
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <link rel="stylesheet" href="style.css">
    <title>Login Automático</title>
    <style>
        body {
            background-image: url(../FundoTrabalho.jpg);
        }

        h1 {
            text-align: center;
            font-weight: bold;
            margin-top: 15px;
            color: #000000;
        }

        form {
            align-items: center;
            display: flex;
            justify-content: center;
            flex-direction: column;
            font-family: 'monserrat', sans-serif;
            min-height: 100%;
            margin: 1%;
            border-radius: 15px;
        }

        label {
            display: block;
            font-weight: bold;
            margin-top: 5px;
            color: #000000;
        }

        input {
            width: 100%;
            padding: 12px 15px;
            margin-bottom: 15px;
            border-radius: 5px;
            border: 1px solid #ccc;
            outline: none;
        }

        input[type="submit"] {
            color: #fff;
            background: #ff4b2b;
            font-size: 16px;
            font-weight: bold;
            padding: 12px 0;
            border-radius: 20px;
            border: 1px solid #ff4b2b;
            outline: none;
            letter-spacing: 1px;
            text-transform: uppercase;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        input[type="submit"]:hover {
            background: #ff5e40;
        }

        img {
            max-width: 350px;
            max-height: 200px;
        }
        a {
            margin-bottom: 50px
        }
    </style>
</head>

<body>
    <img src="../costelinhapng.png">
    <form method="post" action="">
        <h1>Login Automático</h1>
        <?php if (isset($error)) { ?>
            <p style="text-align: center; color: #ff4b2b;"><?php echo $error; ?></p>
        <?php } ?>
        <label for="username">Nome de Usuário:</label>
        <input type="text" name="username" id="username" required>

        <label for="password">Senha:</label>
        <input type="password" name="password" id="password" required>

        <input type="submit" value="Login">
        <a href="../index.html"> Voltar </a>
    </form>
</body>

</html>
