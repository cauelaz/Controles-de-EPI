<?php
    // Destruindo a sessão
    session_start();
    session_destroy();

    // Redirecionar
    header('LOCATION: ../index.php');

