<?php
    $formulario['id']           = isset($_POST['txt_id_estoque']) ? $_POST['txt_id_estoque'] : '';
    $formulario['descricao']    = isset($_POST['txt_descricao_estoque']) ? $_POST['txt_descricao_estoque'] : '';
    $formulario['qtd_estoque']  = isset($_POST['txt_estoque_estoque']) ? $_POST['txt_estoque_estoque'] : '';
    $formulario['qtd_ajuste']   = isset($_POST['txt_new_qtd_estoque']) ? $_POST['txt_new_qtd_estoque'] : '';
    if(in_array('',$formulario))
    {
        echo 
        "<script>
            alert('Existem dados faltando! Verifique.')
            window.location = '../../sistema.php?tela=equipamentos';
        </script>";
    }
    try
    {
        include_once '../class/BancoDeDados.php';
        $banco = new BancoDeDados;
        $sql = 'SELECT qtd_estoque FROM equipamentos WHERE id_equipamento = ?';
        $parametros = [$formulario['id']];
        $resultado = $banco->Consultar($sql,$parametros);
        $qtd_estoque = $resultado['qtd_estoque'];
        if($formulario['qtd_ajuste'] == 0)
        {
            $msg_sucesso = 'Não existem itens para ajuste!';
        }
        else 
        {
            // Converter $formulario['qtd_ajuste'] para número se necessário
            $qtd_ajuste = $qtd_estoque + (int)$formulario['qtd_ajuste'];
            $sql = 'UPDATE equipamentos SET qtd_estoque = ? WHERE id_equipamento = ?';
            $parametros = [
                $qtd_ajuste,
                $formulario['id']
            ];
            $msg_sucesso = 'Estoque atualizado com sucesso!';
        }
        $banco->ExecutarComando($sql,$parametros);
        echo
        "<script>
            alert('$msg_sucesso');
            window.location = '../../sistema.php?tela=equipamentos';
        </script>";
    }
    catch(PDOException $erro)
    {
        echo $erro->getMessage();
        echo 
        "<script>
            alert(\"$msg\");
            window.location = '../../sistema.php?tela=equipamentos';
        </script";
    }