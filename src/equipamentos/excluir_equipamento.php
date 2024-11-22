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
        $sql = 'SELECT COUNT(equipamentos.id_equipamento) AS total_emprestimos
                    FROM equipamentos
                    JOIN equipamentos_emprestimo ON equipamentos.id_equipamento = equipamentos_emprestimo.equipamento
                    JOIN emprestimos ON emprestimos.id_emprestimo = equipamentos_emprestimo.emprestimo
                    WHERE emprestimos.situacao = 1 AND equipamentos.id_equipamento = ?';
        $parametros = [$formulario['id_equipamento']];
        $total_emprestimos = $banco->consultar($sql, $parametros);
        if($total_emprestimos['total_emprestimos'] > 0)
        {
            echo json_encode([
                'codigo' => 5,
                'mensagem' => 'Este equipamento não pode ser excluído, pois ele possui um empréstimo em aberto!'
            ]);
            exit;
        }
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