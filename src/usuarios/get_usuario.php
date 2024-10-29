<?php
    $id = isset($_GET['id']) ? $_GET['id'] : '';
    if(empty($id))
    {
        echo json_encode([
            'codigo' => 0,
            'mensagem' => 'ID obrigatório'
        ]);
        exit;
    }
    include_once '../class/BancoDeDados.php';
    $banco = new BancodeDados;
    try 
    {
        $sql = 'SELECT id_usuario, nome_usuario, senha, administrador FROM usuarios WHERE id_usuario = ?';
        $dados = $banco->Consultar($sql, [$id], true);
        if ($dados) 
        {
            // Retorna os dados do equipamento como JSON
            echo json_encode([
                'id' => $dados[0]['id_usuario'],
                'nome' => $dados[0]['nome_usuario'],
                'senha' => $dados[0]['senha'],
                'administrador' => $dados[0]['administrador']
            ]);
        } 
        else 
        {
            echo json_encode(['erro' => 'Usuário não encontrado']);
        }
    } 
    catch (PDOException $erro) 
    {
        echo json_encode(['erro' => $erro->getMessage()]);
    }