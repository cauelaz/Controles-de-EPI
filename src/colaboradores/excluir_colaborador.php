<?php
    // Validação
    $id_colaborador = isset($_GET['IdColaborador']) ? $_GET['IdColaborador'] : '';
    if (empty($id_colaborador)) 
    {
        header('LOCATION: ../sistema.php?tela=colaboradores');
    }
    // Banco de dados
    try 
    {
        include '../class/BancoDeDados.php';
        $banco = new BancoDeDados;
        $sql = 'UPDATE colaboradores SET ativo = 0 WHERE id_colaborador = ?';
        $parametros = [ $id_colaborador ];
        $banco -> ExecutarComando($sql,$parametros);
        echo 
        "<script>
            alert('Colaborador removido com sucesso!');
            window.location = '../../sistema.php?tela=colaboradores';
        </script>";
    } 
    catch(PDOException $erro) 
    {
        $msg = $erro->getMessage();
        echo 
        "<script>
            alert(\"$msg\");
            window.location = '../../sistema.php?tela=colaboradores';
        </script>";
    }
