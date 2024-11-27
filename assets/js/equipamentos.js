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
function ModalCodigoBarras()
{
    var modal = new bootstrap.Modal(document.getElementById('codigo_barras'));
    modal.show();
}
function ModalImagemEquipamento()
{
    var modal = new bootstrap.Modal(document.getElementById('imagem_equipamento'));
    modal.show();
}
$('#form_equipamento').submit(function() 
{
    return false;
});
$('#form_ajuste_equipamento').submit(function() 
{
    return false;
});
function CadastrarEquipamento() 
{
    var id              = document.getElementById('txt_id').value;
    var descricao       = document.getElementById('txt_descricao').value;
    var estoque         = document.getElementById('txt_estoque').value;
    var cert_aprovacao  = document.getElementById('txt_cert_aprovacao').value;
    var inputFile       = document.getElementById('file_imagem').files[0];
    if (descricao && estoque && cert_aprovacao) 
    {
        var formData = new FormData();
        formData.append('id', id);
        formData.append('descricao', descricao);
        formData.append('estoque', estoque);
        formData.append('cert_aprovacao', cert_aprovacao);
        if (inputFile) 
        {
            formData.append('file_imagem', inputFile);
        }
        $.ajax({
            type: 'post',
            url: './src/equipamentos/cadastrar_equipamento.php',
            data: formData,
            contentType: false, 
            processData: false, 
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
            success: function(retorno) 
            {
                if (retorno.codigo == 2) 
                {
                    alert(retorno['mensagem']);
                    window.location = 'sistema.php?tela=equipamentos'; 
                } 
                else if (retorno['codigo'] == 5)
                {
                    alert(retorno['mensagem']);
                    window.location = 'sistema.php?tela=equipamentos';
                }
            },
            error: function(erro) 
            {
                alert('Ocorreu um erro na requisição: ' + erro.responseText);
            }
        });
    }
} 
function ReativarEquipamento(id)
{
    $.ajax({
    type: 'post',
    datatype: 'json',
    url: './src/equipamentos/reativar_equipamento.php',
    data: { 'id': id },
    success: function(retorno) {
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
var emprestados;
function GetAjustarEstoque(id) 
{
    $.ajax({
        type: 'get',
        url: './src/equipamentos/get_equipamentos.php', 
        data: { 'id': id },
        success: function(retorno) 
        {
            var equipamento = JSON.parse(retorno);
            document.getElementById('txt_id_estoque').value = equipamento.id;
            document.getElementById('txt_descricao_estoque').value = equipamento.descricao;
            document.getElementById('txt_estoque_ajuste').value = equipamento.qtd_estoque;
            emprestados = equipamento.emprestados;
            AjustarEstoqueModal();
        },
        error: function(erro) 
        {
            alert('Ocorreu um erro na requisição: ' + erro.responseText);
        }
    });
}
function AlterarEquipamento(id) 
{
    $.ajax({
        type: 'get',
        url: './src/equipamentos/get_equipamentos.php',
        data: { 'id': id },
        success: function(retorno) {
            var equipamento = JSON.parse(retorno); 
            document.getElementById('txt_id').value = equipamento.id;
            document.getElementById('txt_descricao').value = equipamento.descricao;
            document.getElementById('txt_estoque').value = equipamento.qtd_estoque;
            document.getElementById('txt_cert_aprovacao').value = equipamento.certificado_aprovacao;
            document.getElementById('txt_estoque').readOnly = true;
            const caminhoImagem = document.getElementById('caminho_imagem');
            const btnDesvincular = document.getElementById('btnDesvincular');
            const btnLimpar = document.getElementById('btnLimparImagem');
            caminhoImagem.style.display = 'none'; 
            btnDesvincular.style.display = 'none';
            caminhoImagem.src = 'src/equipamentos/upload/' + equipamento.imagem_equipamento;
            if(equipamento.imagem_equipamento != 'vazio')
            {
                caminhoImagem.style.display = 'block'; // Exibe a imagem
                btnDesvincular.style.display = 'block'; // Exibe o botão de desvincular
            }
            EditarEquipamentoModal();
            },
            error: function(erro) {
                alert('Ocorreu um erro na requisição: ' + erro.responseText);
            }
    });
}
function AjustarEstoque()
{
    var id = document.getElementById('txt_id_estoque').value;
    var qtd_estoque = document.getElementById('txt_estoque_ajuste').value;
    var qtd_ajuste = document.getElementById('txt_new_qtd_estoque').value;
    if (qtd_estoque && qtd_ajuste) 
    {
        $.ajax({
            type: 'post',
            url: './src/equipamentos/ajuste_estoque.php',
            data: 
            { 
                'id': id,
                'qtd_estoque': qtd_estoque,
                'qtd_ajuste': qtd_ajuste,
                'emprestados': emprestados
            },
            success: function(retorno) 
            {
                if (retorno['codigo'] == 2) 
                {
                    alert(retorno['mensagem']);
                    window.location = 'sistema.php?tela=equipamentos';
                } 
                else if(retorno['codigo'] == 3)
                {
                    alert(retorno['mensagem']);
                    window.location = 'sistema.php?tela=equipamentos';
                }
            },
            error: function(erro) 
            {
                alert('Ocorreu um erro na requisição: ' + erro.responseText);
            }
        });
    }
}   
function GerarCodigoBarras(id) 
{
    var barcodeUrl = 'https://www.barcodesinc.com/generator/image.php?code=' + id + '&style=197&type=C128B&width=300&height=100&xres=1&font=3';
    $('#img_barras').attr('src', barcodeUrl);
    ModalCodigoBarras();
}
function DesvincularImagem()
{
    var id = document.getElementById('txt_id').value;  
    $.ajax({
        type: 'post',
        url: './src/equipamentos/desvincular_imagem.php',
        data: { 'id': id },
        success: function(retorno) 
        {
            if (retorno['codigo'] == 2) 
            {
                alert(retorno['mensagem']);
                AlterarEquipamento(id);
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
    const caminhoImagem = document.getElementById('visualiza_imagem');
    caminhoImagem.src = caminho_imagem; 
    ModalImagemEquipamento(); 
}