<?php
    header('Content-Type: application/json');
    $formulario['id']    = isset($_POST['id'])        ? $_POST['id'] : '';
    $formulario['nome']  = isset($_POST['nome'])   ? $_POST['nome'] : '';
    if(in_array('', $formulario))
    {
        echo json_encode([
            'codigo'=> 0,
            'mensagem' => 'Existem dados faltando! Verifique.'
        ]);
        exit;
    }
    try
    {
        include '../class/BancodeDados.php';
        $banco = new BancodeDados;
        if($formulario['id'] == 'NOVO')
        {
            $sql = 'INSERT INTO  departamentos (nome_departamento, ativo) VALUES (?,1)';
            $parametros =
            [
                $formulario['nome']
            ];
            echo json_encode([
                'codigo' => 2,
                'mensagem' => 'Departamento cadastrado com sucesso!'
            ]);
        }
        else
        {
            $sql = 'UPDATE departamentos SET nome_departamento = ? WHERE id_departamento = ?';
            $parametros =
            [
                $formulario['nome'],
                $formulario['id']
            ];
            echo json_encode([
                'codigo' => 2,
                'mensagem' => 'Departamento atualizado com sucesso!'
            ]);
        }
        $banco -> ExecutarComando($sql, $parametros);
    }
    catch(PDOException $erro)
    {
        $msg = $erro->getMessage();
        echo json_encode([
            'codigo' => 0,
            'mensagem' => "Erro ao realizar registro: $msg"
        ]);
    }