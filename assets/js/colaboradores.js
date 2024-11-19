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