<?php
    // Destruindo a sessão
    session_start();
    session_destroy();
    $configuracoes = 
    [
        'expires' => time() - 1800, //Hora atual - 30 minutos, fazendo com que o cookie seja excluído
        'path' => '/',
        'domain' => 'localhost',
    ];
    setcookie('id_user', 'logout', $configuracoes);
    $configuracoes = 
    [
        'expires' => time() - 1800, //Hora atual - 30 minutos, fazendo com que o cookie seja excluído
        'path' => '/',
        'domain' => 'localhost',
    ];
    setcookie('login_time', 'logout', $configuracoes);
    
    // Redirecionar
    header('LOCATION: ../index.php');