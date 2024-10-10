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
                    $sql = 'SELECT * FROM usuarios WHERE ativo = 1 ';
                    $dados = $banco->Consultar($sql, [], true);
                    if ($dados) 
                    {
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
                                    <a href='sistema.php?tela=usuarios&acao=alterarusuario&IdUsuario={$linha['id_usuario']}'><i class='bi bi-pencil-square'></i></a>
                                    <a href='#' onclick='ExcluirUsuario({$linha['id_usuario']})'><i class='bi bi-trash3-fill'></i></a>
                                </td>
                            </tr>";
                        }
                    } 
                    else 
                    {
                        echo 
                        "<tr>
                            <td colspan='5' class='text-center'>Nenhum usuário cadastrado...</td>
                        </tr>";
                    }
                } 
                catch (PDOException $erro) 
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
<div id="adicionar_usuario" class="modal fade" data-bs-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="form_usuario">
                <div class="modal-header">
                    <h4 class="modal-title" id="modalLabel">Usuário</h4>
                    <button onclick="window.location.href='sistema.php?tela=usuarios'" type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
<script>
    function abrirModal() 
    {
        $('#adicionar-produto').modal('show');
    }
    function EditarUsuarioModal()
    {
        var modal = new bootstrap.Modal(document.getElementById('adicionar_usuario'));
        modal.show();
    }
    $('#form_usuario').submit(function() 
    {
        return false; // Evita o envio padrão do formulário
    });
    function CadastrarUsuario() 
    {
        var id      = document.getElementById('txt_id').value;
        var usuario = document.getElementById('txt_nome').value;
        var senha   = document.getElementById('txt_senha').value;
        var adm     = document.getElementById('list_user').value;

        if (usuario && senha) 
        { // Validação simples
            $.ajax({
                type: 'post',
                datatype: 'json',
                url: './src/usuarios/cadastrar_usuario.php',
                data: 
                {
                    'id': id,
                    'usuario': usuario,
                    'senha': senha,
                    'adm': adm
                },
                success: function(retorno) 
                {
                    if (retorno['codigo'] == 2) 
                    {
                        alert(retorno['mensagem']);
                        window.location = 'sistema.php?tela=usuarios';
                    }
                    else 
                    {
                        alert(retorno['mensagem']);
                        window.location = 'sistema.php?tela=usuarios';
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
    function ExcluirUsuario(id)
    {
        if (confirm('Tem certeza que deseja excluir este usuário?')) 
        {
            $.ajax({
                type: 'post',
                datatype: 'json',
                url: './src/usuarios/excluir_usuario.php',
                data: { 'id': id },
                success: function(retorno) 
                {
                    if (retorno['codigo'] == 2) 
                    {
                        alert(retorno['mensagem']);
                        window.location = 'sistema.php?tela=usuarios';
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
</script>