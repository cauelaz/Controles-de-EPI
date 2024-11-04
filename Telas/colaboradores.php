<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1>Colaboradores</h1>
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
                        <th scope="col">Telefone</th>
                        <th scope="col">Departamento</th>
                        <th scope="col">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    try 
                    {
                        include_once 'src/class/BancodeDados.php';
                        $banco = new BancodeDados;
                        $sql = 'SELECT *
                                     , COALESCE(colaboradores.telefone, "Sem Telefone Cadastrado") AS telefone
                                     , COALESCE(colaboradores.data_nascimento, "Sem Data de Nascimento") AS data_nascimento_format
                                     , COALESCE(departamentos.nome_departamento, "Sem Departamento") AS nome_departamento
                                FROM colaboradores
                                LEFT JOIN departamentos ON colaboradores.id_departamento = departamentos.id_departamento 
                                WHERE colaboradores.ativo = 1';
                        $dados = $banco->Consultar($sql, [], true);
                        if ($dados)     
                        {
                            foreach ($dados as $linha) 
                            {
                                $caminho_imagem = 'src/colaboradores/upload/' . $linha['imagem_colaborador'];
                                $imagem_existe = file_exists($caminho_imagem);
                                if($linha['data_nascimento_format'] != "Sem Data de Nascimento")
                                {   
                                    $data_nascimento = DateTime::createFromFormat('Y-m-d', $linha['data_nascimento'])->format('d/m/y');
                                }
                                else
                                {
                                    $data_nascimento = "Sem Data de Nascimento";
                                }
                               
                                echo 
                                "<tr class='text-center'>
                                    <td>{$linha['id_colaborador']}</td>
                                    <td>{$linha['nome_colaborador']}</td>
                                    <td>{$data_nascimento}</td>
                                    <td>{$linha['telefone']}</td>
                                    <td>{$linha['nome_departamento']}</td>
                                    <td>
                                        " . ($imagem_existe ? "<a href='#' onclick='AbrirImagem(\"{$caminho_imagem}\")'><i class='bi bi-image'></i></a>" : "<i class='bi bi-image' style='color: gray;'></i>") . "
                                        <a href='#' onclick='AlterarColaborador({$linha['id_colaborador']})'><i class='bi bi-pencil-square'></i></a>
                                        <a href='#' onclick='ExcluirColaborador({$linha['id_colaborador']})'><i class='bi bi-trash3-fill'></i></a>
                                    </td>
                                </tr>";
                            }
                        } 
                        else 
                        {
                            echo "<tr><td colspan='8' class='text-center'>Nenhum colaborador cadastrado</td></tr>";
                        }
                    } 
                    catch (PDOException $erro) {
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
                        <th scope="col">Telefone</th>
                        <th scope="col">Departamento</th>
                        <th scope="col">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    try 
                    {
                        include_once 'src/class/BancodeDados.php';
                        $banco = new BancodeDados;
                        $sql = 'SELECT *
                                     , COALESCE(departamentos.nome_departamento, "Sem Departamento") AS nome_departamento
                                FROM colaboradores
                                LEFT JOIN departamentos ON colaboradores.id_departamento = departamentos.id_departamento 
                                WHERE colaboradores.ativo = 0';
                        $dados = $banco->Consultar($sql, [], true);
                        if ($dados) 
                        {
                            foreach ($dados as $linha) 
                            {
                                $data_nascimento = DateTime::createFromFormat('Y-m-d', $linha['data_nascimento'])->format('d/m/y');
                                echo 
                                "<tr class='text-center'>
                                    <td>{$linha['id_colaborador']}</td>
                                    <td>{$linha['nome_colaborador']}</td>
                                    <td>{$data_nascimento}</td>
                                    <td>{$linha['telefone']}</td>
                                    <td>{$linha['nome_departamento']}</td>
                                    <td>
                                        <a href='#' onclick='ReativarColaborador({$linha['id_colaborador']})'>Reativar</a>
                                    </td>
                                </tr>";
                            }
                        } 
                        else 
                        {
                            echo "<tr><td colspan='8' class='text-center'>Nenhum colaborador inativado</td></tr>";
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
<?php
    include_once 'src/class/BancodeDados.php';
    $banco = new BancodeDados;
    $sql = 'SELECT id_departamento, nome_departamento FROM departamentos WHERE ativo = 1';
    $departamentos = $banco->Consultar($sql, [], true);
?>
<!-- Modal Principal -->
<div id="adicionar_colaborador" class="modal fade" data-bs-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="form_colaborador" enctype="multipart/form-data">
                <div class="modal-header">
                    <h4 class="modal-title" id="modalLabel">Colaborador</h4>
                    <button onclick="window.location.reload()" type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <ul class="nav nav-tabs" id="colaboradoresTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active" id="dados-tab" data-bs-toggle="tab" href="#dados" role="tab" aria-controls="dados" aria-selected="true">Dados Pessoais</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="endereco-tab" data-bs-toggle="tab" href="#endereco" role="tab" aria-controls="endereco" aria-selected="false">Endereço</a>
                    </li>
                </ul>
                <div class="modal-body">
                    <input type="hidden" name="txt_id" id="txt_id" value="NOVO">
                    <div class="tab-content" id="colaboradoresTabContent">
                        <div class="tab-pane fade show active" id="dados" role="tabpanel" aria-labelledby="dados-tab">
                            <div class="form-group">
                                <label for="txt_nome">Nome</label>
                                <input type="text" class="form-control" name="txt_nome" id="txt_nome" required maxlength="255">
                            </div>
                            <div class="form-group">
                                <label for="txt_data_nasc">Data Nascimento</label>
                                <input type="date" class="form-control" name="txt_data_nasc" id="txt_data_nasc" required>
                            </div>
                            <div class="form-group">
                                <label for="txt_cpf_cnpj">CPF/CNPJ</label>
                                <input type="text" class="form-control" name="txt_cpf_cnpj" id="txt_cpf_cnpj" required minlength="11" maxlength="18">
                            </div>
                            <div class="form-group">
                                <label for="txt_rg">RG</label>
                                <input type="text" class="form-control" name="txt_rg" id="txt_rg" minlength="7" maxlength="9">
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="txt_telefone">Telefone</label>
                                        <input type="tel" class="form-control" name="txt_telefone" id="txt_telefone" required minlength="10" maxlength="14">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="opt_departamento">Departamento</label>
                                        <select class="form-control" id="opt_departamento" name="opt_departamento" required>
                                            <option value="0">Selecione o Departamento</option>
                                            <?php foreach ($departamentos as $departamento): ?>
                                                <option value="<?= $departamento['id_departamento']; ?>">
                                                    <?= $departamento['nome_departamento']; ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                            </div>  
                            <div class="form-group">
                                <label for="file_imagem">Foto do Colaborador</label>
                                <input type="file" class="form-control" id="file_imagem" value="S/IMG">
                                <button class="btn btn-danger" id="btnLimparImagem" onclick="LimparImagem()">Limpar Imagem</button>
                            </div>
                            <div class="form-group" id="imagemContainer">
                                <img id="caminho_imagem" class="img-thumbnail" height="300">
                                <button class="btn btn-danger" id="btnDesvincular" onclick="DesvincularImagem()">Desvincular Imagem</button>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="endereco" role="tabpanel" aria-labelledby="endereco-tab">
                            <div class="form-group">
                                <label for="txt_cep" class="form-label">CEP</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="txt_cep" maxlength="9" minlength="8">
                                    <button type="button" class="btn btn-primary" onclick="ConsultarCep()">Buscar</button>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="list_uf" class="form-label">UF</label>
                                <select class="form-select" id="list_uf" onchange="CarregarCidades(this.value)">
                                    <option value="">Escolha...</option>
                                    <?php
                                    $ufs = 
                                    [
                                        'AC',
                                        'AL',
                                        'AP',
                                        'AM',
                                        'BA',
                                        'CE',
                                        'DF',
                                        'ES',
                                        'GO',
                                        'MA',
                                        'MT',
                                        'MS',
                                        'MG',
                                        'PA',
                                        'PB', 
                                        'PR',
                                        'PE',
                                        'PI',
                                        'RJ',
                                        'RN',
                                        'RS',
                                        'RO',
                                        'RR',
                                        'SC',
                                        'SP',
                                        'SE',
                                        'TO'
                                    ];
                                    foreach ($ufs as $uf): 
                                    ?>
                                        <option value="<?= $uf; ?>"><?= $uf; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="list_cidade" class="form-label">Cidade</label>
                                <select class="form-select" id="list_cidade">
                                    <option value="">Escolha...</option>
                                </select>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="txt_rua">Rua</label>
                                        <input type="text" class="form-control" id="txt_rua" maxlength="255">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="txt_numero">Número</label>
                                        <input type="number" class="form-control" id="txt_numero">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="txt_bairro" class="form-label">Bairro</label>
                                <input type="text" class="form-control" id="txt_bairro" maxlength="255">
                            </div>
                            <div class="form-group">
                                <label for="txt_complemento" class="form-label">Complemento</label>
                                <input type="text" class="form-control" id="txt_complemento" maxlength="255">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-success" onclick="CadastrarColaborador()">Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Modal para imagem de colaborador -->
<div id="imagem_colaborador" class="modal fade" data-bs-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modalLabel">Imagem do Equipamento</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
                <img id="visualiza_imagem_colaborador" alt="Imagem do colaborador" class="img-fluid" style="display:block" height="500px" width="500px">
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
    $('#form_colaborador').submit(function() 
    {
        return false;
    });
    function ModalImagemColaborador()
    {
        var modal = new bootstrap.Modal(document.getElementById('imagem_colaborador'));
        modal.show();
    }
    function CadastrarColaborador()
    {
        var id           = document.getElementById('txt_id').value;
        var nome         = document.getElementById('txt_nome').value;
        var data_nasc    = document.getElementById('txt_data_nasc').value;
        var cpf_cnpj     = document.getElementById('txt_cpf_cnpj').value;
        var rg           = document.getElementById('txt_rg').value;
        var telefone     = document.getElementById('txt_telefone').value;
        var inputFile    = document.getElementById('file_imagem').files[0];
        var departamento = document.getElementById('opt_departamento').value;
        var cep          = document.getElementById('txt_cep').value;
        var rua          = document.getElementById('txt_rua').value;
        var bairro       = document.getElementById('txt_bairro').value;
        var cidade       = document.getElementById('list_cidade').value;
        var uf           = document.getElementById('list_uf').value;
        var complemento  = document.getElementById('txt_complemento').value;
        var numero       = document.getElementById('txt_numero').value;
        // Verificação básica de campos preenchidos
        var formData = new FormData(); 
        formData.append('id', id);
        formData.append('nome', nome);
        formData.append('data_nasc', data_nasc);
        formData.append('cpf_cnpj', cpf_cnpj);
        formData.append('rg', rg);
        formData.append('telefone', telefone);
        formData.append('departamento', departamento);
        formData.append('cep', cep);
        formData.append('rua', rua);
        formData.append('bairro', bairro);
        formData.append('cidade', cidade);
        formData.append('uf', uf);
        formData.append('complemento', complemento);
        formData.append('numero', numero);
        if(data_nasc == '')
        {
            alert('Data de Nascimento é obrigatório e obrigatório');
            return false;
        }
        if(telefone == '')
        {
            alert('Telefone é obrigatório');
            return false;
        }
        if (inputFile) 
        {
            formData.append('file_imagem', inputFile);
        }
        $.ajax({
            type: 'post',
            url: './src/colaboradores/cadastrar_colaborador.php',
            data: formData,
            contentType: false,
            processData: false,
            success: function(retorno) 
            {
                if (retorno['codigo'] == 2) 
                {
                    alert(retorno['mensagem']);
                    window.location = 'sistema.php?tela=colaboradores';
                } 
                else if(retorno['codigo'] == 3) 
                {
                    alert(retorno['mensagem']);
                    window.location = 'sistema.php?tela=colaboradores';
                }
            },
            error: function(erro) 
            {
                alert('Ocorreu um erro na requisição: ' + erro.responseText);
            }
        });
    }
    function ExcluirColaborador(id) {
        if (confirm('Tem certeza que deseja excluir este Colaborador?')) 
        {
            $.ajax({
                type: 'post',
                datatype: 'json',
                url: './src/colaboradores/excluir_colaborador.php',
                data: { 'id': id },
                success: function(retorno) 
                {
                    if (retorno.codigo == 2) 
                    {
                        alert(retorno['mensagem']);
                        window.location = 'sistema.php?tela=colaboradores';
                    } 
                    else if (retorno['codigo'] == 5)
                    {
                        alert(retorno['mensagem']);
                        window.location = 'sistema.php?tela=colaboradores';
                    }
                },
                error: function(erro) 
                {
                    alert('Ocorreu um erro na requisição: ' + erro.responseText);
                }
            });
        }
    }   
    function ReativarColaborador(id)
    {
        $.ajax({
            type: 'post',
            datatype: 'json',
            url: './src/colaboradores/reativar_colaborador.php',
            data: { 'id': id },
            success: function(retorno) {
                if (retorno['codigo'] == 2) 
                {
                    alert(retorno['mensagem']);
                    window.location = 'sistema.php?tela=colaboradores';
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
    function AlterarColaborador(id)
    {   
        $.ajax({    
            type: 'get',
            url: './src/colaboradores/get_colaborador.php',
            data: { 'id': id },
            success: function(retorno) {
                var colaborador = JSON.parse(retorno);
                document.getElementById('txt_id').value = colaborador.id;
                document.getElementById('txt_nome').value = colaborador.nome;
                document.getElementById('txt_data_nasc').value = colaborador.data_nascimento;
                document.getElementById('txt_cpf_cnpj').value = colaborador.cpf_cnpj;
                document.getElementById('txt_rg').value = colaborador.rg;
                document.getElementById('txt_telefone').value = colaborador.telefone;
                document.getElementById('opt_departamento').value = colaborador.departamento;
                document.getElementById('txt_cep').value = colaborador.cep;
                document.getElementById('txt_rua').value = colaborador.rua;
                document.getElementById('txt_bairro').value = colaborador.bairro;
                document.getElementById('list_uf').value = colaborador.uf;
                document.getElementById('list_uf').dispatchEvent(new Event('change')) 
                document.getElementById('txt_numero').value = colaborador.numero;
                document.getElementById('txt_complemento').value = colaborador.complemento;
                setTimeout(function(){
                    document.getElementById('list_cidade').value = colaborador.cidade;
                }, 1000);
                // Exibir o modal de edição
                const caminhoImagem = document.getElementById('caminho_imagem');
                const btnDesvincular = document.getElementById('btnDesvincular');
                caminhoImagem.style.display = 'none'; 
                btnDesvincular.style.display = 'none';
                caminhoImagem.src = 'src/colaboradores/upload/' + colaborador.imagem_colaborador;
                if(colaborador.imagem_colaborador != 'vazio')
                {
                    caminhoImagem.style.display = 'block';
                    btnDesvincular.style.display = 'block';
                }
                EditarColaboradorModal();   
            },
            error: function(erro) 
            {
                alert('Ocorreu um erro na requisição: ' + erro.responseText);
            }
        });
    }
    function ConsultarCep() 
    {
        var cep = document.getElementById('txt_cep').value;
        if(cep.length < 8)
        {
            alert("Por favor, informe o CEP corretamente!");
            return false;
        }
        cep = cep.replace(/[^a-zA-Z0-9]/g, '');
        $.ajax({
            type: 'get',
            dataType: 'json',
            url: 'https://viacep.com.br/ws/' + cep + '/json/',
            success: function(retorno) {
                document.getElementById('txt_rua').value        = retorno.logradouro;
                document.getElementById('txt_bairro').value     = retorno.bairro;
                document.getElementById('list_uf').value        = retorno.uf;
                document.getElementById('list_uf').dispatchEvent(new Event('change'))      
                // Atrasando a execução do comando
                setTimeout(function(){
                    document.getElementById('list_cidade').value = retorno.localidade;
                }, 1000);
            },
            error: function(erro) {
                alert('Ocorreu um erro na requisição: ' + erro);
            }
        });
    }
    function CarregarCidades(uf) 
    {
        $.ajax({
            type: 'get',
            dataType: 'json',
            url: 'https://servicodados.ibge.gov.br/api/v1/localidades/estados/' + uf + '/municipios',
            success: function(retorno) {
                document.getElementById('list_cidade').innerHTML = "<option value=''>Escolha...</option>";
                $.each(retorno, function(chave, valor) {
                    var option = document.createElement('option');
                    option.value = valor.nome;
                    option.text = valor.nome;
                    document.getElementById('list_cidade').appendChild(option);
                });
            },
            error: function(erro) {
                alert('Ocorreu um erro na requisição: ' + erro);
            }
        });
    }
    function DesvincularImagem()
    {
        var id = document.getElementById('txt_id').value;  
        $.ajax({
            type: 'post',
            url: './src/colaboradores/desvincular_imagem.php',
            data: { 'id': id },
            success: function(retorno) 
            {
                if (retorno['codigo'] == 2) 
                {
                    alert(retorno['mensagem']);
                    AlterarColaborador(id);
                } 
                else if(retorno['codigo'] == 0)
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
    function LimparImagem()
    {    
        const btn_limpar = document.getElementById('btnLimparImagem');
        const input_imagem = document.getElementById('file_imagem');
        btn_limpar.style.display = 'none';
        input_imagem.value = ''; 
    }
    const btn_limpar = document.getElementById('btnLimparImagem');
    const input_imagem = document.getElementById('file_imagem');
    const btn_desvincular = document.getElementById('btnDesvincular');
    btn_desvincular.style.display = 'none';
    btn_limpar.style.display = 'none';
    input_imagem.addEventListener('change', function() {
    var img_selecionada = input_imagem.files[0];
    if (img_selecionada) 
    {
        btn_limpar.style.display = 'block'; // Mostra o botão se uma imagem foi selecionada
    } 
    else 
    {
        btn_limpar.style.display = 'none'; // Esconde o botão se não houver imagem
    }
    });
    function AbrirImagem(caminho_imagem)
        {
            const caminhoImagem = document.getElementById('visualiza_imagem_colaborador');
            caminhoImagem.src = caminho_imagem; 
            ModalImagemColaborador(); 
        }
</script>