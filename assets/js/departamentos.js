function abrirModal() 
{
    $('#adicionar_departamento').modal('show');
}
function EditarDepartamentoModal()
{
    var modal = new bootstrap.Modal(document.getElementById('adicionar_departamento'));
    modal.show();
}
$('#form_departamento').submit(function() 
{
    return false;
});
function CadastrarDepartamento() 
{
    var id    = document.getElementById('txt_id').value;
    var nome  = document.getElementById('txt_nome').value;
    if (nome) 
    { 
        $.ajax({
            type: 'post',
            datatype: 'json',
            url: './src/departamentos/cadastrar_departamento.php',
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
                    window.location = 'sistema.php?tela=departamentos';
                }
                else 
                {
                    alert(retorno['mensagem']);
                    window.location = 'sistema.php?tela=departamentos';
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
function ExcluirDepartamento(id)
{
    if (confirm('Tem certeza que deseja excluir este departamento?')) 
    {
        $.ajax({
            type: 'post',
            datatype: 'json',
            url: './src/departamentos/excluir_departamento.php',
            data: { 'id': id },
            success: function(retorno) 
            {
                if (retorno['codigo'] == 2) 
                {
                    alert(retorno['mensagem']);
                    window.location = 'sistema.php?tela=departamentos';
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
function AlterarDepartamento(id)
{
    $.ajax({
        type: 'get',
        url: './src/departamentos/get_departamentos.php', 
        data: { 'id': id },
        success: function(retorno) 
        {
            var departamento = JSON.parse(retorno); // Converter o retorno para objeto JavaScript
            document.getElementById('txt_id').value = departamento.id;
            document.getElementById('txt_nome').value = departamento.nome;
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
        url: './src/departamentos/reativar_departamento.php',
        data: { 'id': id },
        success: function(retorno) {
            if (retorno['codigo'] == 2) 
            {
                alert(retorno['mensagem']);
                window.location = 'sistema.php?tela=departamentos';
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