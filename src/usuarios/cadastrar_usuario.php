<?php
    $formulario['id']           = isset($_POST['txt_id']) ? $_POST['txt_id'] : '';
    $formulario['nome']         = isset($_POST['txt_nome']) ? $_POST['txt_nome'] : '';
    $formulario['senha']        = isset($_POST['txt_senha']) ? $_POST['txt_senha'] : '';
    $formulario['adm']          = isset($_POST['cbx_adm']) ? $_POST['cbx_adm'] : '0';
    if(in_array('', $formulario))
    {
        echo
        "<script>
            alert('Existem dados faltando! Verifique');
            window.location = '../sistema.php?tela=usuarios';
        </script>";
        exit;
    }
    try
    {
        include '../class/BancodeDados.php';
        $banco = new BancodeDados;
        if($formulario['id'] == 'NOVO')
        {
            $sql = 'INSERT INTO  usuarios (nome_usuario, senha, administrador, ativo) VALUES (?,?,?,1)';
            $parametros =
            [
                $formulario['nome'],
                $formulario['senha'],
                $formulario['adm'],
            ];
            $msg_sucesso = 'Dados cadastrados com sucesso!';
        }
        else
        {
            $sql = 'UPDATE usuarios SET nome_usuario = ?, senha = ?, administrador = ? WHERE id_usuario = ?';
            $parametros =
            [
                $formulario['nome'],
                $formulario['senha'],
                $formulario['adm'],
                $formulario['id']
            ];
            $msg_sucesso = 'Dados alterados com sucesso!';
        }
        $banco -> ExecutarComando($sql, $parametros);
        echo
        "<script>
            alert('$msg_sucesso');
            window.location = '../../sistema.php?tela=usuarios';
        </script>";
    }
    catch(PDOException $erro)
    {
        $msg = $erro->getMessage();
        echo
        "<script>
            alert(\"$msg\");
            window.location = '../../sistema.php?tela=usuarios';
        </script>";
    }