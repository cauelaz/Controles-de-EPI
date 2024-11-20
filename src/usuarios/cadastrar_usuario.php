<?php
    header('Content-Type: application/json');
    $formulario['id']    = isset($_POST['id'])        ? $_POST['id'] : '';
    $formulario['nome']  = isset($_POST['usuario'])   ? $_POST['usuario'] : '';
    $formulario['senha'] = isset($_POST['senha'])     ? $_POST['senha'] : '';
    $formulario['adm']   = isset($_POST['adm'])       ? $_POST['adm'] : '0';
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
        $sql = 'SELECT COUNT(id_usuario) AS total FROM usuarios WHERE nome_usuario = ?';
        $parametros = [ $formulario['nome'] ];
        $total = $banco -> Consultar($sql,$parametros,true);
        if($total[0]['total'] > 0)
        {
            echo json_encode([
                'codigo' => 0,
                'mensagem' => 'Usuário ja existe!'
            ]);
            exit;
        }
        if($formulario['id'] == 'NOVO')
        {
            $sql = 'INSERT INTO  usuarios (nome_usuario, senha, administrador, ativo) VALUES (?,?,?,1)';
            $parametros =
            [
                $formulario['nome'],
                $formulario['senha'],
                $formulario['adm'],
            ];
            echo json_encode([
                'codigo' => 2,
                'mensagem' => 'Usuário cadastrado com sucesso!'
            ]);
        }
        else
        {
            $sql = 'UPDATE usuarios SET nome_usuario = ?, senha = ?, administrador = ? WHERE id_usuario = ?';
            $parametros =
            [
                $formulario['nome'],
                $formulario['senha'],
                $formulario['adm'],
                $formulario['id']
            ];
            echo json_encode([
                'codigo' => 2,
                'mensagem' => 'Usuário atualizado com sucesso!'
            ]);
        }
        $banco -> ExecutarComando($sql, $parametros);
    }
    catch(PDOException $erro)
    {
        $msg = $erro->getMessage();
        echo json_encode([
            'codigo' => 0,
            'mensagem' => "Erro ao realizar registro: $msg"
        ]);
    }