<?php
session_start();
header('Content-Type: application/json'); // Assegura que o conteúdo seja enviado como JSON

// Validação
$usuario = isset($_POST['usuario']) ? $_POST['usuario'] : '';
$senha = isset($_POST['senha']) ? $_POST['senha'] : '';

if (empty($usuario) || empty($senha)) {
    echo json_encode([
        'codigo' => 0, // Código 0 para erro de campos vazios
        'mensagem' => 'Por favor preencha todos os campos!'
    ]);
    exit;
}

// Banco de Dados
try {
    include_once '../src/class/BancoDeDados.php';
    $banco = new BancoDeDados; // Não é necessário '()' pois não é passado nenhum parâmetro

    // Definir o SQL e os parâmetros
    $sql = "SELECT * FROM usuarios WHERE nome_usuario = ? AND senha = ?";
    $parametros = [$usuario, $senha];

    // Consultar os dados
    $dados_usuario = $banco->Consultar($sql, $parametros, true); // Se quiser FETCH normal, só deixa FALSE no lugar do TRUE.

    if ($dados_usuario) {
        // Acessar o primeiro elemento do array
        $usuario_data = $dados_usuario[0]; // Acesso ao primeiro usuário

        // Sessão
        $_SESSION['logado'] = true;
        $_SESSION['id_user'] = $usuario_data["id_usuario"];
        $_SESSION['nome_usuario'] = $usuario_data["nome_usuario"]; // Acessando o nome corretamente

        echo json_encode([
            'codigo' => 2 // Código 2 para sucesso
        ]);
        exit;
    } else {
        echo json_encode([
            'codigo' => 1, // Código 1 para erro de credenciais
            'mensagem' => 'Usuário ou senha incorretos! Verifique.'
        ]);
        exit;
    }
} catch (PDOException $erro) {
    $msg = $erro->getMessage();
    echo json_encode([
        'codigo' => 0, // Código 0 para erro geral
        'mensagem' => "Erro ao realizar o login: $msg"
    ]);
}
