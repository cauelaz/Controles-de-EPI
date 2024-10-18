<?php
    header('Content-Type: application/json');
    // Validação
    $formulario['id']           = isset($_POST['id'])           ? $_POST['id'] : '';
    $formulario['nome']         = isset($_POST['nome'])         ? $_POST['nome'] : '';
    $formulario['data_nasc']    = isset($_POST['data_nasc'])    ? $_POST['data_nasc'] : '';
    $formulario['cpf_cnpj']     = isset($_POST['cpf_cnpj'])     ? $_POST['cpf_cnpj'] : '';
    $formulario['rg']           = isset($_POST['rg'])           ? $_POST['rg'] : '';
    $formulario['telefone']     = isset($_POST['telefone'])     ? $_POST['telefone'] : '';
    $formulario['departamento'] = isset($_POST['departamento']) ? $_POST['departamento'] : '';
    $formulario['cep']          = isset($_POST['cep'])          ? $_POST['cep'] : '';
    $formulario['rua']          = isset($_POST['rua'])          ? $_POST['rua'] : '';
    $formulario['numero']       = isset($_POST['numero'])       ? $_POST['numero'] : '';
    $formulario['bairro']       = isset($_POST['bairro'])       ? $_POST['bairro'] : '';
    $formulario['cidade']       = isset($_POST['cidade'])       ? $_POST['cidade'] : '';
    $formulario['uf']           = isset($_POST['uf'])           ? $_POST['uf'] : '';
    $formulario['complemento']  = isset($_POST['complemento'])  ? $_POST['complemento'] : '';
    if (in_array('', $formulario)) {
        echo json_encode([
           'codigo' => 0,
           'mensagem' => 'Existem dados faltando! Verifique.' 
        ]);
        exit;
    }
    try 
    {
        if($formulario['departamento'] == '0')
        {
            $formulario['departamento'] = null;
        }
        include_once '../class/BancoDeDados.php';
        $banco = new BancoDeDados;
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
        if ($formulario['id'] == 'NOVO') {
            // Inserir na tabela colaboradores
            $sql = 'INSERT INTO colaboradores (nome_colaborador, cpf_cnpj, data_nascimento, rg, ativo, telefone, imagem_colaborador, id_departamento) VALUES (?,?,?,?,?,?,?,?)';
            $parametros = [
                $formulario['nome'],
                $formulario['cpf_cnpj'],
                $formulario['data_nasc'],
                $formulario['rg'],
                1,  // Ativo
                $formulario['telefone'],
                $nome_imagem,
                $formulario['departamento']
            ];
            $banco->ExecutarComando($sql, $parametros);
            // Obter o ID do colaborador recém-inserido
            $id_colaborador = $banco->getLastInsertId();
            // Inserir na tabela de endereço
            $sql_endereco = 'INSERT INTO endereco_colaborador (fk_id_colaborador, rua, numero, bairro, cidade, uf, cep, complemento) VALUES (?, ?, ?, ?, ?, ?, ?, ?)';
            $parametros_endereco = [
                $id_colaborador,
                $formulario['rua'],
                $formulario['numero'],
                $formulario['bairro'],
                $formulario['cidade'],
                $formulario['uf'],
                $formulario['cep'],
                $formulario['complemento']
            ];
            $banco->ExecutarComando($sql_endereco, $parametros_endereco);
            echo json_encode([
                'codigo' => 2,
                'mensagem' => 'Colaborador cadastrado com sucesso!'
            ]);
        }
        else {
            // Consultar a imagem atual do colaborador
            $sql = 'SELECT imagem_colaborador FROM colaboradores WHERE id_colaborador = ?';
            $parametros = [$formulario['id']];
            $foto = $banco->consultar($sql, $parametros);
        
            // Verificar se a imagem foi atualizada
            if ($foto['imagem_colaborador'] == $nome_imagem) {
                $sql = 'UPDATE colaboradores SET nome_colaborador = ?, cpf_cnpj = ?, data_nascimento = ?, rg = ?, telefone = ?, id_departamento = ? WHERE id_colaborador = ?';
                $parametros = [
                    $formulario['nome'],
                    $formulario['cpf_cnpj'],
                    $formulario['data_nasc'],
                    $formulario['rg'],
                    $formulario['telefone'],
                    $formulario['departamento'],
                    $formulario['id']
                ];
            } else {
                // Excluir a imagem antiga se a nova foi enviada
                if (file_exists("upload/" . $foto['imagem_colaborador'])) {
                    unlink("upload/" . $foto['imagem_colaborador']);
                }
                $sql = 'UPDATE colaboradores SET nome_colaborador = ?, cpf_cnpj = ?, data_nascimento = ?, rg = ?, telefone = ?, imagem_colaborador = ?, id_departamento = ? WHERE id_colaborador = ?';
                $parametros = [
                    $formulario['nome'],
                    $formulario['cpf_cnpj'],
                    $formulario['data_nasc'],
                    $formulario['rg'],
                    $formulario['telefone'],
                    $nome_imagem,
                    $formulario['departamento'],
                    $formulario['id']
                ];
            }
            // Executar a atualização do colaborador
            $banco->ExecutarComando($sql, $parametros);
            // Atualizar ou inserir o endereço
            $sql_endereco = 'SELECT * FROM endereco_colaborador WHERE fk_id_colaborador = ?';
            $parametros_endereco = [$formulario['id']];
            $endereco = $banco->consultar($sql_endereco, $parametros_endereco);
            if ($endereco) {
                // Atualizar o endereço existente
                $sql_endereco_update = 'UPDATE endereco_colaborador SET rua = ?, numero = ?, bairro = ?, cidade = ?, uf = ?, cep = ?, complemento = ? WHERE fk_id_colaborador = ?';
                $parametros_endereco_update = [
                    $formulario['rua'],
                    $formulario['numero'],
                    $formulario['bairro'],
                    $formulario['cidade'],
                    $formulario['uf'],
                    $formulario['cep'],
                    $formulario['complemento'],
                    $formulario['id']
                ];
                $banco->ExecutarComando($sql_endereco_update, $parametros_endereco_update);
            } else {
                // Inserir um novo endereço
                $sql_endereco_insert = 'INSERT INTO endereco_colaborador (fk_id_colaborador, rua, numero, bairro, cidade, uf, cep, complemento) VALUES (?, ?, ?, ?, ?, ?, ?, ?)';
                $parametros_endereco_insert = [
                    $formulario['id'],
                    $formulario['rua'],
                    $formulario['numero'],
                    $formulario['bairro'],
                    $formulario['cidade'],
                    $formulario['uf'],
                    $formulario['cep'],
                    $formulario['complemento']
                ];
                $banco->ExecutarComando($sql_endereco_insert, $parametros_endereco_insert);
            }
            echo json_encode([
                'codigo' => 2,
                'mensagem' => 'Colaborador atualizado com sucesso!'
            ]);
        }    
    } 
    catch (PDOException $erro) {
        $msg_erro = $erro->getMessage();
        echo json_encode([
           'codigo' => 0,
           'mensagem' => 'Erro ao realizar registro: ' . $msg_erro
        ]);
    }