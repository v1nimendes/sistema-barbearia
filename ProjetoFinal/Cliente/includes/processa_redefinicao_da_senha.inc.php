<?php
// Inclua o arquivo que contém a conexão PDO
require_once("../../config.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $nova_senha = $_POST["nova_senha"];

    // Verifique se a conexão foi estabelecida com sucesso
    if (!$db) {
        die("Conexão ao banco de dados falhou.");
    }

    // Hash da nova senha
    $senha_hash = password_hash($nova_senha, PASSWORD_DEFAULT);

    // Construa a consulta SQL para atualizar a senha
    $sql = "UPDATE usuarios SET senha = '$senha_hash' WHERE email = '$email'";

    if ($db->exec($sql) !== FALSE) {
        echo '<script>alert("Senha alterada com Sucesso. Clique em OK para voltar à tela de login."); window.location.href = "../formulario_cliente.php";</script>';
    } else {
        '<script>alert("Erro ao alterar a senha. Clique em OK para voltar à tela de redefinição."); window.location.href = "../redefinicao.php";</script>';
    }
}
?>
