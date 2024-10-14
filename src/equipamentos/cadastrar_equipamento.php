<?php
    header('Content-Type: application/json');
    $formulario['id']             = isset($_POST['id'])             ? $_POST['id'] : '';
    $formulario['descricao']      = isset($_POST['descricao'])      ? $_POST['descricao'] : '';
    $formulario['qtd_estoque']    = isset($_POST['estoque'])        ? $_POST['estoque'] : '';
    $formulario['cert_aprovacao'] = isset($_POST['cert_aprovacao']) ? $_POST['cert_aprovacao'] : '';
    if(in_array('', $formulario))
    {
        echo json_encode([
            'codigo'=> 0,
            'mensagem' => 'Existem dados faltando! Verifique.'
        ]);
        exit;
    }
    try
    {
        include '../class/BancodeDados.php';
        $banco = new BancodeDados;
        // Inserção da imagem na pasta UPLOAD
        if (isset($_FILES['file_imagem']) && $_FILES['file_imagem']['error'] == UPLOAD_ERR_OK) 
        {
            $nome_imagem = uniqid() . '.jpg';
            $destino = 'upload/' . $nome_imagem;
            // Criar diretório se não existir
            if (!is_dir('upload'))
            {
                mkdir('upload', 0777, true);
            }
            // Mover o arquivo de upload para o destino
            if (!move_uploaded_file($_FILES['file_imagem']['tmp_name'], $destino)) {
                echo json_encode([
                    'codigo' => 0,
                    'mensagem' => 'Erro ao fazer upload da imagem.'
                ]);
                exit;
            }
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
                    $sql = 'SELECT imagem_equipamento FROM equipamentos WHERE id_equipamento = ?';
                    $parametros = [$formulario['id']];
                    $foto = $banco->consultar($sql, $parametros);
                    $nome_imagem = $foto['imagem_equipamento'];
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
                    'codigo' => 3,
                    'mensagem' => "Erro ao realizar consulta de imagem: $msg_erro"
                ]);
                exit;
            }
        }
        if($formulario['id'] == 'NOVO')
        {
            $sql = 'INSERT INTO  equipamentos (descricao, qtd_estoque, certificado_aprovacao, ativo, imagem_equipamento) VALUES (?,?,?,?,?)';
            $parametros =
            [
                $formulario['descricao'],
                $formulario['qtd_estoque'],
                $formulario['cert_aprovacao'],
                1,
                $nome_imagem
            ];
            $banco->ExecutarComando($sql, $parametros);
            echo json_encode([
                'codigo' => 2,
                'mensagem' => 'Equipamento cadastrado com sucesso!'
            ]);
        }
        else
        {
            $sql = 'SELECT imagem_equipamento FROM equipamentos WHERE id_equipamento = ?';
            $parametros = [$formulario['id']];
            $foto = $banco->consultar($sql, $parametros);
            if($foto['imagem_equipamento'] == $nome_imagem)
            {
                $sql = 'UPDATE equipamentos SET descricao = ?, qtd_estoque = ?, certificado_aprovacao = ? WHERE id_equipamento = ?';
                $parametros = [
                    $formulario['descricao'],
                    $formulario['qtd_estoque'],
                    $formulario['cert_aprovacao'],
                    $formulario['id']
                ];
                echo json_encode([
                    'codigo' => 2,
                    'mensagem' => 'Equipamento atualizado com sucesso!'
                ]);
            }                                                                   
            else
            {
                if (file_exists("upload/" . $foto['imagem_equipamento'])) 
                {
                    (unlink("upload/" . $foto['imagem_equipamento']));
                }
                $sql = 'UPDATE equipamentos SET descricao = ?, qtd_estoque = ?, certificado_aprovacao = ?, imagem_equipamento = ? WHERE id_equipamento = ?';
                $parametros = [
                    $formulario['descricao'],
                    $formulario['qtd_estoque'],
                    $formulario['cert_aprovacao'],
                    $nome_imagem,
                    $formulario['id']
                ];
                echo json_encode([
                   'codigo' => 2,
                   'mensagem' => 'Equipamento atualizado com sucesso!'
                ]);
            }
            $banco->ExecutarComando($sql, $parametros);
        }
    }
    catch(PDOException $erro)
    {
        $msg = $erro->getMessage();
        echo json_encode([
            'codigo' => 0,
            'mensagem' => "Erro ao realizar registro: $msg"
        ]);
    }