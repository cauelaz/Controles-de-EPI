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

function AtualizarEmprestimo($idEmprestimo, $formulario, $banco) : bool {
    try {

        $colaborador = $formulario['colaborador'];    
        $situacao = $formulario['situacao'];          
        $dataEmprestimo = $formulario['dataEmprestimo'];  
        $dataDevolucao = $formulario['dataDevolucao'];   
        $observacoes = $formulario['observacoes'];   

        $parametros = [
            $colaborador,      
            $situacao,         // Nova situação
            $dataEmprestimo,   // Nova data do empréstimo
            $dataDevolucao,    // Nova data de devolução
            $observacoes,      // Novas observações
            $idEmprestimo      // ID do empréstimo a ser atualizado
        ];

        // SQL para atualizar o empréstimo
        $sql = 'UPDATE emprestimos 
                SET colaborador = ?, situacao = ?, data_emprestimo = ?, data_devolucao = ?, observacoes = ?
                WHERE id_emprestimo = ?';

        // Executa o comando SQL
        $banco->ExecutarComando($sql, $parametros);

        return true;

    } catch (PDOException $erro) {
        $msg = $erro->getMessage();
        echo json_encode([
            'codigo' => 0,
            'mensagem' => "Erro ao atualizar empréstimo: $msg"
        ]);
        return false;
    }
}

function AtualizarEquipamentosEmprestimo($idEmprestimo, $idEquipamento, $banco, $quantidade) {
    try {
        $sql = 'UPDATE equipamentos_emprestimo 
                SET quantidade = ? 
                WHERE emprestimo = ? AND equipamento = ?';

        $parametros = [
            $quantidade,     // Nova quantidade do equipamento emprestado
            $idEmprestimo,   // ID do empréstimo
            $idEquipamento   // ID do equipamento
        ];

        // Executa o comando SQL
        $banco->ExecutarComando($sql, $parametros);

    } catch (PDOException $erro) {
        $msg = $erro->getMessage();
        echo json_encode([
            'codigo' => 0,
            'mensagem' => "Erro ao atualizar equipamento: $msg"
        ]);
    }
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


function AtualizarEstoque($banco, $idEquipamento, $quantidade, $soma) {

    try {

        // Consulta a quantidade atual de estoque
        $sqlqtd  = 'SELECT qtd_estoque FROM equipamentos WHERE id_equipamento = ?';
        $parametros = [$idEquipamento];
        $qtdAtual = $banco->Consultar($sqlqtd, $parametros);

        $qtdAtual = is_array($qtdAtual) ? reset($qtdAtual) : $qtdAtual;
        $quantidade = is_array($quantidade) ? reset($quantidade) : $quantidade;

        // Se for para somar a quantidade
        if ($soma) {
            $qtd = $qtdAtual + $quantidade;
        } else {  // Se for para subtrair a quantidade
            $qtd = $qtdAtual - $quantidade;
        }

        // Atualiza o estoque com a nova quantidade
        $sql = 'UPDATE equipamentos SET qtd_estoque = ? WHERE id_equipamento = ?';
        $parametros = [$qtd, $idEquipamento];

        // Executa o comando de atualização
        $banco->ExecutarComando($sql, $parametros);

        return true;

    } catch (PDOException $error) {
        $msg = $error->getMessage();
        echo json_encode([
            'codigo' => 0,
            'mensagem' => "Erro ao atualizar estoque: $msg"
        ]);
        return false;
    }
}


function VerificarDisponibilidadeEstoque($banco,$idEquipamento, $quantidade) {
    try {
        // Consulta a quantidade atual de estoque
        $sqlqtd = 'SELECT qtd_estoque FROM equipamentos WHERE id_equipamento = ?';
        $parametros = [$idEquipamento];
        $qtdAtual = $banco->Consultar($sqlqtd, $parametros);

        // Se o resultado for um array, pega o primeiro valor
        $qtdAtual = is_array($qtdAtual) ? reset($qtdAtual) : $qtdAtual;

        // Verifica se a quantidade disponível é suficiente
        if (($qtdAtual - $quantidade) < 0) {
            return false; // Estoque insuficiente
        }

        return true; // Estoque suficiente
    } catch (PDOException $error) {
        $msg = $error->getMessage();
        echo json_encode([
            'codigo' => 0,
            'mensagem' => "Erro ao verificar estoque: $msg"
        ]);
        return false;
    }
}

function CancelarEmprestimo($banco,$idEmprestimo){

}
