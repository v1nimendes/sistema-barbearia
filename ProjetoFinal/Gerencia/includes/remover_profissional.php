<?php
require_once("../../config.php");

if ($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET["id"])) {
    $profissional_id = $_GET["id"];

    // Excluir os agendamentos associados ao profissional
    $sqlDeleteAgendamentos = "DELETE FROM agendamentos WHERE profissional_id = :profissional_id";
    $stmtDeleteAgendamentos = $db->prepare($sqlDeleteAgendamentos);
    $stmtDeleteAgendamentos->bindParam(":profissional_id", $profissional_id);
    $stmtDeleteAgendamentos->execute();

    // Excluir o profissional
    $sqlDeleteProfissional = "DELETE FROM profissionais WHERE id = :profissional_id";
    $stmtDeleteProfissional = $db->prepare($sqlDeleteProfissional);
    $stmtDeleteProfissional->bindParam(":profissional_id", $profissional_id);
    $stmtDeleteProfissional->execute();

    header("Location:  ../formulario_controle_gerencia.php"); // Redirecione para a página desejada após a exclusão
    exit();
} else {
    header("Location: ../formulario_controle_gerencia.php"); // Redirecione em caso de acesso direto a este arquivo sem parâmetros válidos
    exit();
}
?>
