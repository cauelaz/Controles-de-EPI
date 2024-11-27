<?php
header('Content-Type: application/json');

function CadastrarEmprestimo($formulario, $banco) : int {
    try {
        $sql = 'INSERT INTO emprestimos (colaborador, situacao, data_emprestimo, data_devolucao, observacoes)
                VALUES (?, ?, ?, ?, ?)';

        $parametros = [
            $formulario['colaborador'],
            $formulario['situacao'],
            $formulario['dataEmprestimo'],
            $formulario['dataDevolucao'],
            $formulario['observacoes']
        ];

        $id = $banco->ExecutarComando($sql, $parametros);
        $id = $banco->getLastInsertId();

        return $id;

    } catch (PDOException $erro) {
        $msg = $erro->getMessage();
        echo json_encode([
            'codigo' => 0,
            'mensagem' => "Erro ao inserir registro: $msg"
        ]);
        return 0;
    }
}

function CadastrarEquipamentosEmprestimo($idEmprestimo, $idEquipamento, $banco, $quantidade) {
    try {
        $sql = 'INSERT INTO equipamentos_emprestimo (emprestimo, equipamento, quantidade) VALUES (?, ?, ?)';

        $parametros = [
            $idEmprestimo,
            $idEquipamento,
            $quantidade
        ];

        $banco->ExecutarComando($sql, $parametros);

    } catch (PDOException $erro) {
        $msg = $erro->getMessage();
        echo json_encode([
            'codigo' => 0,
            'mensagem' => "Erro ao inserir equipamentos: $msg"
        ]);
    }
}
function AtualizarEmprestimo($idEmprestimo,$banco){

}

function BuscarEmprestimo($idEmprestimo,$banco){
    $sql = ' SELECT 
                id_emprestimo as id
                ,data_emprestimo as dataEmprestimo
                ,data_devolucao as dataDevolucao
                ,observacoes
                ,colaborador 
                ,situacao 
                from emprestimos e
                where e.id_emprestimo = ?';

    $parametros = [
        $idEmprestimo
    ];

    $emprestimo = $banco->Consultar($sql,$parametros);


    return $emprestimo;
 
}
function BuscarEquipamentoEmprestimo($idEmprestimo,$banco){
    $sql = ' SELECT equip.id_equipamento 
                    ,equip.descricao
                    ,ee.quantidade
                FROM equipamentos_emprestimo ee 
                LEFT JOIN equipamentos equip on equip.id_equipamento = ee.equipamento 
                LEFT JOIN emprestimos e2 on e2.id_emprestimo = ee.emprestimo 
                WHERE e2.id_emprestimo = ?
                ';

    $parametros = [
        $idEmprestimo
    ];


    $equipamentosEmprestimo = $banco->Consultar($sql,$parametros,true);



    return $equipamentosEmprestimo;
 
}


function AtualizarEstoque($banco,$idEquipamento,$quantidade,$soma){

    try {

        $sqlqtd  = 'SELECT qtd_estoque FROM equipamentos WHERE id_equipamento = ?';

        $parametros = [
            $idEquipamento
        ];

        $qtdAtual = $banco->Consultar($sqlqtd,$parametros);


        if ($soma == true){
            $qtd =  $qtdAtual + $quantidade;
        }else{
            $qtd =  $qtdAtual - $qtdAtual;
        }

        $sql = 'UPDATE equipamentos SET qtd_estoque = ?
        WHERE id_equipamento = ?';

        $parametros = [
            $idEquipamento,
            $qtd 
        ];

        $result = $banco->ExecutarComando($sql,$parametros);

        return true;

    } catch (PDOException $error) {
        $msg = $erro->getMessage();
        echo json_encode([
            'codigo' => 0,
            'mensagem' => "Erro ao atualizar estoque: $msg"
        ]);
        return false;
    }


}