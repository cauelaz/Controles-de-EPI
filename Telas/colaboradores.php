<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1>Cadastro de Colaboradores</h1>
</div>
<div class="col-sm-6">
    <button onclick="abrirModal()" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#adicionar_colaborador">
        <i class="bi bi-plus"></i> Novo Colaborador
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
<div class="tab-content" id="colaboradoresTabContent">
    <!-- Colaboradores Ativos -->
    <div class="tab-pane fade show active" id="ativos" role="tabpanel" aria-labelledby="ativos-tab">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr class="text-center">
                        <th scope="col">ID</th>
                        <th scope="col">Nome</th>
                        <th scope="col">Data Nascimento</th>
                        <th scope="col">CPF/CNPJ</th>
                        <th scope="col">RG</th>
                        <th scope="col">Telefone</th>
                        <th scope="col">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    try 
                    {
                        include_once 'src/class/BancodeDados.php';
                        $banco = new BancodeDados;
                        $sql = 'SELECT * FROM colaboradores WHERE ativo = 1';
                        $dados = $banco->Consultar($sql, [], true);
                        if ($dados) {
                            foreach ($dados as $linha) 
                            {
                                $caminho_imagem = 'src/colaboradores/upload/' . $linha['imagem_colaborador'];
                                $imagem_existe = file_exists($caminho_imagem);
                                $data_nascimento = DateTime::createFromFormat('Y-m-d', $linha['data_nascimento'])->format('d/m/y');
                                echo 
                                "<tr class='text-center'>
                                    <td>{$linha['id_colaborador']}</td>
                                    <td>{$linha['nome_colaborador']}</td>
                                    <td>{$data_nascimento}</td>
                                    <td>{$linha['cpf_cnpj']}</td>
                                    <td>{$linha['rg']}</td>
                                    <td>{$linha['telefone']}</td>
                                    <td>
                                        " . ($imagem_existe ? "<a href='$caminho_imagem' target='_blank'><i class='bi bi-image'></i></a>" : "<i class='bi bi-image' style='color: gray;'></i>") . "
                                        <a href='sistema.php?tela=colaboradores&acao=alterarcolaborador&IdColaborador={$linha['id_colaborador']}'><i class='bi bi-pencil-square'></i></a>
                                        <a href='#' onclick='ExcluirColaborador({$linha['id_colaborador']})'><i class='bi bi-trash3-fill'></i></a>
                                    </td>
                                </tr>";
                            }
                        } 
                        else 
                        {
                            echo "<tr><td colspan='7' class='text-center'>Nenhum colaborador cadastrado</td></tr>";
                        }
                    } catch (PDOException $erro) {
                        $msg = $erro->getMessage();
                        echo "<script>alert(\"$msg\");</script>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
    <!-- Colaboradores Inativos -->
    <div class="tab-pane fade" id="inativos" role="tabpanel" aria-labelledby="inativos-tab">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr class="text-center">
                        <th scope="col">ID</th>
                        <th scope="col">Nome</th>
                        <th scope="col">Data Nascimento</th>
                        <th scope="col">CPF/CNPJ</th>
                        <th scope="col">RG</th>
                        <th scope="col">Telefone</th>
                        <th scope="col">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    try 
                    {
                        include_once 'src/class/BancodeDados.php';
                        $banco = new BancodeDados;
                        $sql = 'SELECT * FROM colaboradores WHERE ativo = 0';
                        $dados = $banco->Consultar($sql, [], true);
                        if ($dados) {
                            foreach ($dados as $linha) {
                                $data_nascimento = DateTime::createFromFormat('Y-m-d', $linha['data_nascimento'])->format('d/m/y');
                                echo 
                                "<tr class='text-center'>
                                    <td>{$linha['id_colaborador']}</td>
                                    <td>{$linha['nome_colaborador']}</td>
                                    <td>{$data_nascimento}</td>
                                    <td>{$linha['cpf_cnpj']}</td>
                                    <td>{$linha['rg']}</td>
                                    <td>{$linha['telefone']}</td>
                                    <td>
                                        <a href='sistema.php?tela=colaboradores&acao=reativarcolaborador&IdColaborador={$linha['id_colaborador']}'>Reativar</a>
                                    </td>
                                </tr>";
                            }
                        } 
                        else 
                        {
                            echo "<tr><td colspan='7' class='text-center'>Nenhum colaborador inativado</td></tr>";
                        }
                    } catch (PDOException $erro) 
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
<div id="adicionar_colaborador" class="modal fade" data-bs-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="form_colaborador" method="post" action="src/colaboradores/cadastrar_colaborador.php" enctype="multipart/form-data">
                <div class="modal-header">
                    <h4 class="modal-title" id="modalLabel">Cadastrar Colaborador</h4>
                    <button onclick="window.location.href='sistema.php?tela=colaboradores'" type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="txt_id" id="txt_id" value="NOVO">
                    <div class="form-group">
                        <label for="txt_nome">Nome</label>
                        <input type="text" class="form-control" name="txt_nome" id="txt_nome" required varchar="255">
                    </div>
                    <div class="form-group">
                        <label for="txt_data_nasc">Data Nascimento</label>
                        <input type="date" class="form-control" name="txt_data_nasc" id="txt_data_nasc" required date>
                    </div>
                    <div class="form-group">
                        <label for="txt_cpf_cnpj">CPF/CNPJ</label>
                        <input type="text" class="form-control" name="txt_cpf_cnpj" id="txt_cpf_cnpj" required minlength="11" maxlength="18">
                    </div>
                    <div class="form-group">
                        <label for="txt_rg">RG</label>
                        <input type="text" class="form-control" name="txt_rg" id="txt_rg" required minlength="7" maxlength="9">
                    </div>
                    <div class="form-group">
                        <label for="txt_telefone">Telefone</label>
                        <input type="tel" class="form-control" name="txt_telefone" id="txt_telefone" required minlength="10" maxlength="14">
                    </div>    
                    <div class="form-group">
                        <label>Foto do Colaborador</label>
                        <input type="file" class="form-control" name="file_imagem" id="file_imagem" value="S/IMG">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>
</div>
<script>
    function abrirModal() 
    {
        $('#adicionar_colaborador').modal('show');
    }
    function EditarColaboradorModal()
    {
        var modal = new bootstrap.Modal(document.getElementById('adicionar_colaborador'));
        modal.show();
    }
</script>