<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1>Usuários</h1>
</div>
<div class="col-sm-6">
    <button onclick="abrirModal()" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#adicionar_usuario">
        <i class="bi bi-plus"></i> Novo Usuário
    </button>
</div>
<hr class="my-4">
<!-- Abas para Ativos e Inativos -->
<ul class="nav nav-tabs" id="colaboradoresTab" role="tablist">
    <li class="nav-item" role="presentation">
        <a class="nav-link active" id="ativos-tab" data-bs-toggle="tab" href="#ativos" role="tab" aria-controls="ativos" aria-selected="true">Ativos</a>
    </li>
    <li class="nav-item" role="presentation">
        <a class="nav-link" id="inativos-tab" data-bs-toggle="tab" href="#inativos" role="tab" aria-controls="inativos" aria-selected="false">Inativos</a>
    </li>
</ul>
<!-- Conteúdo das Abas -->
<div class="tab-content" id="usuariosTabContent">
    <!-- Usuários Ativos -->
    <div class="tab-pane fade show active" id="ativos" role="tabpanel" aria-labelledby="ativos-tab">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr class="text-center">
                        <th scope="col">ID</th>
                        <th scope="col">Nome</th>
                        <th scope="col">Cargo</th>
                        <th scope="col">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    try 
                    {
                        include_once 'src/class/BancodeDados.php';
                        $banco = new BancodeDados;
                        $sql = 'SELECT * FROM usuarios WHERE ativo = 1';
                        $dados = $banco->Consultar($sql, [], true);
                        if ($dados) {
                            foreach ($dados as $linha) 
                            {
                                // Define o título de acordo com o valor de 'administrador'
                                $tipoUsuario = $linha['administrador'] == 1 ? "Administrador" : "Usuário";
                                echo 
                                "<tr class='text-center'>
                                    <td>{$linha['id_usuario']}</td>
                                    <td>{$linha['nome_usuario']}</td>
                                    <td>{$tipoUsuario}</td>
                                    <td>
                                        <a href='#' onclick='AlterarUsuario({$linha['id_usuario']})'><i class='bi bi-pencil-square'></i></a>
                                        <a href='#' onclick='ExcluirUsuario({$linha['id_usuario']})'><i class='bi bi-trash3-fill'></i></a>
                                    </td>
                                </tr>";
                            }
                        } 
                        else 
                        {
                            echo "<tr><td colspan='4' class='text-center'>Nenhum usuário ativo...</td></tr>";
                        }
                    } 
                    catch (PDOException $erro) 
                    {
                        $msg = $erro->getMessage();
                        echo "<script>alert(\"$msg\");</script>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
    <!-- Usuários Inativos -->
    <div class="tab-pane fade" id="inativos" role="tabpanel" aria-labelledby="inativos-tab">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr class="text-center">
                        <th scope="col">ID</th>
                        <th scope="col">Nome</th>
                        <th scope="col">Cargo</th>
                        <th scope="col">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    try 
                    {
                        $sql = 'SELECT * FROM usuarios WHERE ativo = 0';
                        $dados = $banco->Consultar($sql, [], true);
                        if ($dados) {
                            foreach ($dados as $linha) 
                            {
                                $tipoUsuario = $linha['administrador'] == 1 ? "Administrador" : "Usuário";
                                echo 
                                "<tr class='text-center'>
                                    <td>{$linha['id_usuario']}</td>
                                    <td>{$linha['nome_usuario']}</td>
                                    <td>{$tipoUsuario}</td>
                                    <td>
                                        <a href='#' onclick='ReativarUsuario({$linha['id_usuario']})'>Reativar</a>
                                    </td>
                                </tr>";
                            }
                        } 
                        else 
                        {
                            echo "<tr><td colspan='4' class='text-center'>Nenhum usuário inativo...</td></tr>";
                        }
                    } 
                    catch (PDOException $erro) 
                    {
                        $msg = $erro->getMessage();
                        echo "<script>alert(\"$msg\");</script>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<!--Modal-->
<div id="adicionar_usuario" class="modal fade" data-bs-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="form_usuario">
                <div class="modal-header">
                    <h4 class="modal-title" id="modalLabel">Usuário</h4>
                    <button onclick="window.location.reload()" type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="txt_id" value="NOVO">
                    <div class="form-group">
                        <label for="txt_nome">Nome</label>
                        <input type="text" class="form-control" id="txt_nome" required>
                    </div>
                    <div class="form-group">
                        <label for="txt_senha">Senha</label>
                        <input type="password" class="form-control" id="txt_senha" required>
                    </div>
                    <div class="form-group">
                        <label for="list_user">Cargo</label>
                        <select class="form-select" id="list_user" required>
                            <option value="">Escolha...</option>
                            <option value="1">Administrador</option>
                            <option value="0">Usuário</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button onclick="CadastrarUsuario()" class="btn btn-success">Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script src="assets/js/usuarios.js"></script>