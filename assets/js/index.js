$('#loginform').submit(function()
{
    return false;
});
function login()
{
    var usuario = document.getElementById('txt_usuario').value
    var senha = document.getElementById('txt_senha').value
    $.ajax(
    {
        type: 'post',
        datatype:'json',
        url:'src/login.php',
        data:
        {
            'usuario': usuario,
            'senha': senha
        },
        success: function(retorno)
        {
            if(retorno['codigo'] == 2)
            {
                window.location = 'sistema.php';
            }
            else
            {
                alert(retorno['mensagem']);
                window.location = 'index.php';
            }
        },
        error:function(erro)
        {
            alert('Ocorreu um erro na requisição: ' + erro);
        }
    });
}