<?php
    $formulario['id']           = isset($_POST['txt_id']) ? $_POST['txt_id'] : '';
    $formulario['nome']         = isset($_POST['txt_nome']) ? $_POST['txt_nome'] : '';
    $formulario['senha']        = isset($_POST['txt_senha']) ? $_POST['txt_senha'] : '';
    $formulario['adm']          = isset($_POST['chk_administrador']) ? $_POST['chk_administrador'] : '';
    if(in_array('', $formulario))
    {
        echo
        "<script>
            alert('Existem dados faltando! Verifique');
            window.location = '../index.php?tela=usuarios.php';
        </script>";
        exit;
    }
    try
    {
        include '../class/BancodeDados.php';
        $banco = new BancodeDados;
        if($formulario['id'] == 'NOVO')
        {
            $sql = 'INSERT INTO  usuarios (nome, senha, adm) VALUES (?,?,?)';
            $parametros =
            [
                $formulario['nome'],
                $formulario['senha'],
                $formulario['adm']
            ];
            $msg_sucesso = 'Dados cadastrados com sucesso!';
        }
        else
        {
            $sql = 'UPDATE usuarios SET nome = ?, senha = ?, adm = ? WHERE id_usuario = ?';
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
            window.location = '../index.php?tela=usuarios';
        </script>";
    }
    catch(PDOException $erro)
    {
        $msg = $erro->getMessage();
        echo
        "<script>
            alert(\"$msg\");
            window.location = '../index.php?tela=usuarios';
        </script>";
    }