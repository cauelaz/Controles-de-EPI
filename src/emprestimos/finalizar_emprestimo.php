<?php

header('Content-Type: application/json');

include 'functions.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    try {

        include '../class/BancodeDados.php';

        $banco = new BancodeDados;

        $idEmprestimo = isset($_GET['id']) ? $_GET['id'] : 0 ;
        $soma =  isset($_GET['soma'])  ? true : false;

        $banco->startTransaction();

        $concluiu = FinalizarEmprestimo($banco,$idEmprestimo,$soma);

        if ($concluiu) {
            $banco->commit();
            echo json_encode([
                'codigo'=> 2,
                'mensagem' => 'Empréstimo finalizado com sucesso!'
            ]);

        }

    } catch (PDOException $error) {
        $banco->rollback();
        $msg = $error->getMessage();
        echo json_encode([
            'codigo' => 0,
            'mensagem' => "Erro ao finalizar empréstimo!  $msg"
        ]);
    }



}


function FinalizarEmprestimo($banco,$idEmprestimo,$soma){

    try {

        $sql = 'UPDATE emprestimos set situacao = 2 where id_emprestimo = ? ;';
        $parametros  = [
            $idEmprestimo  
        ];

        $resultado = $banco->ExecutarComando($sql,$parametros);

        $equipamentosEmprestimo = BuscarEquipamentoEmprestimo($idEmprestimo,$banco);

        foreach ($equipamentosEmprestimo as $equipamento) {
            extract($equipamento);
            AtualizarEstoque($banco,$equipamento['id_equipamento'],$equipamento['quantidade'],false);
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