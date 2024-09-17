<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1>Cadastro de Usuários</h1>
    </div>
        <form action="src/usuarios/cadastrar_usuario.php" method="post">
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
                <label for="txt_preco" class="form-label">Senha:</label>
                <input type="password" class="form-control" name="txt_senha" id="txt_senha" required>
            </div>
            <div class="col-sm-3">
                <label for="chk_administrador" class="form-label">Administrador:</label>
                <br>
                <input type="checkbox" name="txt_quantidade" id="chk_administrador" value="1">
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
                    <th scope="col">Senha</th>
                    <th scope="col">Administrador</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    try
                    {
                        include_once 'src/class/BancodeDados.php';
                        $banco = new BancodeDados;
                        $sql = 'SELECT * FROM usuarios';
                        $dados = $banco -> Consultar($sql,[], true);
                        if($dados)
                        {
                            foreach ($dados as $linha)
                            {
                                echo "<tr>
                                    <td>{$linha['id_usuario']}</td>
                                    <td>{$linha['nome_usuario']}</td>
                                    <td>{$linha['senha']}</td>
                                    <td><input type='checkbox' " . ($linha['administrador'] == 1 ? "checked" : "") . " disabled></td>
                                    <td>
                                        <a href='index.php?tela=produtos&idProduto={$linha['id_usuario']}'>Editar</a>
                                        <a href='#' onclick='ExcluirUsuario({$linha['id_usuario']})'>Excluir</a>
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