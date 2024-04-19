<?php
session_start();

// Encerrar a sessão
session_unset();
session_destroy();

// Redirecionar para a página de login
header("Location: ../../index.html");
exit;
?>
