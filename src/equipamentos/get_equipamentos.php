<?php
    $id = isset($_GET['id']) ? $_GET['id'] : '';
    if(empty($id))
    {
        echo json_encode([
            'codigo' => 0,
            'mensagem' => 'ID obrigatório'
        ]);
        exit;
    }
    include_once '../class/BancoDeDados.php';
    $banco = new BancodeDados;
    try 
    {
        $sql = 'SELECT equipamentos.id_equipamento
                     , equipamentos.descricao
                     , equipamentos.qtd_estoque
                     , COALESCE(SUM(CASE WHEN emprestimos.situacao NOT IN (2, 3) THEN 1 ELSE 0 END), 0) AS emprestados
                     , equipamentos.certificado_aprovacao
                     , equipamentos.imagem_equipamento
                     FROM equipamentos
                     LEFT JOIN equipamentos_emprestimo ON equipamentos.id_equipamento = equipamentos_emprestimo.equipamento
                     LEFT JOIN emprestimos ON equipamentos_emprestimo.emprestimo = emprestimos.id_emprestimo AND emprestimos.situacao = 1
                     WHERE equipamentos.id_equipamento = ?
                     GROUP BY equipamentos.id_equipamento, equipamentos.descricao, equipamentos.qtd_estoque';
        $dados = $banco->Consultar($sql, [$id], true);
        if ($dados) 
        {
            // Retorna os dados do equipamento como JSON
            echo json_encode([
                'id' => $dados[0]['id_equipamento'],
                'descricao' => $dados[0]['descricao'],
                'qtd_estoque' => $dados[0]['qtd_estoque'],
                'emprestados' => $dados[0]['emprestados'],
                'certificado_aprovacao' => $dados[0]['certificado_aprovacao'],
                'imagem_equipamento' => $dados[0]['imagem_equipamento']
            ]);
        } 
        else 
        {
            echo json_encode(['erro' => 'Equipamento não encontrado']);
        }
    } 
    catch (PDOException $erro) 
    {
        echo json_encode(['erro' => $erro->getMessage()]);
    }