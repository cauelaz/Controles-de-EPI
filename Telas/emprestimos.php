<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Empréstimos</h1>
        <button onclick="abrirModal()" class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#adicionar_departamento">
            <i class="bi bi-plus-lg me-2"></i>Novo Empréstimo
        </button>
    </div>

    <?php
        try {
            include_once 'src/class/BancodeDados.php';
            $banco = new BancodeDados;
            $sql = 'SELECT 
                    COUNT(CASE WHEN situacao = 1 THEN 1 END) AS ativos,
                    COUNT(CASE WHEN situacao = 2 THEN 2 END) AS finalizados
                FROM emprestimos e;';
            $dados = $banco->Consultar($sql, [], true);

            if ($dados) {
                $ativos = $dados[0]['ativos'];
                $finalizados = $dados[0]['finalizados'];
            } else {
                $ativos = 0;
                $finalizados = 0;
            }

        } catch (PDOException $erro) 
        {
            $msg = $erro->getMessage();
            echo "<script>alert(\"$msg\");</script>";
        } 
    ?>

    <!-- Centralizando a linha de cards -->
    <div class="row justify-content-center mb-4"> <!-- Aqui adicionamos justify-content-center -->
        
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Empréstimos Ativos</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $ativos ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-box-seam fs-2 text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Empréstimos Finalizados</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $finalizados ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-check-circle-fill fs-2 text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>      
    </div>
</div> 

    <div class="card shadow mb-4">
        <div class="card-header p-0">
            <ul class="nav nav-pills nav-fill p-2" id="departamentostab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active px-4 py-3" id="ativos-tab" data-bs-toggle="tab" data-bs-target="#ativos" type="button" role="tab">
                        <i class="bi bi-box2-fill me-2"></i>
                        Empréstimos Abertos
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link px-4 py-3" id="inativos-tab" data-bs-toggle="tab" data-bs-target="#inativos" type="button" role="tab">
                        <i class="bi bi-check-circle-fill me-2"></i>
                        Empréstimos Finalizados
                    </button>
                </li>
            </ul>
        </div>
        
        <div class="card-body">
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
                                    <th scope="col">Observações</th>
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
                                                e.id_emprestimo,
                                                e.data_emprestimo,
                                                e.data_devolucao,
                                                c.nome_colaborador,
                                                e.observacoes,
                                                SUM(ee.quantidade) AS quantidade
                                            FROM emprestimos e
                                            LEFT JOIN colaboradores c ON c.id_colaborador = e.colaborador
                                            LEFT JOIN equipamentos_emprestimo ee ON ee.emprestimo = e.id_emprestimo
                                            WHERE e.situacao = 1
                                            GROUP BY e.id_emprestimo, e.data_emprestimo, e.data_devolucao, c.nome_colaborador, e.observacoes
                                            ORDER BY e.id_emprestimo ASC;';
            
                                    $dados = $banco->Consultar($sql, [], true);
                                    if ($dados) {
                                        foreach ($dados as $linha) 
                                        {
                                            echo 
                                            "<tr class='text-center'>
                                                <td>{$linha['id_emprestimo']}</td>
                                                <td>{$linha['nome_colaborador']}</td>
                                                <td>{$linha['quantidade']}</td>
                                                <td>{$linha['data_emprestimo']}</td>
                                                <td>{$linha['data_devolucao']}</td>
                                                <td>{$linha['observacoes']}</td>
                                                <td>
                                                    <a href='#' onclick='AlterarEmprestimo({$linha['id_emprestimo']})'><i class='bi bi-pencil-square'></i></a>
                                                    <a href='#' onclick='CancelarEmprestimo({$linha['id_emprestimo']})'><i class='bi bi-trash3-fill'></i></a>
                                                    <a href='#' onclick='FinalizarEmprestimo({$linha['id_emprestimo']})'><i class='bi bi-dropbox'></i></a>
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
                                    <th scope="col">Colaborador</th>
                                    <th scope="col">Qtd. EPI's</th>
                                    <th scope="col">Data Empréstimo</th>
                                    <th scope="col">Data Devolução</th>
                                    <th scope="col">Observações</th>
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
                                                e.id_emprestimo,
                                                e.data_emprestimo,
                                                e.data_devolucao,
                                                c.nome_colaborador,
                                                e.observacoes,
                                                SUM(ee.quantidade) AS quantidade
                                            FROM emprestimos e
                                            LEFT JOIN colaboradores c ON c.id_colaborador = e.colaborador
                                            LEFT JOIN equipamentos_emprestimo ee ON ee.emprestimo = e.id_emprestimo
                                            WHERE e.situacao = 2
                                            GROUP BY e.id_emprestimo, e.data_emprestimo, e.data_devolucao, c.nome_colaborador, e.observacoes
                                            ORDER BY e.id_emprestimo ASC;';
            
                                    $dados = $banco->Consultar($sql, [], true);
                                    if ($dados) {
                                        foreach ($dados as $linha) 
                                        {
                                            echo 
                                            "<tr class='text-center'>
                                                <td>{$linha['id_emprestimo']}</td>
                                                <td>{$linha['nome_colaborador']}</td>
                                                <td>{$linha['quantidade']}</td>
                                                <td>{$linha['data_emprestimo']}</td>
                                                <td>{$linha['data_devolucao']}</td>
                                                <td>{$linha['observacoes']}</td>
                                                <td>
                                                    <a href='#' onclick='AlterarEmprestimo({$linha['id_emprestimo']})'><i class='bi bi-pencil-square'></i></a>
                                                    <a href='#' onclick='CancelarEmprestimo({$linha['id_emprestimo']})'><i class='bi bi-trash3-fill'></i></a>
                                                    <a href='#' onclick='FinalizarEmprestimo({$linha['id_emprestimo']})'><i class='bi bi-dropbox'></i></a>
                                                </td>
                                            </tr>";
                                        }
                                    } 
                                    else 
                                    {
                                        echo "<tr><td colspan='4' class='text-center'>Nenhum empréstimo finalizado...</td></tr>";
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
        </div>
    </div>
</div>

<script>

document.addEventListener('DOMContentLoaded', function() {
    const tabs = document.querySelectorAll('.nav-link');
    tabs.forEach(tab => {
        tab.addEventListener('click', function() {
            tabs.forEach(t => t.classList.remove('active'));
            this.classList.add('active');
        });
    });
});

document.querySelectorAll('.table a').forEach(link => {
    link.classList.add('btn-action', 'mx-1');
});
</script>
<!--Modal Principal-->
<?php
    include_once 'src/class/BancodeDados.php';
    $banco = new BancodeDados;
    $sql = 'SELECT id_colaborador, nome_colaborador FROM colaboradores WHERE ativo = 1';
    $colaboradores = $banco->Consultar($sql, [], true);

    $sql = 'SELECT id_equipamento, descricao FROM equipamentos WHERE ativo = 1';
    $equipamentos = $banco->Consultar($sql, [], true);
?>
<!-- Modal de Empréstimo -->
<div id="emprestimo_editor" class="modal fade" data-bs-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="form_emprestimo" enctype="multipart/form-data">
                <div class="modal-header">
                    <h4 class="modal-title" id="modalLabel">Cadastro de Empréstimos</h4>
                    <button type="button" onclick="window.location.reload()" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Abas no Modal -->
                    <ul class="nav nav-tabs" id="modalTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active" id="geral-tab" data-bs-toggle="tab" href="#geral" role="tab" aria-controls="geral" aria-selected="true">Geral</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="equipamentos-tab" data-bs-toggle="tab" href="#equipamentos" role="tab" aria-controls="equipamentos" aria-selected="false">Equipamentos</a>
                        </li>
                    </ul>

                    <div class="tab-content" id="modalTabsContent">
                        <!-- Aba Geral -->
                        <div class="tab-pane fade show active" id="geral" role="tabpanel" aria-labelledby="geral-tab">
                            <input type="hidden" id="txt_id" value="NOVO">

                            
                            <!-- Colaborador -->
                            <div class="form-group">
                                <label for="cbColaborador">Colaborador</label>
                                <select class="form-control" id="cbColaborador" name="cbColaborador" required>
                                    <option value="0">Selecione o Colaborador</option>
                                    <?php foreach ($colaboradores as $colaborador): ?>
                                        <option value="<?= $colaborador['id_colaborador']; ?>">
                                            <?= $colaborador['nome_colaborador']; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <!-- Situação -->
                            <div class="form-group">
                                <label for="cbSituacao">Situação</label>
                                <select class="form-control" id="cbSituacao" required>
                                    <option value=1>Em Aberto</option>
                                    <option value=2>Finalizado</option>
                                </select>
                            </div>

                            <!-- Datas -->
                            <div class="form-group">
                                <label for="dataEmprestimo">Data Empréstimo</label>
                                <input type="date" class="form-control" id="dataEmprestimo" required>
                            </div>

                            <div class="form-group">
                                <label for="dataDevolucao">Data Devolução</label>
                                <input type="date" class="form-control" id="dataDevolucao">
                            </div>

                            <!-- Observações -->
                            <div class="form-group">
                                <label for="txtObservacoes">Observações</label>
                                <textarea class="form-control" id="txtObservacoes"></textarea>
                            </div>
                        </div>

                        <!-- Aba Equipamentos -->
                        <div class="tab-pane fade" id="equipamentos" role="tabpanel" aria-labelledby="equipamentos-tab">
                            <!-- Combobox Equipamento -->
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
                            </div>

                            <!-- Quantidade Equipamento -->
                            <div class="form-group">
                                <label for="inputQuantidade">Quantidade</label>
                                <input type="number" id="inputQuantidade" class="form-control" min="1" value="1" required>
                            </div>

                            <!-- Botão Adicionar -->
                            <button type="button" class="btn btn-primary mt-2" id="btnAdicionar">Adicionar</button>

                            <!-- Tabela Equipamentos -->
                            <div class="form-group">
                                <table class="table table-striped mt-3" id="tabelaEquipamentos">
                                    <thead>
                                        <tr>
                                            <th>Equipamento</th>
                                            <th>Quantidade</th>
                                            <th>Ação</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <button onclick="CadastrarEmprestimo()" class="btn btn-success">Salvar</button>
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
    function EditarEmprestimoModal()
    {
        var modal = new bootstrap.Modal(document.getElementById('emprestimo_editor'));
        modal.show();
    }
    $('#emprestimo_editor').submit(function() 
    {
        return false; // Evita o envio padrão do formulário
    });

    function CadastrarEmprestimo() {

        var emprestimo = {
            "id": document.getElementById('txt_id').value,
            "colaborador": document.getElementById('cbColaborador').value,
            "situacao": document.getElementById('cbSituacao').value,
            "dataEmprestimo": document.getElementById('dataEmprestimo').value,
            "dataDevolucao": document.getElementById('dataDevolucao').value,
            "observacoes": document.getElementById('txtObservacoes').value,
            "itens": [] 
        };

        // Coleta os dados dos itens (equipamentos) da tabela
        var tabelaEquipamentos = document.getElementById('tabelaEquipamentos').getElementsByTagName('tbody')[0];

        for (var i = 0; i < tabelaEquipamentos.rows.length; i++) {
            var equipamentoId = tabelaEquipamentos.rows[i].getAttribute('data-id-equipamento');
            var quantidade = tabelaEquipamentos.rows[i].getAttribute('data-quantidade'); // Coleta a quantidade de cada equipamento


            emprestimo.itens.push({
                "id": equipamentoId,
                "quantidade": quantidade
            });
        }

        console.log(JSON.stringify(emprestimo));

        $.ajax({
            type: 'post',
            datatype: 'json',
            url: './src/emprestimos/editor_emprestimo.php',
            data: JSON.stringify(emprestimo),
            success: function(retorno) 
            {
                console.log(retorno);
                if (retorno['codigo'] == 2) {
                    alert(retorno['mensagem']);
                    window.location = 'sistema.php?tela=emprestimos';
                } else {
                    alert(retorno['mensagem']);
                }
            },
            error: function(erro) {
                alert('Ocorreu um erro na requisição: ' + erro);
            }
        });
    }


    function FinalizarEmprestimo(id)
    {
        var urlp = './src/emprestimos/finalizar_emprestimo.php?id=' + id + '&soma';
        console.log(urlp);
        if (confirm('Tem certeza que deseja finalizar esse empréstimo?')) 
        {
            $.ajax({
                type: 'post',
                datatype: 'json',
                url: urlp,
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
    function AlterarEmprestimo(id) {
        $.ajax({
            type: 'get',
            url: './src/emprestimos/get_emprestimo.php?id=' + id, 
            
            success: function(retorno) {
                console.log(retorno);
                var emprestimo = (retorno);


                // Preenchendo os campos do formulário
                document.getElementById('txt_id').value = emprestimo.id;
                document.getElementById('cbColaborador').value = emprestimo.colaborador; // Exemplo de colaborador
                document.getElementById('cbSituacao').value = emprestimo.situacao;
                document.getElementById('dataEmprestimo').value = emprestimo.dataEmprestimo;
                document.getElementById('dataDevolucao').value = emprestimo.dataDevolucao ? emprestimo.dataDevolucao : ''; // Se não houver devolução, deixa em branco
                document.getElementById('txtObservacoes').value = emprestimo.observacoes;

                // impedir edição quando estiver finalizado

                if (emprestimo.situacao == 2) {
                    document.getElementById('txt_id').disabled = true;
                    document.getElementById('cbColaborador').disabled = true;
                    document.getElementById('cbSituacao').disabled = true;
                    document.getElementById('dataEmprestimo').disabled = true;
                    document.getElementById('dataDevolucao').disabled = true;
                    document.getElementById('txtObservacoes').disabled = true;

                    var btn = document.getElementById('btnAdicionar');
                    if (btn) {
                        btn.style.display = 'none';
                    } else {
                    }
                }


                // impedir ediçao de equipamento independente do caso
                
                document.getElementById('cbEquipamento').disabled = true;
                document.getElementById('inputQuantidade').disabled = true;
 
                var grid = document.getElementById('tabelaEquipamentos');
                grid.innerHTML = ''; // Limpar a tabela

                var header = grid.createTHead();
                var headerRow = header.insertRow(0);

                var th1 = document.createElement('th');
                th1.innerHTML = 'Quantidade';
                headerRow.appendChild(th1);

                var th2 = document.createElement('th');
                th2.innerHTML = 'Nome do EPI';
                headerRow.appendChild(th2);


                emprestimo.itens.forEach(function(item) {
                    var row = grid.insertRow();

                    // Criar células para a quantidade e nome do EPI
                    var cell1 = row.insertCell(0);
                    var cell2 = row.insertCell(1);

                    // Preencher os valores
                    cell1.innerHTML = item.quantidade;
                    cell2.innerHTML = item.descricao;

                    var btnEditar = document.createElement('button');
                    var btnRemover = document.createElement('button');

                    btnEditar.disabled = true; // Desabilitar botão de edição
                    btnRemover.disabled = true; // Desabilitar botão de remoção

                });


                // Chamar o modal de edição
                EditarEmprestimoModal();
            },

            error: function(erro) {
                alert('Ocorreu um erro na requisição: ' + erro.responseText);
            }
        });
    }


    function CancelarEmprestimo(id)
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
        function verificarStatus() {
        const dataDevolucao = document.getElementById('dataDevolucao').value;
        const situacao = document.getElementById('situacao');
        
        if (dataDevolucao) {
            situacao.value = 'finalizado';
            bloquearCampos();
        }
    }

    function bloquearCampos() {
        // Desabilita todos os campos do formulário
        document.getElementById('dataEmprestimo').disabled = true;
        document.getElementById('dataDevolucao').disabled = true;
        document.getElementById('situacao').disabled = true;
        document.getElementById('observacoes').disabled = true;
        document.getElementById('cbEquipamento').disabled = true;
        document.getElementById('btnAdicionar').disabled = true;

        // Desabilita os botões de remoção na tabela de equipamentos
        const botoesRemover = document.querySelectorAll('#tabelaEquipamentos button');
        botoesRemover.forEach(function (botao) {
            botao.disabled = true;
        });
    }

    function verificarStatusInicial() {
        const situacao = document.getElementById('situacao').value;

        if (situacao === 'finalizado') {
            bloquearCampos();
        }
    }

    document.getElementById('emprestimo_editor').addEventListener('shown.bs.modal', function() {
        verificarStatusInicial();
    });

    document.getElementById('btnAdicionar').addEventListener('click', function() {
        const cbEquipamento = document.getElementById('cbEquipamento');
        const equipamentoId = cbEquipamento.value;
        const equipamentoNome = cbEquipamento.options[cbEquipamento.selectedIndex].text;
        const quantidade = document.getElementById('inputQuantidade').value; // Obtém a quantidade

        if (equipamentoId !== "0" && quantidade > 0) {
            const tabelaEquipamentos = document.getElementById('tabelaEquipamentos').getElementsByTagName('tbody')[0];
            const novaLinha = tabelaEquipamentos.insertRow();

            // Adiciona o id do equipamento e a quantidade como atributos na linha da tabela
            novaLinha.setAttribute('data-id-equipamento', equipamentoId);
            novaLinha.setAttribute('data-quantidade', quantidade);  // Adiciona a quantidade

            const celEquipamento = novaLinha.insertCell(0);
            celEquipamento.textContent = equipamentoNome;

            const celQuantidade = novaLinha.insertCell(1);
            celQuantidade.textContent = quantidade;

            const celAcao = novaLinha.insertCell(2);
            const btnRemover = document.createElement('button');
            btnRemover.textContent = 'Remover';
            btnRemover.className = 'btn btn-danger btn-sm';
            btnRemover.onclick = function() {
                tabelaEquipamentos.deleteRow(novaLinha.rowIndex - 1);
            };
            celAcao.appendChild(btnRemover);

            // Limpa os campos após adicionar
            cbEquipamento.value = "0"; 
            document.getElementById('inputQuantidade').value = "1"; // Reseta a quantidade para 1
        } else {
            alert('Selecione um equipamento válido e insira uma quantidade maior que 0.');
        }
    });



</script>