<?php

header('Content-Type: application/json');

include 'functions.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    try {

        include '../class/BancodeDados.php';

        $banco = new BancodeDados;

        $idEmprestimo = isset($_GET['id']) ? $_GET['id'] : 0 ;
        $soma =  isset($_GET['soma'])  ? true : false;
        $situacao =  isset($_GET['situacao'])  ? $_GET['situacao'] : 0;
        

        if ($situacao == 2) {
            $mensagem = "Empréstimo finalizado com sucesso";
        } elseif ($situacao == 3) {
            $mensagem = "Empréstimo cancelado com sucesso";
        }
        


        $concluiu = AtualizarStatus($banco,$idEmprestimo,$situacao,$soma);

        if ($concluiu) {
            echo json_encode([
                'codigo'=> 2,
                'mensagem' =>  $mensagem
            ]);

        }

    } catch (PDOException $error) {

        $msg = $error->getMessage();
        echo json_encode([
            'codigo' => 0,
            'mensagem' => "Erro ao finalizar empréstimo!  $msg"
        ]);
    }



}


function AtualizarStatus($banco,$idEmprestimo,$situacao,$soma){

    try {


        $sql = 'UPDATE emprestimos 
        SET situacao = ?, data_devolucao = ? 
        WHERE id_emprestimo = ?';

        $date = date('Y-m-d H:i:s'); // Data e hora atuais no formato 'YYYY-MM-DD HH:MM:SS'

        $parametros = [
            $situacao, // cancelar ou finalizar
            $date,  // A data atual
            $idEmprestimo  // ID do empréstimo
        ];


        $resultado = $banco->ExecutarComando($sql,$parametros);

        $equipamentosEmprestimo = BuscarEquipamentoEmprestimo($idEmprestimo,$banco);

        foreach ($equipamentosEmprestimo as $equipamento) {
            extract($equipamento);
            try {
                AtualizarEstoque($banco,$id_equipamento, $quantidade, $soma);
            } catch (Exception $e) {
                error_log("Erro ao atualizar estoque: " . $e->getMessage());
                echo "Erro: Não foi possível atualizar o estoque.";
            }

        }


        return true;

    } catch (PDOException $erro) {
        $msg = $erro->getMessage();
        echo json_encode([
            'codigo' => 0,
            'mensagem' => "Erro ao finalizar empréstimo!  $msg"
        ]);
        return false;
    };


}




?>