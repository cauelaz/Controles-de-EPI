<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1>Cadastro de Equipamentos (EPIs)</h1>
    </div>
<div class="col-sm-6">
    <button onclick="abrirModal()" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#adicionar_equipamento">
        <i class="bi bi-plus"></i> Novo Equipamento
    </button>
</div>
    <hr class="my-4">
    <div class="table-responsive">
        <table class="table table-striped table-hover">  
            <thead>
                <tr>
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
                                "<tr'>
                                    <td>{$linha['id_equipamento']}</td>
                                    <td>{$linha['descricao']}</td>
                                    <td>{$linha['qtd_estoque']}</td>
                                    <td>{$linha['certificado_aprovacao']}</td>
                                    <td>
                                        " . ($imagem_existe ? "<a href='$caminho_imagem' target='_blank'><i class='bi bi-image'></i></a>" : "<i class='bi bi-image' style='color: gray;'></i>") . "
                                        <a href='sistema.php?tela=equipamentos&acao=alterarequipamento&IdEquipamento={$linha['id_equipamento']}'><i class='bi bi-pencil-square'></i></a>
                                        <a href='#' onclick='ExcluirEquipamento({$linha['id_equipamento']})'><i class='bi bi-trash3-fill'></i></a>
                                    </td>
                                </tr>";
                            }
                        }
                        else
                        {
                            echo 
                            "<tr>
                                <td colspan = '6' class='text-center'> Nenhum equipamento cadastrado...</td>
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
    <!--Modal-->
<div id="adicionar_equipamento" class="modal fade" data-bs-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="form_equipamento" method="post" action="src/equipamentos/cadastrar_equipamento.php" enctype="multipart/form-data">
                <div class="modal-header">
                    <h4 class="modal-title" id="modalLabel">Cadastro de Equipamento</h4>
                    <button onclick="window.location.href='sistema.php?tela=equipamentos'" type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="txt_id" id="txt_id" value="NOVO">
                    <div class="form-group">
                        <label for="txt_nome">Descrição</label>
                        <input type="text" class="form-control" name="txt_descricao" id="txt_descricao" required varchar="255">
                    </div>
                    <div class="form-group">
                        <label for="txt_data_nasc">Qtd. Estoque</label>
                        <input type="number" class="form-control" name="txt_estoque" id="txt_estoque" required>
                    </div>
                    <div class="form-group">
                        <label for="txt_cpf_cnpj">Certificado Aprovação</label>
                        <input type="text" class="form-control" name="txt_cert_aprovacao" id="txt_cert_aprovacao" required>
                    </div>  
                    <div class="form-group">
                        <label>Imagem do Equipamento</label>
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
    </script>