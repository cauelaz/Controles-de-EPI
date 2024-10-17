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
        $sql = 'UPDATE departamentos SET ativo = 1 WHERE id_departamento = ?';
        $parametros = [ $formulario['id'] ];
        $banco -> ExecutarComando($sql,$parametros);
        echo json_encode([
           'codigo' => 2,
           'mensagem' => 'Departamento reativado com sucesso!'
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