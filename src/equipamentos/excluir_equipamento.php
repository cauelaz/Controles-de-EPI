<?php
    $id_equipamento = isset($_GET['IdEquipamento']) ? $_GET['IdEquipamento'] : '';
    if(empty($id_equipamento))
    {
        header('LOCATION: ../../sistema.php?tela=equipamentos');
    }
    try
    {
        include '../class/BancodeDados.php';
        $banco = new BancodeDados;
        $sql = 'UPDATE equipamentos SET ativo = 0 WHERE id_equipamento = ?';
        $parametros = [$id_equipamento];
        $banco -> ExecutarComando($sql,$parametros);
        echo 
        "<script>
            alert('Equipamento removido com sucesso!');
            window.location = '../../sistema.php?tela=equipamentos';
        </script>";
    }
    catch(PDOException $erro)
    {
        $msg = $erro->getMessage();
        echo
        "<script>
            alert(\"$msg\");
            window.location = '../../sistema.php?tela=equipamentos';
        </script>";
    }