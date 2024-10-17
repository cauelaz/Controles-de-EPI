<?php
    header('Content-Type: application/json');
    $formulario['id_departamento'] = isset($_POST['id']) ? $_POST['id'] : '';
    if(empty($formulario['id_departamento']))
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
        $sql = 'SELECT COUNT(colaboradores.id_departamento) AS total_departamentos
                 FROM departamentos
                 LEFT JOIN colaboradores ON colaboradores.id_departamento = departamentos.id_departamento 
                 WHERE departamentos.id_departamento = ?';
        $parametros = [$formulario['id_departamento']];
        $total_emprestimos = $banco->consultar($sql, $parametros);
        if($total_emprestimos['total_departamentos'] > 0)
        {
            echo json_encode([
                'codigo' => 5,
                'mensagem' => 'Este departamento não pode ser excluído, pois ele possui colaboradores vinculados!'
            ]);
            exit;
        }
        $sql = 'UPDATE departamentos SET ativo = 0 WHERE id_departamento = ?';
        $parametros = [$formulario['id_departamento']];
        $banco -> ExecutarComando($sql,$parametros);
        echo json_encode([
           'codigo' => 2,
           'mensagem' => 'Departamento excluído com sucesso!' 
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