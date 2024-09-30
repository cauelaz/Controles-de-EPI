<?php
    $formulario['id']           = isset($_POST['txt_id']) ? $_POST['txt_id'] : '';
    $formulario['descricao']    = isset($_POST['txt_descricao']) ? $_POST['txt_descricao'] : '';
    $formulario['qtd_estoque']        = isset($_POST['txt_estoque']) ? $_POST['txt_estoque'] : '';
    $formulario['cert_aprovacao']   = isset($_POST['txt_cert_aprovacao']) ? $_POST['txt_cert_aprovacao'] : '';
    if(in_array('', $formulario))
    {
        echo
        "<script>
            alert('Existem dados faltando! Verifique');
            window.location = '../../sistema.php?tela=equipamentos.php';
        </script>";
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
                    $sql = 'SELECT imagem_equipamento FROM equipamentos WHERE id_equipamento = ?';
                    $parametros = [$formulario['id']];
                    $foto = $banco->consultar($sql, $parametros);
                    $nome_imagem = $foto['imagem_equipamento'];
                } 
                else 
                {
                    $nome_imagem = ''; 
                }
            } 
            catch (PDOException $erro)
            {
                $msg_erro = $erro->getMessage();
                echo "<script>
                alert(\"$msg_erro\");
                    window.location = '../../sistema.php?tela=equipamentos';
                </script>";
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
            $msg_sucesso = 'Equipamento cadastrado com sucesso!';
        }
        else
        {
            // LEMBRAR DE MUDAR AQUI PARA O CERTO!!!!!!!!!!!
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
                $msg_sucesso = 'Equipamento atualizada com sucesso!';
            }                                                                   
            else
            {
                if (file_exists("upload/" . $foto['imagem_equipamento'])) 
                {
                    if (unlink("upload/" . $foto['imagem_equipamento'])) 
                    {
                        echo 'Imagem excluída com sucesso.';
                    } 
                    else 
                    {
                        echo 'Erro ao excluir a imagem.';
                    }
                }
                $sql = 'UPDATE equipamentos SET descricao = ?, qtd_estoque = ?, certificado_aprovacao = ?, imagem_equipamento = ? WHERE id_equipamento = ?';
                $parametros = [
                    $formulario['descricao'],
                    $formulario['qtd_estoque'],
                    $formulario['cert_aprovacao'],
                    $nome_imagem,
                    $formulario['id']
                ];
                $msg_sucesso = 'Equipamento atualizada com sucesso!';
            }
        }
        $banco -> ExecutarComando($sql, $parametros);
        echo
        "<script>
            alert('$msg_sucesso');
            window.location = '../../sistema.php?tela=equipamentos';
        </script>";
    }
    catch(PDOException $erro)
    {
        $msg = $erro->getMessage();
        echo
        "<script>
            alert(\"$msg\");
            window.location = '../../sistema.php?tela=equipamentos';
        </script>";
    }