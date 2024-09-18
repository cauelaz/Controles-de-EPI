<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1>Empréstimos</h1>
    </div>
        <form action="src/equipamentos/cadastrar_equipamento.php" method="post">
        <div class="row g-4">
            <div class=col-sm-3>
                <label for="txt_id" class="form-label">ID:</label>
                <input type="text" class="form-control" name="txt_id" id="txt_id" value="NOVO" required readonly>
            </div>
            <div class="col-sm-3">
                <label for="txt_nome"class="form-label">Nome:</label>
                <input type="text" class="form-control" name="txt_nome" id="txt_nome" maxlength="255" required>
            </div>
            <div class="col-sm-3">
                <label for="txt_preco" class="form-label">Preço de Venda:</label>
                <input type="tex" class="form-control" name="txt_preco" id="txt_preco" required>
            </div>
            <div class="col-sm-3">
                <label for="txt_quantidade" class="form-label">Qtd. em Estoque:</label>
                <input type="text" class="form-control" name="txt_quantidade" id="txt_quantidade" required>
            </div>
            <div  class="col-sm-15">
                <button class="w-100 btn btn-primary btn-lg" type="submit">Salvar</button>
            </div>
        </form>
    </div>
    <hr class="my-4">
    <div class="table-responsive">
        <table class="table table-striped table-hover">  
            <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Nome</th>
                    <th scope="col">Preço de venda</th>
                    <th scope="col">Qtd. em Estoque</th>
                    <th scope="col">Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    try
                    {
                        include_once 'src/class/BancodeDados.php';
                        $banco = new BancodeDados;
                        $sql = 'SELECT * FROM emprestimos';
                        $dados = $banco -> Consultar($sql,[], true);
                        if($dados)
                        {
                            foreach ($dados as $linha)
                            {
                                echo
                                "<tr'>
                                    <td>{$linha['id_produto']}</td>
                                    <td>{$linha['nome']}</td>
                                    <td> R$ {$linha['precodevenda']}</td>
                                    <td>{$linha['quantidade']}</td>
                                    <td>
                                        <a href='index.php?tela=produtos&idProduto={$linha['id_produto']}'>Editar</a>
                                        <a href='#' onclick='excluir({$linha['id_produto']})'>Excluir</a>
                                        <a href='index.php?tela=estoque&IdEquipamento={$linha['id_produto']}'>Estoque</a>
                                    </td>
                                </tr>";
                            }
                        }
                        else
                        {
                            echo 
                            "<tr>
                                <td colspan = '6' class='text-center'> Nenhum cliente cadastrado...</td>
                            </tr>";                   
                        }
                    }
                    catch(PDOException $erro)
                    {
                        $msg = $erro->getMessage();
                        echo
                        "<script>
                            alert(\"$msg\");
                        </script>";
                    }
                ?>
            </tbody>
        </table>
    </div>