<?php
header('Content-Type: application/json');

// Recebendo os dados do POST via Ajax
$formulario = [
    'id' => isset($_POST['id']) ? $_POST['id'] : '',
    'colaborador' => isset($_POST['colaborador']) ? $_POST['colaborador'] : '',
    'situacao' => isset($_POST['situacao']) ? $_POST['situacao'] : '',
    'data_emprestimo' => isset($_POST['data_emprestimo']) ? $_POST['data_emprestimo'] : '',
    'data_devolucao' => isset($_POST['data_devolucao']) ? $_POST['data_devolucao'] : '',
    'observacao' => isset($_POST['observacao']) ? $_POST['observacao'] : '',
    'equipamentos' => isset($_POST['equipamentos']) ? $_POST['equipamentos'] : []
];

// Validando os dados
if (empty($formulario['colaborador']) || empty($formulario['situacao']) || empty($formulario['data_emprestimo'])) {
    echo json_encode([
        'codigo' => 0,
        'mensagem' => 'Existem dados faltando! Verifique.'
    ]);
    exit;
}

try {
    include '../class/BancodeDados.php';
    $banco = new BancodeDados;

    // SQL para inserir ou atualizar com base no ID
    $sql = 'INSERT INTO emprestimos (id_emprestimo, colaborador, situacao, data_emprestimo, data_devolucao, observacao)
            VALUES (?, ?, ?, ?, ?, ?)
            ON DUPLICATE KEY UPDATE
            colaborador = VALUES(colaborador),
            situacao = VALUES(situacao),
            data_emprestimo = VALUES(data_emprestimo),
            data_devolucao = VALUES(data_devolucao),
            observacao = VALUES(observacao)';

    $parametros = [
        $formulario['id'] == 'NOVO' ? null : $formulario['id'], // Se for "NOVO", insere null para auto incremento
        $formulario['colaborador'],
        $formulario['situacao'],
        $formulario['data_emprestimo'],
        $formulario['data_devolucao'],
        $formulario['observacao']
    ];

    // Executa o comando no banco
    $banco->ExecutarComando($sql, $parametros);

    // Se foi inserido um novo registro, pega o último ID gerado
    $emprestimo_id = $formulario['id'] == 'NOVO' ? $banco->UltimoId() : $formulario['id'];

    // Inserir os equipamentos associados ao empréstimo
    if (!empty($formulario['equipamentos'])) {
        foreach ($formulario['equipamentos'] as $equipamento) {
            $sql_equip = 'INSERT INTO emprestimo_equipamentos (id_emprestimo, id_equipamento) VALUES (?, ?)
                          ON DUPLICATE KEY UPDATE id_equipamento = VALUES(id_equipamento)';
            $banco->ExecutarComando($sql_equip, [$emprestimo_id, $equipamento]);
        }
    }

    echo json_encode([
        'codigo' => 2,
        'mensagem' => 'Empréstimo cadastrado ou atualizado com sucesso!'
    ]);
    
} catch (PDOException $erro) {
    $msg = $erro->getMessage();
    echo json_encode([
        'codigo' => 0,
        'mensagem' => "Erro ao realizar registro: $msg"
    ]);
}
