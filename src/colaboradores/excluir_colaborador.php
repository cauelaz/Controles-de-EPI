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
        $sql = 'SELECT COUNT(emprestimos.colaborador) AS total_emprestimos
                    FROM emprestimos
                    WHERE emprestimos.ativo = 1 AND emprestimos.colaborador = ?';
        $parametros = [$formulario['id']];
        $total_emprestimos = $banco->consultar($sql, $parametros);
        if($total_emprestimos['total_emprestimos'] > 0)
        {
            echo json_encode([
                'codigo' => 5,
                'mensagem' => 'Este Colaborador não pode ser excluído, pois ele possui um empréstimo em aberto!.'
            ]);
            exit;
        }
        $sql = 'UPDATE colaboradores SET ativo = 0 WHERE id_colaborador = ?';
        $parametros = [ $formulario['id'] ];
        $banco -> ExecutarComando($sql,$parametros);
        echo json_encode([
           'codigo' => 2,
           'mensagem' => 'Colaborador excluído com sucesso!'
        ]);
    } 
    catch(PDOException $erro) 
    {
        $msg = $erro->getMessage();
        echo json_encode([
           'codigo' => 0,
           'Mensagem' => "Erro ao realizar exclusão: $msg"
        ]);
    }