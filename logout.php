<?php
session_start();
session_destroy(); // Destrói o "crachá" de acesso
header("Location: index.php"); // Manda de volta pra tela de login
exit;
?>