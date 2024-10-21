<?php
    $id = isset($_POST['id']) ? $_POST['id'] : '';
    if(empty($id))
    {
        echo json_encode([
            'codigo' => 0,
            'mensagem' => 'ID obrigatório'
        ]);
        exit;
    }
    // Conectar ao banco de dados (usando sua classe BancodeDados)
    include_once '../class/BancoDeDados.php';
    $banco = new BancodeDados;
    try 
    {
        // Consulta para obter os dados do equipamento
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