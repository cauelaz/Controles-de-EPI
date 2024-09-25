<?php
    // Validação
    $formulario['id']         = isset($_POST['txt_id'])         ? $_POST['txt_id'] : '';
    $formulario['nome']       = isset($_POST['txt_nome'])       ? $_POST['txt_nome'] : '';
    $formulario['data_nasc']  = isset($_POST['txt_data_nasc'])  ? $_POST['txt_data_nasc'] : '';
    $formulario['cpf_cnpj']   = isset($_POST['txt_cpf_cnpj'])   ? $_POST['txt_cpf_cnpj'] : '';
    $formulario['rg']         = isset($_POST['txt_rg'])         ? $_POST['txt_rg'] : '';
    $formulario['telefone']   = isset($_POST['txt_telefone'])   ? $_POST['txt_telefone'] : '';
    if (in_array('', $formulario)) {
        echo "<script>
            alert('Existem dados faltando! Verifique.');
            window.location = '../sistema.php?tela=colaboradores';
        </script>";
        exit; 
    }

    try 
    {
        include_once '../class/BancoDeDados.php';
        $banco = new BancoDeDados;

        //Verificar se o sistema deve inserir ou atualizar cliente
        if($formulario['id'] == 'NOVO')
        {
            $sql = 'INSERT INTO colaboradores (nome_colaborador, data_nascimento, cpf_cnpj, rg, telefone, ativo) VALUES (?,?,?,?,?,1)';
            $parametros = [
                $formulario['nome'],
                $formulario['data_nasc'],
                $formulario['cpf_cnpj'],
                $formulario['rg'],
                $formulario['telefone']
            ];
            $msg_sucesso = 'Dados cadastrados com sucesso!';            
        }
        else
        {
            $sql = 'UPDATE colaboradores SET nome_colaborador = ?, data_nascimento = ?, cpf_cnpj = ?, rg = ?, telefone = ? WHERE id_colaborador = ?';
            $parametros = [
                $formulario['nome'],
                $formulario['data_nasc'],
                $formulario['cpf_cnpj'],
                $formulario['rg'],
                $formulario['telefone'],
                $formulario['id']
            ]; 
            $msg_sucesso = 'Dados alterados com sucesso!';
        }
        $banco -> ExecutarComando($sql, $parametros);
        // Msg de sucesso
        echo "<script>
            alert('$msg_sucesso');
            window.location = '../../sistema.php?tela=colaboradores';
        </script>";
    } catch (PDOException $erro) {
        $msg = $erro->getMessage();
        echo "<script>
            alert(\"$msg\");
            window.location = ../../sistema.php?tela=colaboradores';
        </script>";
    }
