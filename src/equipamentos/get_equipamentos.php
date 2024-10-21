<?php
    $id = isset($_POST['id']) ? $_POST['id'] : '';
    if(empty($id))
    {
        echo json_encode([
            'codigo' => 0,
            'mensagem' => 'ID obrigatório'
        ]);
        exit;
    }
    // Conectar ao banco de dados (usando sua classe BancodeDados)
    include_once '../class/BancoDeDados.php';
    $banco = new BancodeDados;
    try 
    {
        // Consulta para obter os dados do equipamento
        $sql = 'SELECT equipamentos.id_equipamento
                     , equipamentos.descricao
                     , equipamentos.qtd_estoque
                     , COALESCE(SUM(CASE WHEN emprestimos.ativo = 1 THEN 1 ELSE 0 END), 0) AS emprestados
                     , (equipamentos.qtd_estoque - COALESCE(SUM(CASE WHEN emprestimos.ativo = 1 THEN 1 ELSE 0 END), 0)) AS qtd_disponivel
                     , equipamentos.certificado_aprovacao
                     , equipamentos.imagem_equipamento
                     FROM equipamentos
                     LEFT JOIN equipamentos_emprestimo ON equipamentos.id_equipamento = equipamentos_emprestimo.equipamento
                     LEFT JOIN emprestimos ON equipamentos_emprestimo.emprestimo = emprestimos.id_emprestimo AND emprestimos.ativo = 1
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
                'qtd_disponivel' => $dados[0]['qtd_disponivel'],
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