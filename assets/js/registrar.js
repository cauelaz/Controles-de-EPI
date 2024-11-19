function Login() {
    window.location = 'sistema.php';
}
// Desativa o submit do formulário
$('#registrarform').submit(function() {
    return false; // Evita o envio padrão do formulário
});
function Registrar() {
    var usuario = document.getElementById('txt_usuario').value;
    var senha = document.getElementById('txt_senha').value;
    var adm = document.getElementById('list_user').value;

    if (usuario && senha) { // Validação simples
        $.ajax({
            type: 'post',
            datatype: 'json',
            url: './src/usuarios/registrar_usuario.php',
            data: {
                'usuario': usuario,
                'senha': senha,
                'adm': adm
            },
            success: function(retorno) {
                if (retorno['codigo'] == 2) {
                    alert(retorno['mensagem']);
                    window.location = 'index.php';
                } 
                else if (retorno['codigo'] == 0)
                {
                    alert(retorno['mensagem']);
                    window.location = 'registrar.php';
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