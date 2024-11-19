<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1>Departamentos</h1>
</div>
<div class="col-sm-6">
    <button onclick="abrirModal()" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#adicionar_departamento">
        <i class="bi bi-plus"></i> Novo Departamento
    </button>
</div>
<hr class="my-4">
<!-- Abas para Ativos e Inativos -->
<ul class="nav nav-tabs" id="departamentostab" role="tablist">
    <li class="nav-item" role="presentation">
        <a class="nav-link active" id="ativos-tab" data-bs-toggle="tab" href="#ativos" role="tab" aria-controls="ativos" aria-selected="true">Ativos</a>
    </li>
    <li class="nav-item" role="presentation">
        <a class="nav-link" id="inativos-tab" data-bs-toggle="tab" href="#inativos" role="tab" aria-controls="inativos" aria-selected="false">Inativos</a>
    </li>
</ul>
<!-- Conteúdo das Abas -->
<div class="tab-content" id="departamentosTabContent">
    <!-- Usuários Ativos -->
    <div class="tab-pane fade show active" id="ativos" role="tabpanel" aria-labelledby="ativos-tab">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr class="text-center">
                        <th scope="col">ID</th>
                        <th scope="col">Nome</th>
                        <th scope="col">Qtd. Colaboradores</th>
                        <th scope="col">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    try 
                    {
                        include_once 'src/class/BancodeDados.php';
                        $banco = new BancodeDados;
                        $sql = 'SELECT departamentos.id_departamento
                                     , departamentos.nome_departamento
                                     , COALESCE(COUNT(colaboradores.id_departamento), 0) AS total_departamentos
                                 FROM departamentos
                                 LEFT JOIN colaboradores ON colaboradores.id_departamento = departamentos.id_departamento 
                                 WHERE departamentos.ativo = 1
                                 GROUP BY departamentos.id_departamento, departamentos.nome_departamento;';
                        $dados = $banco->Consultar($sql, [], true);
                        if ($dados) {
                            foreach ($dados as $linha) 
                            {
                                echo 
                                "<tr class='text-center'>
                                    <td>{$linha['id_departamento']}</td>
                                    <td>{$linha['nome_departamento']}</td>
                                    <td>{$linha['total_departamentos']}</td>
                                    <td>
                                        <a href='#' onclick='AlterarDepartamento({$linha['id_departamento']})'><i class='bi bi-pencil-square'></i></a>
                                        <a href='#' onclick='ExcluirDepartamento({$linha['id_departamento']})'><i class='bi bi-trash3-fill'></i></a>
                                    </td>
                                </tr>";
                            }
                        } 
                        else 
                        {
                            echo "<tr><td colspan='4' class='text-center'>Nenhum departamento ativo...</td></tr>";
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
                        <th scope="col">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    try 
                    {
                        $sql = 'SELECT * FROM departamentos WHERE ativo = 0';
                        $dados = $banco->Consultar($sql, [], true);
                        if ($dados) {
                            foreach ($dados as $linha) 
                            {
                                echo 
                                "<tr class='text-center'>
                                    <td>{$linha['id_departamento']}</td>
                                    <td>{$linha['nome_departamento']}</td>
                                    <td>
                                        <a href='#' onclick='ReativarDepartamento({$linha['id_departamento']})'>Reativar</a>
                                    </td>
                                </tr>";
                            }
                        } 
                        else 
                        {
                            echo "<tr><td colspan='4' class='text-center'>Nenhum departamento inativo...</td></tr>";
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
<div id="adicionar_departamento" class="modal fade" data-bs-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="form_departamento">
                <div class="modal-header">
                    <h4 class="modal-title" id="modalLabel">Departamentos</h4>
                    <button onclick="window.location.reload()" type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="txt_id" value="NOVO">
                    <div class="form-group">
                        <label for="txt_nome">Nome</label>
                        <input type="text" class="form-control" id="txt_nome" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button onclick="CadastrarDepartamento()" class="btn btn-success">Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script src="assets/js/departamentos.js"></script>