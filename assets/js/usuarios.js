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
    return false; 
});
function CadastrarUsuario() 
{
    var id      = document.getElementById('txt_id').value;
    var usuario = document.getElementById('txt_nome').value;
    var senha   = document.getElementById('txt_senha').value;
    var adm     = document.getElementById('list_user').value;
    if(adm == "")
    {
        alert("Escolha um tipo de usuário!");
        return false;
    }
    if (usuario && senha) 
    { 
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
                else if (retorno['codigo'] == 0)
                {
                    alert(retorno['mensagem']);
                    LimpaCampos();
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
function AlterarUsuario(id)
{
    $.ajax({
        type: 'get',
        url: './src/usuarios/get_usuario.php',
        data: { 'id': id },
        success: function(retorno) 
        {
            var usuario = JSON.parse(retorno); 
            document.getElementById('txt_id').value = usuario.id;
            document.getElementById('txt_nome').value = usuario.nome;
            document.getElementById('txt_senha').value = usuario.senha;
            document.getElementById('list_user').value = usuario.administrador;
            EditarUsuarioModal()
        },
        error: function(erro) 
        {
            alert('Ocorreu um erro na requisição: ' + erro.responseText);
        }
    });
}
function ReativarUsuario(id)
{
    $.ajax({
        type: 'post',
        datatype: 'json',
        url: './src/usuarios/reativar_usuario.php',
        data: { 'id': id },
        success: function(retorno) {
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
            alert('Ocorreu um erro na requisição: ' + erro.responseText);
        }
    });
}
function LimpaCampos()
{
    document.getElementById('txt_id').value = '';
    document.getElementById('txt_nome').value = '';
    document.getElementById('txt_senha').value = '';
    document.getElementById('list_user').value = '';
}