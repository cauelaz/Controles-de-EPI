<?php
    header('Content-Type: application/json');
    // Validação
    $formulario['id']         = isset($_POST['id'])         ? $_POST['id'] : '';
    $formulario['nome']       = isset($_POST['nome'])       ? $_POST['nome'] : '';
    $formulario['data_nasc']  = isset($_POST['data_nasc'])  ? $_POST['data_nasc'] : '';
    $formulario['cpf_cnpj']   = isset($_POST['cpf_cnpj'])   ? $_POST['cpf_cnpj'] : '';
    $formulario['rg']         = isset($_POST['rg'])         ? $_POST['rg'] : '';
    $formulario['telefone']   = isset($_POST['telefone'])   ? $_POST['telefone'] : '';
    if (in_array('', $formulario)) {
        echo json_encode([
           'codigo' => 0,
           'mensagem' => 'Existem dados faltando! Verifique.' 
        ]);
        exit;
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
                echo json_encode([
                    'codigo' => 3,
                    'mensagem' => 'CPF ou RG ja existente. Verifique!'
                ]);
                exit;
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
                echo json_encode([
                    'codigo' => 3,
                    'mensagem' => 'CPF ou RG ja existente. Verifique!'
                ]);
                exit;
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
                echo json_encode([
                   'codigo' => 0,
                   'mensagem' => "Erro ao realizar consulta: $msg_erro" 
                ]);
                exit;
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
            $banco->ExecutarComando($sql, $parametros);
            echo json_encode([
               'codigo' => 2,
               'mensagem' => 'Colaborador cadastrado com sucesso!'
            ]);
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
            $banco->ExecutarComando($sql, $parametros);
            echo json_encode([
               'codigo' => 2,
               'mensagem' => 'Colaborador atualizado com sucesso!'
            ]);
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
                $banco->ExecutarComando($sql, $parametros);
                echo json_encode([
                    'codigo' => 2,
                    'mensagem' => 'Colaborador atualizado com sucesso!'
                ]);
            }
        }
    } 
    catch (PDOException $erro) {
        $msg_erro = $erro->getMessage();
        echo json_encode([
           'codigo' => 0,
           'mensagem' => 'Erro ao realizar registro: ' . $msg_erro
        ]);
    }