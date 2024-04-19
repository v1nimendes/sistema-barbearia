<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <link rel="stylesheet" href="style.css">
    <title>Redefinir Senha</title>
    <style>
        body {
            background-image: url(../FundoTrabalho.jpg)
        }

        h1 {
            text-align: center;
            font-weight: bold;
            margin-top: 15px;
            color: #000000
        }

        p {
            text-align: center;
            font-size: 16px;
            margin-top: 1px;
            color: #000000
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
            color: #000000
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
    </style>
</head>

<body>
    <img src="../costelinhapng.png">
    <form action="includes/processa_redefinicao_da_senha.inc.php" method="post">
    <h1>Redefinir Senha</h1>
        <?php
        if (isset($_GET['email'])) {
            $email = $_GET['email'];
            echo '<p>Redefina sua senha para o email: ' . $email . '</p>';
        } else {
            echo '<script>alert("E-mail não fornecido para redefinição!"); window.location.href = "redefinicao.php";</script>';
        }
        ?>


        <input type="hidden" name="email" value="<?php echo $email; ?>">

        <label for="nova_senha">Nova Senha:</label>
        <input type="password" name="nova_senha" required><br>

        <input type="submit" value="Redefinir Senha">
    </form>
</body>

</html>
