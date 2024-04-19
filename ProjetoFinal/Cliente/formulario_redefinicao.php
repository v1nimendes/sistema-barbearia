<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Costelinha Barber Shop</title>
</head>

<body>
    <img src="../costelinhapng.png">

    <div class="container" id="main">
        <div class="login">
            <form method="POST">
                <h1>Redefinir Senha</h1>
                <p>Informe seu email para redefinir a senha</p>
                <input type="email" name="email" placeholder="Email" required>
                <button name="Entrar">Enviar</button>
                <a href="formulario_cliente.php">Voltar para tela de login</a>
            </form>
        </div>

        <div class="overlay-container">
            <div class="overlay">
                <div class="overlay-direita">
                </div>
            </div>
        </div>
    </div>
    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $email = $_POST["email"];

        // Verificar se o e-mail existe no banco de dados
        require_once("../config.php"); // Certifique-se de incluir seu arquivo de configuração com a conexão ao banco de dados

        $sql = "SELECT id FROM usuarios WHERE email = :email";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(":email", $email);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$result) {
            // Se o e-mail não existir no banco de dados, exiba um alert e retorne para a página de redefinição
            echo '<script>alert("O e-mail fornecido não está registrado.");</script>';
            exit; // Encerre o script se o e-mail não existir
        }

        require 'PHPMailer/src/PHPMailer.php';
        require 'PHPMailer/src/SMTP.php';

        $mail = new PHPMailer\PHPMailer\PHPMailer();
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // Altere para o servidor de e-mail desejado
        $mail->SMTPAuth = true;
        $mail->Username = 'gean.js@aluno.ifsc.edu.br'; // Seu endereço de e-mail
        $mail->Password = '17042001Antonia'; // Sua senha
        $mail->SMTPSecure = 'ssl'; // Use 'tls' para TLS, 'ssl' para SSL
        $mail->Port = 465; // Porta SMTP (SSL - 465, TLS - 587)

        $mail->setFrom('gean.js@aluno.ifsc.edu.br', 'Costelinha Barber Shop');
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->Subject = 'Redefinicao de Senha';
        $mail->Body = 'Clique no link abaixo para redefinir a sua senha: <a href="http://localhost/ProjetoFinal/Cliente/redefine_senha.php?email=' . $email . '">Redefinir Senha</a>';

        if ($mail->send()) {
            echo '<script>alert("Enviamos o formulário de redefinição de senha para seu endereço de e-mail.");</script>';
        } else {
            echo '<script>alert("Erro ao enviar email de redefinição."); </script>';
        }
    }
?>
</body>

</html>