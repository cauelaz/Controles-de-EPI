<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1>Empréstimos</h1>
</div>
<div class="col-sm-6">
    <button onclick="abrirModal()" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#adicionar_departamento">
        <i class="bi bi-plus"></i> Novo Empréstimo
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
    <div class="tab-pane fade show active" id="ativos" role="tabpanel" aria-labelledby="ativos-tab">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr class="text-center">
                        <th scope="col">ID</th>
                        <th scope="col">Colaborador</th>
                        <th scope="col">Qtd. EPI's</th>
                        <th scope="col">Data Empréstimo</th>
                        <th scope="col">Data Devolução</th>
                        <th scope="col">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    try 
                    {
                        include_once 'src/class/BancodeDados.php';
                        $banco = new BancodeDados;
                        $sql = 'SELECT
                                    ,e.id_emprestimo
                                    ,e.data_emprestimo
                                    ,e.data_devolucao
                                    ,c.nome_colaborador
                                    ,e.observacoes 
                                    FROM emprestimos e
                                    LEFT JOIN colaboradores c ON c.id_colaborador = e.colaborador
                                    WHERE e.ativo = 1 and data_devolucao is null
                                 GROUP BY e.id_emprestimo';
                        $dados = $banco->Consultar($sql, [], true);
                        if ($dados) {
                            foreach ($dados as $linha) 
                            {
                                echo 
                                "<tr class='text-center'>
                                    <td>{$linha['id_emprestimo']}</td>
                                    <td>{$linha['nome_colaborador']}</td>
                                    <td>{$linha['data_emprestimo']}</td>
                                    <td>{$linha['data_devolucao']}</td>
                                    <td>{$linha['observacoes']}</td>
                                    <td>
                                        <a href='#' onclick='AlterarEmprestimo({$linha['id_emprestimo']})'><i class='bi bi-pencil-square'></i></a>
                                        <a href='#' onclick='FinalizarEmprestimo({$linha['id_emprestimo']})'><i class='bi bi-trash3-fill'></i></a>
                                    </td>
                                </tr>";
                            }
                        } 
                        else 
                        {
                            echo "<tr><td colspan='4' class='text-center'>Nenhum empréstimo ativo...</td></tr>";
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
<!--Modal Principal-->
<?php
    // Inclua o arquivo do banco de dados
    include_once 'src/class/BancodeDados.php';
    // Consultar os departamentos diretamente
    $banco = new BancodeDados;
    $sql = 'SELECT id_colaborador, nome_colaborador FROM colaboradores WHERE ativo = 1';
    $colaboradores = $banco->Consultar($sql, [], true);

    $sql = 'SELECT id_equipamento, descricao FROM equipamentos WHERE ativo = 1';
    $equipamentos = $banco->Consultar($sql, [], true);
?>
<div id="emprestimo_editor" class="modal fade" data-bs-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="form_departamento" enctype="multipart/form-data">
                <div class="modal-header">
                    <h4 class="modal-title" id="modalLabel">Cadastro de Emprestimos</h4>
                    <button onclick="window.location.href='sistema.php?tela=equipamentos'" type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="cbColaborador">Colaborador</label>
                            <select class="form-control" id="cbColaborador" name="cbColaborador" required>
                                <option value="0">Selecione o Colaborador</option>
                                <?php foreach ($colaboradores as $colaborador): ?>
                                    <option value="<?= $colaborador['id_colaborador']; ?>">
                                        <?= $colaborador['colaborador']; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="cbEquipamento">Equipamento</label>
                        <select class="form-control" id="cbEquipamento" required>
                            <option value="0">Selecione o Equipamento</option>
                            <?php foreach ($equipamentos as $equipamento): ?>
                                <option value="<?= $equipamento['id_equipamento']; ?>">
                                    <?= $equipamento['descricao']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <button type="button" class="btn btn-primary mt-2" id="btnAdicionar">Adicionar</button>
                    </div>

                    <div class="form-group">
                        <table class="table table-striped mt-3" id="tabelaEquipamentos">
                            <thead>
                                <tr>
                                    <th>Equipamento</th>
                                    <th>Ação</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>

                    <div class="form-group">
                        <label for="txt_cert_aprovacao">Certificado Aprovação</label>
                        <input type="text" class="form-control" id="txt_cert_aprovacao" required>
                    </div>  
                    <div class="form-group">
                        <label for="file_imagem">Nome do Equipamento</label>
                        <input type="file" class="form-control" id="nome_equipamento" value="S/IMG">
                    </div>
                </div>
                <div class="modal-footer">
                    <button onclick="CadastrarEquipamento()" class="btn btn-success">Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function abrirModal() 
    {
        $('#emprestimo_editor').modal('show');
    }
    function EditarDepartamentoModal()
    {
        var modal = new bootstrap.Modal(document.getElementById('emprestimo_editor'));
        modal.show();
    }
    $('#form_departamento').submit(function() 
    {
        return false; // Evita o envio padrão do formulário
    });
    function CadastrarEmprestimo() 
    {
        var id      = document.getElementById('txt_id').value;
        var nome    = document.getElementById('txt_nome').value;
        if (nome) 
        { // Validação simples
            $.ajax({
                type: 'post',
                datatype: 'json',
                url: './src/departamentos/editor_emprestimo.php',
                data: 
                {
                    'id': id,
                    'nome': nome,
                },
                success: function(retorno) 
                {
                    if (retorno['codigo'] == 2) 
                    {
                        alert(retorno['mensagem']);
                        window.location = 'sistema.php?tela=emprestimos';
                    }
                    else 
                    {
                        alert(retorno['mensagem']);
                        window.location = 'sistema.php?tela=emprestimos';
                    }
                },
                error: function(erro) 
                {
                    alert('Ocorreu um erro na requisição: ' + erro);
                }
            });
        } 
        else 
        {
            alert('Por favor, preencha todos os campos!');
        }
    }
    function FinalizarEmprestimo(id)
    {
        if (confirm('Tem certeza que deseja finalizar esse empréstimo?')) 
        {
            $.ajax({
                type: 'post',
                datatype: 'json',
                url: './src/emprestimos/finalizar_emprestimo.php',
                data: { 'id': id },
                success: function(retorno) 
                {
                    if (retorno['codigo'] == 2) 
                    {
                        alert(retorno['mensagem']);
                        window.location = 'sistema.php?tela=emprestimos';
                    } 
                    else 
                    {
                        alert(retorno['mensagem']);
                    }
                },
                error: function(erro) 
                {
                    alert('Ocorreu um erro na requisição: ' + erro);
                }
            });
        }
    }
    function AlterarEmprestimo(id)
    {
        // Envia o ID do equipamento para o backend
        $.ajax({
            type: 'post',
            url: './src/emprestimos/editor_emprestimo.php', // Endpoint que retorna os dados do equipamento
            data: { 'id': id },
            success: function(retorno) 
            {
                var departamento = JSON.parse(retorno); // Converter o retorno para objeto JavaScript
                // Preencher os campos do modal com os dados recebidos
                document.getElementById('txt_id').value = departamento.id;
                document.getElementById('txt_nome').value = departamento.nome;
                // Abrir o modal usando Bootstrap
                EditarDepartamentoModal()
            },
            error: function(erro) 
            {
                alert('Ocorreu um erro na requisição: ' + erro.responseText);
            }
        });
    }
    function ReativarDepartamento(id)
    {
        $.ajax({
            type: 'post',
            datatype: 'json',
            url: './src/emprestimos/editor_emprestimo.php',
            data: { 'id': id },
            success: function(retorno) {
                if (retorno['codigo'] == 2) 
                {
                    alert(retorno['mensagem']);
                    window.location = 'sistema.php?tela=emprestimos';
                } 
                else 
                {
                    alert(retorno['mensagem']);
                }
            },
            error: function(erro) 
            {
                alert('Ocorreu um erro na requisição: ' + erro.responseText);
            }
        });
    }
    document.getElementById('btnAdicionar').addEventListener('click', function() {
    const cbEquipamento = document.getElementById('cbEquipamento');
    const equipamentoId = cbEquipamento.value;
    const equipamentoNome = cbEquipamento.options[cbEquipamento.selectedIndex].text;

    if (equipamentoId !== "0") {
        const tabelaEquipamentos = document.getElementById('tabelaEquipamentos').getElementsByTagName('tbody')[0];
        const novaLinha = tabelaEquipamentos.insertRow();

        const celEquipamento = novaLinha.insertCell(0);
        celEquipamento.textContent = equipamentoNome;

        const celAcao = novaLinha.insertCell(1);
        const btnRemover = document.createElement('button');
        btnRemover.textContent = 'Remover';
        btnRemover.className = 'btn btn-danger btn-sm';
        btnRemover.onclick = function() {
            tabelaEquipamentos.deleteRow(novaLinha.rowIndex - 1);
        };
        celAcao.appendChild(btnRemover);

        cbEquipamento.value = "0"; // Reseta a combo box
    } else {
        alert('Selecione um equipamento válido.');
    }
});

</script>