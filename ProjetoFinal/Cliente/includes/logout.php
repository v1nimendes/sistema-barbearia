<?php
session_start();

// Encerrar a sessão
session_unset();
session_destroy();

// Redirecionar para a página de login
header("Location: ../formulario_cliente.php");
exit;
?>
