<?php
    // Validação
    $usuario = isset($_POST['txt_usuario']) ? $_POST['txt_usuario'] : '';
    $senha = isset($_POST['txt_senha']) ? $_POST['txt_senha'] : '';
    if (empty($usuario) || empty($senha)) 
    {
        echo 
        "<script>
            alert('Por favor preencha todos os campos!');
            window.location = '../index.php';
        </script>";
        exit;
    }
    // Banco de Dados
    try
    {
        include_once '../src/class/BancoDeDados.php';
        $banco = new BancoDeDados; // Não é necessário '()' pois não é passado nenhum parametro
        
        // Definir o SQL e os parâmetros
        $sql = "SELECT *
                FROM usuarios 
                WHERE nome_usuario = ? AND senha = ?";
        $parametros = [ $usuario, $senha ];
        
        //Consultar os dados 
        $dados_usuario = $banco -> Consultar($sql,$parametros,true); // Se quiser FETCH normal, só deixa FALSE no lugar do TRUE.
        if ($dados_usuario) {
            // Acessar o primeiro elemento do array
            $usuario_data = $dados_usuario[0]; // Acesso ao primeiro usuário
            // Sessão
            session_start();
            $_SESSION['logado'] = true;
            $_SESSION['id_user'] = $usuario_data["id_usuario"];
            $_SESSION['nome_usuario'] = $usuario_data["nome_usuario"]; // Acessando o nome corretamente
            header('Location: ../sistema.php');
            exit;
        }
        else 
        {    
            echo 
            "<script>
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