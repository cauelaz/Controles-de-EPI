<?php
    $formulario['id']           = isset($_POST['txt_id']) ? $_POST['txt_id'] : '';
    $formulario['nome']         = isset($_POST['txt_nome']) ? $_POST['txt_nome'] : '';
    $formulario['preco']        = isset($_POST['txt_preco']) ? $_POST['txt_preco'] : '';
    $formulario['quantidade']   = isset($_POST['txt_quantidade']) ? $_POST['txt_quantidade'] : '';
    if(in_array('', $formulario))
    {
        echo
        "<script>
            alert('Existem dados faltando! Verifique');
            window.location = '../index.php?tela=produtos.php';
        </script>";
        exit;
    }
    try
    {
        include 'class/BancodeDados.php';
        $banco = new BancodeDados;
        if($formulario['id'] == 'NOVO')
        {
            $sql = 'INSERT INTO  produtos (nome, precodevenda, quantidade) VALUES (?,?,?)';
            $parametros =
            [
                $formulario['nome'],
                $formulario['preco'],
                $formulario['quantidade']
            ];
            $msg_sucesso = 'Dados cadastrados com sucesso!';
        }
        else
        {
            $sql = 'UPDATE produtos SET nome = ?, precodevenda = ?, quantidade = ? WHERE id_produto = ?';
            $parametros =
            [
                $formulario['nome'],
                $formulario['preco'],
                $formulario['quantidade'],
                $formulario['id']
            ];
            $msg_sucesso = 'Dados alterados com sucesso!';
        }
        $banco -> ExecutarComando($sql, $parametros);
        echo
        "<script>
            alert('$msg_sucesso');
            window.location = '../index.php?tela=produtos';
        </script>";
    }
    catch(PDOException $erro)
    {
        $msg = $erro->getMessage();
        echo
        "<script>
            alert(\"$msg\");
            window.location = '../index.php?tela=produtos';
        </script>";
    }