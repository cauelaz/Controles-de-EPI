<?php
    header('content-type: application/json');
    // Validação
    $formulario['id'] = isset($_POST['id']) ? $_POST['id'] : '';
    if(empty($formulario['id']))
    {
        echo json_encode([
            'codigo' => 0,
            'mensagem' => 'Existem dados faltando! Verifique.'
        ]);
        exit;
    }
    try 
    {
        include '../class/BancoDeDados.php';
        $banco = new BancoDeDados;
        $sql = 'UPDATE equipamentos SET ativo = 1 WHERE id_equipamento = ?';
        $parametros = [ $formulario['id'] ];
        $banco -> ExecutarComando($sql,$parametros);
        echo json_encode([
           'codigo' => 2,
           'mensagem' => 'Equipamento reativado com sucesso!'
        ]);
        exit;
    } 
    catch(PDOException $erro) 
    {
        $msg = $erro->getMessage();
        echo json_encode([
           'codigo' => 0,
           'mensagem' => "Erro ao realizar reativação: $msg"
        ]);
    }