<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1>Cadastro de Usuários</h1>
</div>
<div class="col-sm-6">
    <button onclick="abrirModal()" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#adicionar_usuario">
        <i class="bi bi-plus"></i> Novo Usuário
    </button>
</div>
<hr class="my-4">
<div class="table-responsive">
    <table class="table table-striped table-hover">
        <thead>
            <tr>
                <th scope="col">ID</th>
                <th scope="col">Nome</th>
                <th scope="col">Administrador</th>
                <th scope="col">Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php
                try {
                    include_once 'src/class/BancodeDados.php';
                    $banco = new BancodeDados;
                    $sql = 'SELECT * FROM usuarios WHERE ativo = 1 ';
                    $dados = $banco->Consultar($sql, [], true);
                    if ($dados) {
                        foreach ($dados as $linha) {
                            echo "<tr>
                                <td>{$linha['id_usuario']}</td>
                                <td>{$linha['nome_usuario']}</td>
                                <td><input type='checkbox' " . ($linha['administrador'] == 1 ? "checked" : "") . " disabled></td>
                                <td>
                                    <a href='sistema.php?acao=alterar&IdUsuario={$linha['id_usuario']}'>Editar</a>
                                    <a href='#' onclick='ExcluirUsuario({$linha['id_usuario']})'>Excluir</a>
                                </td>
                            </tr>";
                        }
                    } else {
                        echo "<tr>
                            <td colspan='5' class='text-center'>Nenhum usuário cadastrado...</td>
                        </tr>";
                    }
                } catch (PDOException $erro) {
                    $msg = $erro->getMessage();
                    echo "<script>
                        alert(\"$msg\");
                    </script>";
                }
            ?>
        </tbody>
    </table>
</div>
<!--Modal-->
<div id="adicionar_usuario" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="form_usuario" method="post" action="src/usuarios/cadastrar_usuario.php">
                <div class="modal-header">
                    <h4 class="modal-title" id="modalLabel">Novo Usuário</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="txt_id" id="txt_id" value="NOVO">
                    <div class="form-group">
                        <label for="txt_nome">Nome</label>
                        <input type="text" class="form-control" name="txt_nome" id="txt_nome" required>
                    </div>
                    <div class="form-group">
                        <label for="txt_senha">Senha</label>
                        <input type="password" class="form-control" name="txt_senha" id="txt_senha" required>
                    </div>
                    <div class="form-group">
                        <label for="txt_administrador">Administrador</label>
                        <input type="checkbox" name="chk_administrador" id="chk_administrador" value="1">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    function abrirModal() 
    {
        $('#adicionar-produto').modal('show');
    }
</script>