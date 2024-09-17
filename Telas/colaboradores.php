<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1>Cadastro de Colaboradores</h1>
    </div>
        <form action="src/colaboradores/cadastrar_colaborador.php" method="post">
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
                <label for="txt_preco" class="form-label">Data de Nascimento</label>
                <input type="date" class="form-control" name="txt_preco" id="txt_preco" required>
            </div>
            <div class="col-sm-3">
                <label for="txt_quantidade" class="form-label">Documento:</label>
                <input type="text" class="form-control" name="txt_quantidade" id="txt_quantidade" required>
            </div>
            <div class="col-sm-3">
                <label for="txt_quantidade" class="form-label">RG:</label>
                <input type="text" class="form-control" name="txt_quantidade" id="txt_quantidade" required>
            </div>
            <div class="col-sm-3">
                <label for="txt_quantidade" class="form-label">Telefone:</label>
                <input type="number" class="form-control" name="txt_quantidade" id="txt_quantidade" required>
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
                    <th scope="col">Data de Nascimento</th>
                    <th scope="col">Documento</th>
                    <th scope="col">RG</th>
                    <th scope="col">Telefone</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    try
                    {
                        include_once 'src/class/BancodeDados.php';
                        $banco = new BancodeDados;
                        $sql = 'SELECT * FROM colaboradores';
                        $dados = $banco -> Consultar($sql,[], true);
                        if($dados)
                        {
                            foreach ($dados as $linha)
                            {
                                echo
                                "<tr'>
                                    <td>{$linha['id_colaborador']}</td>
                                    <td>{$linha['nome_colaborador']}</td>
                                    <td> R$ {$linha['data_nascimento']}</td>
                                    <td>{$linha['cpf_cnpj']}</td>
                                    <td>{$linha['rg']}</td>
                                    <td>{$linha['telefone']}</td>
                                    <td>
                                        <a href='index.php?tela=produtos&idProduto={$linha['id_produto']}'>Editar</a>
                                        <a href='#' onclick='ExcluirColaborador({$linha['id_produto']})'>Excluir</a>
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