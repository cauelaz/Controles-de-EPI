<?php
    // Validação
    $formulario['id']         = isset($_POST['txt_id'])       ? $_POST['txt_id'] : '';
    $formulario['nome']       = isset($_POST['txt_nome'])     ? $_POST['txt_nome'] : '';
    $formulario['cpf']        = isset($_POST['txt_cpf'])      ? $_POST['txt_cpf'] : '';
    $formulario['cidade']     = isset($_POST['txt_cidade'])   ? $_POST['txt_cidade'] : '';
    $formulario['uf']         = isset($_POST['list_uf'])      ? $_POST['list_uf'] : '';
    if (in_array('', $formulario)) {
        echo "<script>
            alert('Existem dados faltando! Verifique.');
            window.location = '../sistema.php?tela=clientes';
        </script>";
        exit; // Termina o script
    }

    // Continuando
    
    // Banco de dados para cadastrar o cliente
    try {
        include 'class/BancoDeDados.php';
        $banco = new BancoDeDados;

        //Verificar se o sistema deve inserir ou atualizar cliente
        if($formulario['id'] == 'NOVO')
        {
            $sql = 'INSERT INTO clientes (nome, CPF, cidade, uf) VALUES (?, ?, ?, ?)';
            $parametros = [
                $formulario['nome'],
                $formulario['cpf'],
                $formulario['cidade'],
                $formulario['uf'],
            ];
            $msg_sucesso = 'Dados cadastrados com sucesso!';            
        }
        else
        {
            $sql = 'UPDATE clientes SET (nome, CPF, cidade, uf) VALUES (?, ?, ?, ?) WHERE id_cliente == ?';
            $parametros = [
                $formulario['nome'],
                $formulario['cpf'],
                $formulario['cidade'],
                $formulario['uf'],
                $formulario['id']
            ]; 
            $msg_sucesso = 'Dados alterados com sucesso!';
        }
        $banco -> ExecutarComando($sql, $parametros);

        // Msg de sucesso
        echo "<script>
            alert('$msg_sucesso');
            window.location = '../sistema.php?tela=clientes';
        </script>";
    } catch (PDOException $erro) {
        $msg = $erro->getMessage();
        echo "<script>
            alert(\"$msg\");
            window.location = '../sistema.php?tela=clientes';
        </script>";
    }
