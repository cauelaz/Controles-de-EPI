<?php
    header('content-type: application/json');
    $id = isset($_POST['id']) ? $_POST['id'] : '';
    if(empty($id))
    {
        echo json_encode([
            'codigo'=> 0,
            'mensagem' => 'Existem dados faltando! Verifique.'
        ]);
        exit;
    }
    try
    {
        include_once '../class/BancoDeDados.php';
        $banco = new BancoDeDados;
        $sql = 'SELECT imagem_equipamento 
                FROM equipamentos 
                WHERE id_equipamento = ?';
        $parametros = [$id];
        $imagem = $banco->consultar($sql, $parametros);
        if($imagem['imagem_equipamento'] != 'vazio')
        {
            unlink('upload/' . $imagem['imagem_equipamento']);
            // Desvincula imagem
            $sql = 'UPDATE equipamentos SET imagem_equipamento = ? WHERE id_equipamento = ?';
            $parametros = ['vazio', $id];
            $banco->ExecutarComando($sql, $parametros);
            echo json_encode([
                'codigo' => 2,
                'mensagem' => 'Imagem removida com sucesso!'
            ]);
        }
        else
        {
            echo json_encode([
                'codigo' => 0,
                'mensagem' => 'Imagem não encontrada!'
            ]);
        }
    }
    catch(PDOException $erro)
    {
        $msg_erro = $erro->getMessage();
        echo json_encode([
            'codigo' => 0,
            'mensagem' => "Erro ao realizar consulta de imagem: $msg_erro"
        ]);
    }
    exit;