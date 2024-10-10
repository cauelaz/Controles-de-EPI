<?php
    header('Content-Type: application/json');
    $formulario['id_equipamento'] = isset($_POST['id']) ? $_POST['id'] : '';
    if(empty($formulario['id_equipamento']))
    {
        echo json_encode([
            'codigo' => 0,
            'mensagem' => 'Existem dados faltando! Verifique.'
        ]);
        exit;
    }
    try
    {
        include '../class/BancodeDados.php';
        $banco = new BancodeDados;
        $sql = 'UPDATE equipamentos SET ativo = 0 WHERE id_equipamento = ?';
        $parametros = [$formulario['id_equipamento']];
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