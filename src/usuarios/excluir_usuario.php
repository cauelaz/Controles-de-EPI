<?php
    header('Content-Type: application/json');
    // Validação
    $formulario['id'] = isset($_POST['id']) ? $_POST['id'] : '';
    if (empty($formulario['id'])) 
    {
        echo json_encode([
           'codigo' => 0,
           'mensagem' => 'Existem dados faltando! Verifique.' 
        ]);  
    }
    try 
    {
        include_once '../class/BancoDeDados.php';
        $banco = new BancoDeDados;
        $sql = 'UPDATE usuarios SET ativo = 0 WHERE id_usuario = ?';
        $parametros = [ $formulario['id'] ];
        $banco -> ExecutarComando($sql,$parametros);
        echo json_encode([
           'codigo' => 2,
           'mensagem' => 'Dados excluídos com sucesso!'
        ]);
    } 
    catch(PDOException $erro) 
    {
        $msg = $erro->getMessage();
        echo json_encode([
           'codigo' => 0,
           'mensagem' => "Erro ao realizar exclusão: $msg" 
        ]);
    }