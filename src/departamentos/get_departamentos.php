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
        $sql = 'SELECT id_departamento, nome_departamento FROM departamentos WHERE id_departamento = ?';
        $dados = $banco->Consultar($sql, [$id], true);
        if ($dados) 
        {
            // Retorna os dados do equipamento como JSON
            echo json_encode([
                'id' => $dados[0]['id_departamento'],
                'nome' => $dados[0]['nome_departamento']
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