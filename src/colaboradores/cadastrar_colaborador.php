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
            alert('Existem campo vázios. Verifique!');
            window.location = '../../sistema.php?tela=colaboradores';
        </script>"; 
    }
    // Banco de Dados
    try 
    {
        include_once '../class/BancoDeDados.php';
        $banco = new BancoDeDados;
        // Validar CPF
        if ($formulario['id'] == 'NOVO') 
        {
            $sql = 'SELECT COUNT(id_colaborador) as qtd FROM colaboradores WHERE (cpf_cnpj = ? or rg = ?)';
            $parametros = [$formulario['cpf_cnpj'], $formulario['rg']];
            $qtd = $banco->consultar($sql, $parametros);
            if ($qtd['qtd'] > 0) 
            {
                echo 
                "<script>
                    alert('CPF ou RG já existente. Verifique!');
                    window.location = '../../sistema.php?tela=colaboradores';
                </script>";
            }
        } 
        else 
        {
            $sql = 'SELECT COUNT(id_colaborador) as qtd FROM colaboradores WHERE (cpf_cnpj = ? or rg = ?) AND id_colaborador != ?';
            $parametros = [
                $formulario['cpf_cnpj'],
                $formulario['rg'],
                $formulario['id']];
            $qtd = $banco->consultar($sql, $parametros);

            if ($qtd['qtd'] > 0) 
            {
                echo 
                "<script>
                    alert('CPF ou RG já existente. Verifique!');
                    window.location = '../../sistema.php?tela=colaboradores';
                </script>";
            }
        }
        // Inserção da imagem na pasta UPLOAD
        if (isset($_FILES['file_imagem']) && $_FILES['file_imagem']['error'] == UPLOAD_ERR_OK) 
        {
            $nome_imagem = uniqid() . '.jpg';
            $destino = 'upload/' . $nome_imagem;
            $origem = $_FILES['file_imagem']['tmp_name'];
            move_uploaded_file($origem, $destino);
        } 
        else 
        {
            // Manter a imagem existente se nenhuma nova for enviada
            try
            {
                include_once '../class/BancoDeDados.php';
                $banco = new BancoDeDados;
                if ($formulario['id'] != 'NOVO') 
                {
                    $sql = 'SELECT imagem_colaborador FROM colaboradores WHERE id_colaborador = ?';
                    $parametros = [$formulario['id']];
                    $foto = $banco->consultar($sql, $parametros);
                    $nome_imagem = $foto['imagem_colaborador'];
                } 
                else 
                {
                    $nome_imagem = 'vazio'; 
                }
            } 
            catch (PDOException $erro)
            {
                $msg_erro = $erro->getMessage();
                echo "<script>
                alert(\"$msg_erro\");
                    window.location = '../../sistema.php?tela=colaboradores';
                </script>";
            }
        }
        if($formulario['id'] == 'NOVO')
        {
            $sql = 'INSERT INTO colaboradores (nome_colaborador, cpf_cnpj, data_nascimento, rg, ativo, telefone, imagem_colaborador) VALUES (?,?,?,?,?,?,?)';
            $parametros = [
                $formulario['nome'],
                $formulario['cpf_cnpj'],
                $formulario['data_nasc'],
                $formulario['rg'],
                1,
                $formulario['telefone'],
                $nome_imagem
            ];
            $msg_sucesso = 'Colaborador cadastrada com sucesso!';
        }
        else
        {
            $sql = 'SELECT imagem_colaborador FROM colaboradores WHERE id_colaborador = ?';
            $parametros = [$formulario['id']];
            $foto = $banco->consultar($sql, $parametros);
            if($foto['imagem_colaborador'] == $nome_imagem)
            {
                $sql = 'UPDATE colaboradores SET nome_colaborador = ?, cpf_cnpj = ?, data_nascimento = ?, rg = ?, telefone = ? WHERE id_colaborador = ?';
                $parametros = [
                    $formulario['nome'],
                    $formulario['cpf_cnpj'],
                    $formulario['data_nasc'],
                    $formulario['rg'],
                    $formulario['telefone'],
                    $formulario['id']
            ];
            $msg_sucesso = 'Colaborador atualizado com sucesso!';
            }
            else
            {
                if (file_exists("upload/" . $foto['imagem_colaborador'])) 
                {
                    if (unlink("upload/" . $foto['imagem_colaborador'])) 
                    {
                        echo 'Imagem excluída com sucesso.';
                    } else {
                        echo 'Erro ao excluir a imagem.';
                    }
                }
                $sql = 'UPDATE colaboradores SET nome_colaborador = ?, cpf_cnpj = ?, data_nascimento = ?, rg = ?, telefone = ?, imagem_colaborador = ? WHERE id_colaborador = ?';
                $parametros = [
                    $formulario['nome'],
                    $formulario['cpf_cnpj'],
                    $formulario['data_nasc'],
                    $formulario['rg'],
                    $formulario['telefone'],
                    $nome_imagem,
                    $formulario['id']
                ];
                $msg_sucesso = 'Colaborador atualizada com sucesso!';
            }
        }
        $banco->executarComando($sql, $parametros);
        // Sucesso
        echo 
        "<script>
            alert('$msg_sucesso');
            window.location = '../../sistema.php?tela=colaboradores';
        </script>";
    } 
    catch (PDOException $erro) {
        $msg_erro = $erro->getMessage();
        echo 
        "<script>
            alert(\"$msg_erro\");
            window.location = '../../sistema.php?tela=colaboradores';
        </script>";
    }