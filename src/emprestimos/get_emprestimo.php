<?php

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {

        $idEmprestimo = isset($_GET['id']) ? $_GET['id'] : 0 ;
        $inativos =  isset($_GET['inativos'])  ? true : false;

        include '../class/BancodeDados.php';
        $banco = new BancodeDados;

        if ($idEmprestimo != 0) {
            get_one($banco,$idEmprestimo);
        }else{
            get_all($banco,$inativos);
        }

    } catch (Exception $erro) {
        echo json_encode("Erro na requisição");
    }
}

function get_one($banco,$id){

    include 'functions.php';  
    
    $idEmprestimo = $id;
    
    $emprestimo = BuscarEmprestimo($idEmprestimo,$banco);


    $equipamentosEmprestimo = BuscarEquipamentoEmprestimo($idEmprestimo,$banco);

    if ($equipamentosEmprestimo != null && $emprestimo != null) {
    
        $response = [
            "id" => $emprestimo['id'],  
            "colaborador" => $emprestimo['colaborador'],
            "situacao" => $emprestimo['situacao'],
            "dataEmprestimo" => $emprestimo['dataEmprestimo'],
            "dataDevolucao" => $emprestimo['dataDevolucao'],
            "observacoes" => $emprestimo['observacoes'],
            "itens" => []
        ];

        foreach ($equipamentosEmprestimo as $equipamento) {
            extract($equipamento);
            $response['itens'][] = [
                "idEquipamento" => $equipamento['id_equipamento'],  
                "descricao" => $equipamento['descricao'],
            ];
        }

        echo json_encode( $response);
        
    } else{
        echo json_encode([
            'codigo' => 0,
            'mensagem' => "Empréstimo não encontrado ou vazio! "
        ]);
    }
}

function get_all($banco,$inativos){

    try{

        $condicao = $inativos ? 2 : 1;


        $sql = 'SELECT
                e.id_emprestimo,
                e.data_emprestimo,
                e.data_devolucao,
                c.nome_colaborador,
                e.observacoes,
                SUM(ee.quantidade) AS quantidade
            FROM emprestimos e
            LEFT JOIN colaboradores c ON c.id_colaborador = e.colaborador
            LEFT JOIN equipamentos_emprestimo ee ON ee.emprestimo = e.id_emprestimo
            WHERE e.situacao = ? 
            GROUP BY e.id_emprestimo, e.data_emprestimo, e.data_devolucao, c.nome_colaborador, e.observacoes
            ORDER BY e.id_emprestimo ASC;';


        $parametros  = [
            $condicao  
        ];


        $resultado = $banco->Consultar($sql,$parametros,true);

        $emprestimos = [
            'Emprestimos' => []
        ];
        
        foreach ($resultado as $emprestimo) {
            $emprestimos['Emprestimos'][] = $emprestimo;  
        }

        echo json_encode($emprestimos);


    }catch(PDOException $erro){
        $msg = $erro->getMessage();
        echo json_encode([
            'codigo' => 0,
            'mensagem' => "Erro ao buscar empréstimos!" .  $msg
        ]);
    }


}