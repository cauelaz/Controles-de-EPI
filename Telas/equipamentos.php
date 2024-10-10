<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1>Cadastro de Equipamentos (EPIs)</h1>
    </div>
<div class="col-sm-6">
    <button onclick="abrirModal()" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#adicionar_equipamento">
        <i class="bi bi-plus"></i> Novo Equipamento
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
                    <th scope="col">Descrição</th>
                    <th scope="col">Qtd. em Estoque</th>
                    <th scope="col">Certificado Aprovação</th>
                    <th scope="col">Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    try
                    {
                        include_once 'src/class/BancodeDados.php';
                        $banco = new BancodeDados;
                        $sql = 'SELECT * FROM equipamentos WHERE ativo = 1';
                        $dados = $banco -> Consultar($sql,[], true);
                        if($dados)
                        {
                            foreach ($dados as $linha)
                            {
                                $caminho_imagem = 'src/equipamentos/upload/' . $linha['imagem_equipamento'];
                                $imagem_existe = file_exists($caminho_imagem);
                                echo
                                "<tr class='text-center'>
                                    <td>{$linha['id_equipamento']}</td>
                                    <td>{$linha['descricao']}</td>
                                    <td>{$linha['qtd_estoque']}</td>
                                    <td>{$linha['certificado_aprovacao']}</td>
                                    <td>
                                        " . ($imagem_existe ? "<a href='$caminho_imagem' target='_blank'><i class='bi bi-image'></i></a>" : "<i class='bi bi-image' style='color: gray;'></i>") . "
                                        <a href='sistema.php?tela=equipamentos&acao=alterarequipamento&IdEquipamento={$linha['id_equipamento']}'><i class='bi bi-pencil-square'></i></a>
                                        <a href='sistema.php?tela=equipamentos&acao=ajustarestoque&IdEquipamento={$linha['id_equipamento']}'><i class='bi bi-dropbox'></i></a>  
                                        <a href='#' onclick='ExcluirEquipamento({$linha['id_equipamento']})'><i class='bi bi-trash3-fill'></i></a>
                                    </td>
                                </tr>";
                            }
                        }
                        else
                        {
                            echo 
                            "<tr>
                                <td colspan = '6' class='text-center'>Nenhum equipamento cadastrado</td>
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
    </div>
    <!-- Colaboradores Inativos -->
    <div class="tab-pane fade" id="inativos" role="tabpanel" aria-labelledby="inativos-tab">
        <div class="table-responsive">
        <table class="table table-striped table-hover">  
            <thead>
                <tr class="text-center">
                    <th scope="col">ID</th>
                    <th scope="col">Descrição</th>
                    <th scope="col">Qtd. em Estoque</th>
                    <th scope="col">Certificado Aprovação</th>
                    <th scope="col">Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    try
                    {
                        include_once 'src/class/BancodeDados.php';
                        $banco = new BancodeDados;
                        $sql = 'SELECT * FROM equipamentos WHERE ativo = 0';
                        $dados = $banco -> Consultar($sql,[], true);
                        if($dados)
                        {
                            foreach ($dados as $linha)
                            {
                                echo
                                "<tr class='text-center'>
                                    <td>{$linha['id_equipamento']}</td>
                                    <td>{$linha['descricao']}</td>
                                    <td>{$linha['qtd_estoque']}</td>
                                    <td>{$linha['certificado_aprovacao']}</td>
                                    <td>
                                        <a href='sistema.php?tela=equipamentos&acao=reativarequipamento&IdEquipamento={$linha['id_equipamento']}'>Reativar</a>
                                    </td>
                                </tr>";
                            }
                        }
                        else
                        {
                            echo 
                            "<tr>
                                <td colspan = '6' class='text-center'>Nenhum equipamento inativado</td>
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
    </div>
</div>
<!--Modal Principal-->
<div id="adicionar_equipamento" class="modal fade" data-bs-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="form_equipamento" enctype="multipart/form-data">
                <div class="modal-header">
                    <h4 class="modal-title" id="modalLabel">Cadastro de Equipamento</h4>
                    <button onclick="window.location.href='sistema.php?tela=equipamentos'" type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="txt_id" value="NOVO">
                    <div class="form-group">
                        <label for="txt_descricao">Descrição</label>
                        <input type="text" class="form-control" id="txt_descricao" required varchar="255">
                    </div>
                    <div class="form-group">
                        <label for="txt_estoque">Qtd. Estoque</label>
                        <input type="number" class="form-control" id="txt_estoque" required>
                    </div>
                    <div class="form-group">
                        <label for="txt_cert_aprovacao">Certificado Aprovação</label>
                        <input type="text" class="form-control" id="txt_cert_aprovacao" required>
                    </div>  
                    <div class="form-group">
                        <label>Imagem do Equipamento</label>
                        <input type="file" class="form-control" id="file_imagem" value="S/IMG">
                    </div>
                </div>
                <div class="modal-footer">
                    <button onclick="CadastrarEquipamento()" class="btn btn-success">Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!--Modal Ajuste de Estoque-->
<div id="ajuste_estoque" class="modal fade" data-bs-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="form_ajuste_equipamento" method="post" action="src/equipamentos/ajuste_estoque.php" enctype="multipart/form-data">
                <div class="modal-header">
                    <h4 class="modal-title" id="modalLabel">Ajuste de Estoque</h4>
                    <button onclick="window.location.href='sistema.php?tela=equipamentos'" type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="txt_id_estoque" id="txt_id_estoque" value="NOVO">
                    <div class="form-group">
                        <label for="txt_descricao_estoque">Descrição</label>
                        <input type="text" class="form-control" name="txt_descricao_estoque" id="txt_descricao_estoque" required varchar="255" readonly>
                    </div>
                    <div class="form-group">
                        <label for="txt_estoque_estoque">Qtd. Estoque</label>
                        <input type="number" class="form-control" name="txt_estoque_estoque" id="txt_estoque_estoque" required readonly>
                    </div> 
                    <div class="form-group">
                        <label for="txt_new_qtd_estoque">Qtd. Ajuste</label>
                        <input type="number" class="form-control" name="txt_new_qtd_estoque" id="txt_new_qtd_estoque" value="0">
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
            $('#adicionar_equipamento').modal('show');
        }
        function EditarEquipamentoModal()
        {
            var modal = new bootstrap.Modal(document.getElementById('adicionar_equipamento'));
            modal.show();
        }
        function AjustarEstoqueModal()
        {
            var modal = new bootstrap.Modal(document.getElementById('ajuste_estoque'));
            modal.show();
        }
        $('#form_equipamento').submit(function() 
        {
            return false; // Evita o envio padrão do formulário
        });
        function CadastrarEquipamento() 
        {
            var id              = document.getElementById('txt_id').value;
            var descricao       = document.getElementById('txt_descricao').value;
            var estoque         = document.getElementById('txt_estoque').value;
            var cert_aprovacao  = document.getElementById('txt_cert_aprovacao').value;
            var inputFile       = document.getElementById('file_imagem').files[0];
            // Verificação básica de campos preenchidos
            if (descricao && estoque && cert_aprovacao) 
            {
                var formData = new FormData(); // Criar um FormData
                formData.append('id', id);
                formData.append('descricao', descricao);
                formData.append('estoque', estoque);
                formData.append('cert_aprovacao', cert_aprovacao);
                // Adicionar o arquivo somente se foi selecionado
                if (inputFile) {
                    formData.append('file_imagem', inputFile);
                }
                // Envio da requisição AJAX com FormData
                $.ajax({
                    type: 'POST',
                    url: './src/equipamentos/cadastrar_equipamento.php',
                    data: formData,
                    contentType: false, // Importante: evitar que o jQuery defina o tipo de conteúdo
                    processData: false, // Importante: não processar os dados automaticamente
                    success: function(retorno) 
                    {
                        if (retorno['codigo'] == 2) 
                        {
                            alert(retorno['mensagem']);
                            window.location = 'sistema.php?tela=equipamentos';
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
            else 
            {
                alert('Por favor, preencha todos os campos!');
            }
        }
        function ExcluirEquipamento(id) {
            if (confirm('Tem certeza que deseja excluir este equipamento?')) {
                $.ajax({
                    type: 'post',
                    datatype: 'json',
                    url: './src/equipamentos/excluir_equipamento.php',
                    data: { 'id': id },
                    success: function(retorno) {
                        console.log(retorno); // Para verificar o que está retornando
                        if (retorno.codigo == 2) {
                            alert(retorno['mensagem']);
                            window.location = 'sistema.php?tela=equipamentos'; // Atualiza a página
                        } else {
                            alert(retorno['mensagem']);
                        }
                    },
                    error: function(erro) {
                        console.log(erro); // Verifica o erro retornado
                        alert('Ocorreu um erro na requisição: ' + erro.responseText);
                    }
                });
            }
        }   
    </script>