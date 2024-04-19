<?php
//esse é apenas para o gerenciamento_horarios
session_start();

if (!isset($_SESSION["usuario_id"])) {
    header("Location: index.php");
    exit;
}

if (isset($_GET["id"])) {
    require_once("../../config.php");

    $agendamento_id = $_GET["id"];

    // Implemente a lógica para remover o agendamento com base no ID fornecido
    $sql = "DELETE FROM agendamentos WHERE id = :agendamento_id";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(":agendamento_id", $agendamento_id);
    $stmt->execute();

    // Redireciona de volta para a página principal após cancelar o agendamento
    header("Location: ../formulario_controle_gerencia.php");
    exit;
} else {
    // Caso não seja fornecido um ID, redireciona de volta para a página principal
    header("Location: formulario_controle_gerencia.php");
    exit;
}
?>
