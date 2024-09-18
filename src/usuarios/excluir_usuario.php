<?php
    // Validação
    $id_usuario = isset($_GET['IdUsuario']) ? $_GET['IdUsuario'] : '';
    if (empty($id_usuario)) {
        header('LOCATION: ../../sistema.php?tela=usuarios');
        // Não é necessário usar 'exit' após o header()
    }

    // Continuando

    // Banco de dados
    try {
        include_once '../class/BancoDeDados.php';
        $banco = new BancoDeDados;
        $sql = 'UPDATE usuarios SET ativo = 0 WHERE id_usuario = ?';
        $parametros = [ $id_usuario ];
        $banco -> ExecutarComando($sql,$parametros);
        echo "<script>
            alert('Usuário removido com sucesso!');
            window.location = '../../sistema.php?tela=usuarios';
        </script>";
    } catch(PDOException $erro) {
        $msg = $erro->getMessage();
        echo "<script>
            alert(\"$msg\");
            window.location = '../../sistema.php?tela=usuarios';
        </script>";
    }
