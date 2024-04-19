<?php
require_once("../../config.php");
session_start();

if (!isset($_SESSION["usuario_id"])) {
    header("Location: index.php");
    exit;
}

if (isset($_GET["id"])) {
    $usuario_id = $_SESSION["usuario_id"];
    $agendamento_id = $_GET["id"];

    // Verifique se o agendamento pertence ao usuário logado
    $sql = "SELECT * FROM agendamentos WHERE usuario_id = :usuario_id AND id = :agendamento_id";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(":usuario_id", $usuario_id);
    $stmt->bindParam(":agendamento_id", $agendamento_id);
    $stmt->execute();
    $agendamento = $stmt->fetch();

    if ($agendamento) {
        // Remova o agendamento do banco de dados
        $sql = "DELETE FROM agendamentos WHERE id = :agendamento_id";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(":agendamento_id", $agendamento_id);
        $stmt->execute();

        header("Location: ../agendamento.php");
        exit;
    } else {
        echo "Você não tem permissão para cancelar este agendamento.";
    }
} else {
    echo "ID de agendamento não especificado.";
}
?>
