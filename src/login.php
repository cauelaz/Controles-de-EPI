<?php
    // Validação
    $usuario = isset($_POST['txt_usuario']) ? $_POST['txt_usuario'] : '';
    $senha = isset($_POST['txt_senha']) ? $_POST['txt_senha'] : '';
    if (empty($usuario) || empty($senha)) {
        echo "<script>
            alert('Por favor preencha todos os campos!');
            window.location = '../index.php';
        </script>";
        exit;
    }

    // Banco de Dados
    try
    {
        include './class/BancoDeDados.php';
        $banco = new BancoDeDados; // Não é necessário '()' pois não é passado nenhum parametro
        
        // Definir o SQL e os parâmetros
        $sql = "SELECT id_usuario, nome
                FROM usuarios 
                WHERE usuario = ? AND senha = ?";
        $parametros = [ $usuario, $senha ];
        
        //Consultar os dados 
        $dados_usuario = $banco -> Consultar($sql,$parametros,true); // Se quiser FETCH normal, só deixa FALSE no lugar do TRUE.

        // Login
        if ($dados_usuario) {
            // Sessão
            session_start();
            $_SESSION['logado'] = true;
            $_SESSION['id_usuario'] = $dados_usuario['id_usuario'];
            $_SESSION['nome_usuario'] = $dados_usuario['nome'];

            // Cookies
            // ...

            // Redirecionar
            header('LOCATION: ../sistema.php');
        } else {
            echo "<script>
                alert('Usuário ou senha incorretos! Verifique.');
                window.location = '../index.php';
            </script>";
        }
    }
    catch(PDOException $erro)
    {
        $msg = $erro->getMessage();
        echo 
            "<script>
                alert(\"$msg\");
                window.location = '../index.php';
            </script>";
    }