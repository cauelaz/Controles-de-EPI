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
    $formulario['cep']          = isset($_POST['cep'])          ? $_POST['cep'] : null;
    $formulario['rua']          = isset($_POST['rua'])          ? $_POST['rua'] : null;
    $formulario['numero']       = isset($_POST['numero'])       ? $_POST['numero'] : null;
    $formulario['bairro']       = isset($_POST['bairro'])       ? $_POST['bairro'] : null;
    $formulario['cidade']       = isset($_POST['cidade'])       ? $_POST['cidade'] : null;
    $formulario['uf']           = isset($_POST['uf'])           ? $_POST['uf'] : null;
    $formulario['complemento']  = isset($_POST['complemento'])  ? $_POST['complemento'] : null;

    // Validação apenas dos campos obrigatórios
    if (empty($formulario['nome']) || empty($formulario['cpf_cnpj'])) {
        echo json_encode([
           'codigo' => 0,
           'mensagem' => 'Nome e CPF/CNPJ são obrigatórios!'
        ]);
        exit;
    }
    try 
    {
        if($formulario['departamento'] == '0') {
            $formulario['departamento'] = null;
        }

        include_once '../class/BancoDeDados.php';
        $banco = new BancoDeDados;
        if ($formulario['id'] == 'NOVO') 
        {
            // Verificar se CPF ou RG já existe
            $sql = 'SELECT COUNT(id_colaborador) as qtd FROM colaboradores WHERE (cpf_cnpj = ? or rg = ?)';
            $parametros = [$formulario['cpf_cnpj'], $formulario['rg']];
            $qtd = $banco->consultar($sql, $parametros);

            if ($qtd['qtd'] > 0) 
            {
                echo json_encode([
                    'codigo' => 3,
                    'mensagem' => 'CPF ou RG já existente. Verifique!'
                ]);
                exit;
            }
        } 
        else 
        {
            // Verificar se CPF ou RG já existe para outro colaborador
            $sql = 'SELECT COUNT(id_colaborador) as qtd FROM colaboradores WHERE (cpf_cnpj = ? or rg = ?) AND id_colaborador != ?';
            $parametros = [
                $formulario['cpf_cnpj'],
                $formulario['rg'],
                $formulario['id']
            ];
            $qtd = $banco->consultar($sql, $parametros);

            if ($qtd['qtd'] > 0) 
            {
                echo json_encode([
                    'codigo' => 3,
                    'mensagem' => 'CPF ou RG já existente. Verifique!'
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
        if ($formulario['id'] == 'NOVO') 
        {
            // Inserir colaborador
            $sql = 'INSERT INTO colaboradores (nome_colaborador, cpf_cnpj, data_nascimento, rg, ativo, telefone, imagem_colaborador, id_departamento) VALUES (?,?,?,?,?,?,?,?)';
            $parametros = [
                $formulario['nome'],
                $formulario['cpf_cnpj'],
                $formulario['data_nasc'] ?: null,
                $formulario['rg'] ?: null,
                1,  // Ativo
                $formulario['telefone'] ?: null,
                $nome_imagem,
                $formulario['departamento'] ?: null
            ];
            $banco->ExecutarComando($sql, $parametros);
            // Obter o ID do colaborador recém-inserido
            $id_colaborador = $banco->getLastInsertId();
            // Inserir endereço se fornecido
            if (!empty($formulario['rua']) || !empty($formulario['numero']) || !empty($formulario['bairro']) || !empty($formulario['cidade']) || !empty($formulario['uf']) || !empty($formulario['cep']) || !empty($formulario['complemento'])) 
            {
                $sql_endereco = 'INSERT INTO endereco_colaborador (fk_id_colaborador, rua, numero, bairro, cidade, uf, cep, complemento) VALUES (?, ?, ?, ?, ?, ?, ?, ?)';
                $parametros_endereco = [
                    $id_colaborador,
                    $formulario['rua'] ?: null,
                    $formulario['numero'] ?: null,
                    $formulario['bairro'] ?: null,
                    $formulario['cidade'] ?: null,
                    $formulario['uf'] ?: null,
                    $formulario['cep'] ?: null,
                    $formulario['complemento'] ?: null
                ];
                $banco->ExecutarComando($sql_endereco, $parametros_endereco);
            }
            echo json_encode([
                'codigo' => 2,
                'mensagem' => 'Colaborador cadastrado com sucesso!'
            ]);
        }
        else 
        {
            // Atualizar colaborador
            $sql = 'SELECT imagem_colaborador FROM colaboradores WHERE id_colaborador = ?';
            $parametros = [$formulario['id']];
            $foto = $banco->consultar($sql, $parametros);
            if ($foto['imagem_colaborador'] == $nome_imagem) 
            {
                $sql = 'UPDATE colaboradores SET nome_colaborador = ?, cpf_cnpj = ?, data_nascimento = ?, rg = ?, telefone = ?, id_departamento = ? WHERE id_colaborador = ?';
                $parametros = [
                    $formulario['nome'],
                    $formulario['cpf_cnpj'],
                    $formulario['data_nasc'] ?: null,
                    $formulario['rg'] ?: null,
                    $formulario['telefone'] ?: null,
                    $formulario['departamento'] ?: null,
                    $formulario['id']
                ];
                echo json_encode([
                    'codigo' => 2,
                    'mensagem' => 'Colaborador atualizado com sucesso!'
                ]);
            } 
            else 
            {
                if (file_exists("upload/" . $foto['imagem_colaborador'])) 
                {
                    unlink("upload/" . $foto['imagem_colaborador']);
                }

                $sql = 'UPDATE colaboradores SET nome_colaborador = ?, cpf_cnpj = ?, data_nascimento = ?, rg = ?, telefone = ?, imagem_colaborador = ?, id_departamento = ? WHERE id_colaborador = ?';
                $parametros = [
                    $formulario['nome'],
                    $formulario['cpf_cnpj'],
                    $formulario['data_nasc'] ?: null,
                    $formulario['rg'] ?: null,
                    $formulario['telefone'] ?: null,
                    $nome_imagem,
                    $formulario['departamento'] ?: null,
                    $formulario['id']
                ];
                echo json_encode([
                    'codigo' => 2,
                    'mensagem' => 'Colaborador atualizado com sucesso!'
                ]);
            }
            // Atualizar ou inserir endereço
            $sql_endereco = 'SELECT * FROM endereco_colaborador WHERE fk_id_colaborador = ?';
            $parametros_endereco = [$formulario['id']];
            $endereco = $banco->consultar($sql_endereco, $parametros_endereco);
            if ($endereco) 
            {
                $sql_endereco_update = 'UPDATE endereco_colaborador SET rua = ?, numero = ?, bairro = ?, cidade = ?, uf = ?, cep = ?, complemento = ? WHERE fk_id_colaborador = ?';
                $parametros_endereco_update = [
                    $formulario['rua'] ?: null,
                    $formulario['numero'] ?: null,
                    $formulario['bairro'] ?: null,
                    $formulario['cidade'] ?: null,
                    $formulario['uf'] ?: null,
                    $formulario['cep'] ?: null,
                    $formulario['complemento'] ?: null,
                    $formulario['id']
                ];
                $banco->ExecutarComando($sql_endereco_update, $parametros_endereco_update);
            } 
            else 
            {
                if (!empty($formulario['rua']) || !empty($formulario['numero']) || !empty($formulario['bairro']) || !empty($formulario['cidade']) || !empty($formulario['uf']) || !empty($formulario['cep']) || !empty($formulario['complemento'])) 
                {
                    $sql_endereco_insert = 'INSERT INTO endereco_colaborador (fk_id_colaborador, rua, numero, bairro, cidade, uf, cep, complemento) VALUES (?, ?, ?, ?, ?, ?, ?, ?)';
                    $parametros_endereco_insert = [
                        $formulario['id'],
                        $formulario['rua'] ?: null,
                        $formulario['numero'] ?: null,
                        $formulario['bairro'] ?: null,
                        $formulario['cidade'] ?: null,
                        $formulario['uf'] ?: null,
                        $formulario['cep'] ?: null,
                        $formulario['complemento'] ?: null
                    ];
                    $banco->ExecutarComando($sql_endereco_insert, $parametros_endereco_insert);
                    echo json_encode([
                        'codigo' => 2,
                        'mensagem' => 'Colaborador atualizado com sucesso! 216'
                    ]);
                }
            }
            $banco->ExecutarComando($sql, $parametros);
        }
    } 
    catch (PDOException $erro) 
    {
        echo json_encode([
           'codigo' => 0,
           'mensagem' => 'Erro ao realizar registro: ' . $erro->getMessage()
        ]);
    }