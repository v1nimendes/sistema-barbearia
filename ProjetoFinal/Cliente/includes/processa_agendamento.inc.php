<?php
require_once("../../config.php");

session_start();

if (!isset($_SESSION["usuario_id"])) {
    header("Location: index.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario_id = $_SESSION["usuario_id"];
    $profissional_id = $_POST["profissional"];
    $tipo_servico = $_POST["tipo_servico"];
    $data = $_POST["data"];
    $hora = $_POST["hora"];

    $agendamento_datetime = $data . ' ' . $hora;

    // Verificar se o horário já foi agendado
    $sql = "SELECT COUNT(*) as count FROM agendamentos WHERE profissional_id = :profissional_id AND horario = :horario";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(":profissional_id", $profissional_id);
    $stmt->bindParam(":horario", $agendamento_datetime);
    $stmt->execute();
    $result = $stmt->fetch();

    if ($result['count'] == 0) {
        // Atualizar a disponibilidade do horário
        $sql = "UPDATE horarios_disponiveis SET disponivel = 0 WHERE profissional_id = :profissional_id AND horario = :horario";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(":profissional_id", $profissional_id);
        $stmt->bindParam(":horario", $agendamento_datetime);
        $stmt->execute();

        // Inserir o agendamento na tabela de agendamentos
        $sql = "INSERT INTO agendamentos (usuario_id, profissional_id, tipo_servico, horario) VALUES (:usuario_id, :profissional_id, :tipo_servico, :horario)";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(":usuario_id", $usuario_id);
        $stmt->bindParam(":profissional_id", $profissional_id);
        $stmt->bindParam(":tipo_servico", $tipo_servico);
        $stmt->bindParam(":horario", $agendamento_datetime);

        if ($stmt->execute()) {
            // Obtendo o último ID inserido (ID do agendamento)
             $ultimo_id = $db->lastInsertId();

             $sql = "SELECT agendamentos.*, usuarios.nome AS nome_usuario, profissionais.nome AS nome_profissional
             FROM agendamentos 
             INNER JOIN usuarios ON agendamentos.usuario_id = usuarios.id 
             INNER JOIN profissionais ON agendamentos.profissional_id = profissionais.id 
             WHERE agendamentos.id = :ultimo_id";
            $stmt = $db->prepare($sql);
            $stmt->bindParam(":ultimo_id", $ultimo_id);
            $stmt->execute();
            $novo_agendamento = $stmt->fetch(PDO::FETCH_ASSOC);

            $mensagem_email = "Olá " . $novo_agendamento['nome_usuario'] . ",<br><br>";
            $mensagem_email .= "Seu agendamento foi realizado com sucesso!<br><br>";
            $mensagem_email .= "Detalhes do Agendamento:<br>";
            $mensagem_email .= "ID do Agendamento: " . $novo_agendamento['id'] . "<br>";
            $mensagem_email .= "Nome do Profissional: " . $novo_agendamento['nome_profissional'] . "<br>";
            $mensagem_email .= "Tipo de Serviço: " . $novo_agendamento['tipo_servico'] . "<br>";
            
            // Formate o horário usando a função date()
            $horario_formatado = date("d/m/Y H:i", strtotime($novo_agendamento['horario']));
            $mensagem_email .= "Horário: " . $horario_formatado . "<br>";
            

            
            // Obtenha os dados do usuário para enviar o e-mail
            $sql = "SELECT email, nome FROM usuarios WHERE id = :usuario_id";
            $stmt = $db->prepare($sql);
            $stmt->bindParam(":usuario_id", $usuario_id);
            $stmt->execute();
            $result = $stmt->fetch();

            // Variáveis para enviar o e-mail
            $email = $result['email']; // Defina corretamente
            $nome = $result['nome']; // Defina corretamente

            // Configuração e envio do e-mail
            require '../PHPMailer/src/PHPMailer.php';
            require '../PHPMailer/src/SMTP.php';

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
            $mail->Subject = 'Agendamento realizado com Sucesso';
            $mail->Body = $mensagem_email;
            if ($mail->send()) {
                echo '<script>alert("Agendamento Realizado com Sucesso, Um email de confirmação foi enviado para você"); window.location.href = "../agendamento.php";</script>';
            } else {
                echo "Erro ao enviar e-mail de confirmação: " . $mail->ErrorInfo;
            }
            
        } else {
            echo "Erro ao agendar horário.";
        }
    } else {
        echo "Este horário já foi agendado por outro usuário.";
    }
    
}
?>
