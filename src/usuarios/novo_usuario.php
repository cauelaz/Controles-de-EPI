<?php
    $formulario['nome']         = isset($_POST['txt_usuario']) ? $_POST['txt_usuario'] : '';
    $formulario['senha']        = isset($_POST['txt_senha'])   ? $_POST['txt_senha'] : '';
    $formulario['adm']          = isset($_POST['list_user'])   ? $_POST['list_user'] : '';
    if(in_array('', $formulario))
    {
        echo
        "<script>
            alert('Existem dados faltando! Verifique');
            window.location = '../../sistema.php';
        </script>";
        exit;
    }
    try
    {
        include '../class/BancodeDados.php';
        $banco = new BancodeDados;
        $sql = 'INSERT INTO  usuarios (nome_usuario, senha, administrador, ativo) VALUES (?,?,?,1)';
        $parametros =
        [
            $formulario['nome'],
            $formulario['senha'],
            $formulario['adm'],
        ];
        $msg_sucesso = 'Dados cadastrados com sucesso!';
        $banco -> ExecutarComando($sql, $parametros);
        echo
        "<script>
            alert('$msg_sucesso');
            window.location = '../../sistema.php';
        </script>";
    }
    catch(PDOException $erro)
    {
        $msg = $erro->getMessage();
        echo
        "<script>
            alert(\"$msg\");
            window.location = '../../registrar.php';
        </script>";
    }