<?php
header('Content-Type: application/json');

// Recebendo os dados do POST via Ajax
$formulario = [
    'id' => isset($_POST['id']) ? $_POST['id'] : '',
    'colaborador' => isset($_POST['colaborador']) ? $_POST['colaborador'] : '',
    'situacao' => isset($_POST['situacao']) ? $_POST['situacao'] : '',
    'dataEmprestimo' => isset($_POST['dataEmprestimo']) ? $_POST['dataEmprestimo'] : '',
    'dataDevolucao' => isset($_POST['dataDevolucao']) ? $_POST['dataDevolucao'] : '',
    'observacoes' => isset($_POST['observacoes']) ? $_POST['observacoes'] : '',
    'equipamentos' => isset($_POST['equipamentos']) ? $_POST['equipamentos'] : []
];

// Validando os dados
if (empty($formulario['colaborador']) || empty($formulario['situacao']) || empty($formulario['dataEmprestimo'])) {
    echo json_encode([
        'codigo' => 0,
        'mensagem' => 'Existem dados faltando! Verifique.'
    ]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $mensagem = '';
        include '../class/BancodeDados.php';
        $banco = new BancodeDados;

        if ($formulario['id'] == 'NOVO') {
            // Cadastrar empréstimo e equipamentos
            $idEmprestimo = CadastrarEmprestimo($formulario, $banco);
            if ($idEmprestimo != 0) {
                foreach ($formulario['equipamentos'] as $equipamento) {
                    CadastrarEquipamentosEmprestimo($idEmprestimo, $equipamento, $banco);
                }
            }
            $mensagem = 'Empréstimo cadastrado com sucesso!';
        } else {
            // Atualizar empréstimo (se necessário)
            $mensagem = 'Empréstimo atualizado com sucesso!';
        }

        echo json_encode([
            'codigo' => 2,
            'mensagem' => $mensagem
        ]);

    } catch (PDOException $erro) {
        $msg = $erro->getMessage();
        echo json_encode([
            'codigo' => 0,
            'mensagem' => "Erro ao realizar registro: $msg"
        ]);
    }
}

function CadastrarEmprestimo($formulario, $banco) : int {
    try {
        $sql = 'INSERT INTO emprestimos (colaborador, situacao, data_emprestimo, data_devolucao, observacoes)
                VALUES (?, ?, ?, ?, ?)
                RETURNING id_emprestimo';

        $parametros = [
            $formulario['colaborador'],
            $formulario['situacao'],
            $formulario['dataEmprestimo'],
            $formulario['dataDevolucao'],
            $formulario['observacoes']
        ];

        $id = $banco->ExecutarRetornandoId($sql, $parametros);
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

function CadastrarEquipamentosEmprestimo($idEmprestimo, $idEquipamento, $banco) {
    try {
        $sql = 'INSERT INTO emprestimo_equipamentos (id_emprestimo, id_equipamento) VALUES (?, ?)';

        $parametros = [
            $idEmprestimo,
            $idEquipamento
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
