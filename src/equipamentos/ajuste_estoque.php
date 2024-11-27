<?php
    header('content-type: application/json');
    $formulario['id']             = isset($_POST['id'])             ? $_POST['id'] : '';
    $formulario['qtd_estoque']    = isset($_POST['qtd_estoque'])    ? $_POST['qtd_estoque'] : '';
    $formulario['qtd_ajuste']     = isset($_POST['qtd_ajuste'])     ? $_POST['qtd_ajuste'] : '';
    $formulario['emprestados']    = isset($_POST['emprestados'])    ? $_POST['emprestados'] : '';
    if(in_array('',$formulario))
    {
        echo json_encode([
            'codigo' => 0,
            'mensagem' => 'Existem dados faltando! Verifique.'
        ]);
        exit;
    }
    try
    {
        include_once '../class/BancoDeDados.php';
        $banco = new BancoDeDados;
        $sql = 'SELECT qtd_estoque FROM equipamentos WHERE id_equipamento = ?';
        $parametros = [$formulario['id']];
        $resultado = $banco->Consultar($sql,$parametros);
        $qtd_estoque = $resultado['qtd_estoque'];
        $qtd_ajuste = $qtd_estoque + (int)$formulario['qtd_ajuste'];
        if($formulario['qtd_ajuste'] == 0)
        {
            echo json_encode([
               'codigo' => 3,
               'mensagem' => 'Nenhum ajuste foi realizado!'
            ]);
            exit;
        }
        else if($qtd_ajuste < $formulario['emprestados'])
        {         
            echo json_encode([
               'codigo' => 3,
               'mensagem' => 'Estoque insuficiente para o ajuste, pois existem itens emprestados!'
            ]);
            exit;
        }
        else 
        {
            $sql = 'UPDATE equipamentos SET qtd_estoque = ? WHERE id_equipamento = ?';
            $parametros = [
                $qtd_ajuste,
                $formulario['id']
            ];
        }
        $banco->ExecutarComando($sql,$parametros);
        echo json_encode([
           'codigo' => 2,
           'mensagem' => 'Estoque atualizado com sucesso!'
        ]);    
    }
    catch(PDOException $erro)
    {
        echo $erro->getMessage();
        echo json_encode([
            'codigo' => 0,
            'mensagem' => "Erro ao realizar registro: $msg"
        ]);
    }