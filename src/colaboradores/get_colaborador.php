<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') 
{
    $id = $_POST['id'];
    include_once '../class/BancoDeDados.php';
    $banco = new BancodeDados;
    try 
    {
        $sql = 'SELECT *
                FROM colaboradores
                LEFT JOIN endereco_colaborador on endereco_colaborador.fk_id_colaborador = colaboradores.id_colaborador 
                WHERE id_colaborador = ?';
        $dados = $banco->Consultar($sql, [$id], true);
        if ($dados) 
        {
            $response = [
                'id' => $dados[0]['id_colaborador'] ?? null,
                'nome' => $dados[0]['nome_colaborador'] ?? '',
                'data_nascimento' => $dados[0]['data_nascimento'] ?? '',
                'cpf_cnpj' => $dados[0]['cpf_cnpj'] ?? '',
                'rg' => $dados[0]['rg'] ?? '',
                'telefone' => $dados[0]['telefone'] ?? '',
                'departamento' => $dados[0]['id_departamento'] ?? 0,
                'cep' => $dados[0]['cep'] ?? '',
                'rua' => $dados[0]['rua'] ?? '',
                'numero' => $dados[0]['numero'] ?? null,
                'bairro' => $dados[0]['bairro'] ?? null,
                'cidade' => $dados[0]['id_cidade'] ?? null,
                'uf' => $dados[0]['uf'] ?? null,
                'complemento' => $dados[0]['complemento'] ?? null
            ];
            echo json_encode($response);
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