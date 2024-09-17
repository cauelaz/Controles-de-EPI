<?php
    $id_produto = isset($_GET['IdEquipamento']) ? $_GET['IdEquipamento'] : '';
    if(empty($id_produto))
    {
        header('LOCATION: ../index.php?tela=equipamentos');
    }
    try
    {
        include 'class/BancodeDados.php';
        $banco = new BancodeDados;
        $sql = 'DELETE FROM produtos WHERE id_produto = ?';
        $parametros = [$id_produto];
        $banco -> ExecutarComando($sql,$parametros);
        echo 
        "<script>
            alert('Produto removido com sucesso!');
            window.location = '../index.php?tela=equipamentos';
        </script>";
    }
    catch(PDOException $erro)
    {
        $msg = $erro->getMessage();
        echo
        "<script>
            alert(\"$msg\");
            window.location = '../index.php?tela=equipamentos';
        </script>";
    }