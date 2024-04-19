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
        <div class="cadastro">
            <form method="POST">
                <h1>Cadastro</h1>
                <p>Informe as informações para cadastro</p>
                Nome: <input type="text" name="nome" required><br>
                E-mail: <input type="email" name="email" required><br>
                Senha: <input type="password" name="senha" required><br>
                <button name="Cadastrar">Cadastrar</button>
            </form>
        </div>
    
        <div class="login">
            <form  method="POST">
                <h1>Login</h1>
                <p>Informe seus dados de login</p>
                 E-mail: <input type="email" name="email" required><br>
                 Senha: <input type="password" name="senha" required><br>
                <a href="formulario_redefinicao.php">Esqueceu sua senha?</a>

                <button name="Entrar">Login</button>
                <a href="../index.html"> Voltar </a>
            </form>
        </div>

        <div class="overlay-container">
            <div class="overlay">
                <div class="overlay-esquerda">
                    <h1>Bem Vindo de Volta</h1>
                    <p>Para entrar informe seus dados de login</p>
                    <button id="login"> Login </button>
                </div>
                <div class="overlay-direita">
                    <h1>Olá Amigo!</h1>
                    <p>Realize seu cadastro para utilizar nossos serviços</p>
                    <button id="cadastro"> Cadastro </button>
                </div>
            </div>
        </div>
    </div>
    <script>
        // Código para fazer a troca de telas no formulário
        const botaoCadastro = document.getElementById('cadastro');
        const botaoLogin = document.getElementById('login');
        const main = document.getElementById('main')

        botaoCadastro.addEventListener('click', () => {
            main.classList.add("right-panel-active");
        });

        botaoLogin.addEventListener('click', () => {
            main.classList.remove("right-panel-active");
        });
    </script>

    <?php
    require_once("../config.php");

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Verifica se o botão "Cadastrar" foi pressionado
        if (isset($_POST['Cadastrar'])) {
            $nome = $_POST["nome"];
            $email = $_POST["email"];
            $senha = password_hash($_POST["senha"], PASSWORD_BCRYPT);

            // Verifica se o e-mail já está cadastrado
            $sql_verifica_email = "SELECT id FROM usuarios WHERE email = :email";
            $stmt_verifica_email = $db->prepare($sql_verifica_email);
            $stmt_verifica_email->bindParam(":email", $email);
            $stmt_verifica_email->execute();

            if ($stmt_verifica_email->rowCount() > 0) {
                echo '<script>alert("E-mail já cadastrado");</script>';
            } else {
                // E-mail não está cadastrado, realiza o cadastro
                $sql = "INSERT INTO usuarios (nome, email, senha) VALUES (:nome, :email, :senha)";
                $stmt = $db->prepare($sql);
                $stmt->bindParam(":nome", $nome);
                $stmt->bindParam(":email", $email);
                $stmt->bindParam(":senha", $senha);

                if ($stmt->execute()) {
                    // Envio de e-mail de confirmação
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
                    $mail->addAddress($email, $nome);
                    $mail->isHTML(true);
                    $mail->Subject = 'Cadastro Realizado com Sucesso';
                    $mail->Body = 'O Cadastro foi realizado com Sucesso, Agradecemos a preferência';
        

                    if ($mail->send()) {
                        echo '<script>alert("Cadastro Realizado com Sucesso, Um email de confirmação foi enviado para você"); window.location.href = "formulario_cliente.php";</script>';
                    } else {
                        echo '<script>alert("Erro ao enviar E-mail de confirmação."); </script>';
                    }
                    // Redirecione o usuário para a página de agendamento
                    exit();
                } else {
                    echo '<script>alert("Erro ao Cadastrar Usuário");</script>';
                }
            }
        }

        // Verifica se o botão "Entrar" foi pressionado
        if (isset($_POST['Entrar'])) {
            $email = $_POST["email"];
            $senha = $_POST["senha"];
        
            $sql = "SELECT id, senha FROM usuarios WHERE email = :email";
            $stmt = $db->prepare($sql);
            $stmt->bindParam(":email", $email);
            $stmt->execute();
            $row = $stmt->fetch();
        
            if ($row && password_verify($senha, $row["senha"])) {
                // Login bem-sucedido
                session_start();
                $_SESSION["usuario_id"] = $row["id"];
                header("Location: agendamento.php");
            } else {
                echo '<script>alert("E-mail ou senha incorretos.");</script>';
            }
        }
    }
    ?>
</body>

</html>