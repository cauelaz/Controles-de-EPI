<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') 
{
    $id = $_POST['id'];
    include_once '../class/BancoDeDados.php';
    $banco = new BancodeDados;
    try 
    {
        $sql = 'SELECT id_colaborador, nome_colaborador, data_nascimento, cpf_cnpj, rg, telefone FROM colaboradores WHERE id_colaborador = ?';
        $dados = $banco->Consultar($sql, [$id], true);
        if ($dados) 
        {
            echo json_encode([
                'id' => $dados[0]['id_colaborador'],
                'nome' => $dados[0]['nome_colaborador'],
                'data_nascimento' => $dados[0]['data_nascimento'],
                'cpf_cnpj' => $dados[0]['cpf_cnpj'],
                'rg' => $dados[0]['rg'],
                'telefone' => $dados[0]['telefone']
            ]);
        } 
        else 
        {
            echo json_encode(['erro' => 'Colaborador não encontrado']);
        }
    } 
    catch (PDOException $erro) 
    {
        echo json_encode(['erro' => $erro->getMessage()]);
    }
}