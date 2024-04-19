<?php
session_start();
require_once("../../config.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $novoProfissional = $_POST["novo_profissional"];

    // Realize a inserção na tabela de profissionais (substitua 'nome_profissional' pelo seu campo de nome na tabela)
    $sql = "INSERT INTO profissionais (nome) VALUES (:nome)";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(":nome", $novoProfissional);
    $stmt->execute();

    header("Location: ../formulario_controle_gerencia.php"); // Redireciona de volta à página de gerenciamento após adicionar
    exit;
}
?>
