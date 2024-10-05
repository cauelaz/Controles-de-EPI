<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - EPIs</title>
    <link href="assets/css/index.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="d-flex align-items-center py-4 bg-body-tertiary">
<button class="btn btn-outline-primary btn-login" onclick="Login()">
    <i class="bi bi-box-arrow-in-right"></i> Tela de Login
</button>
    <main class="form-signin w-100 m-auto">
        <form action="src/usuarios/novo_usuario.php" method="post">
            <h1 class="h3 mb-3 fw-normal text-center">Registro de Usuário</h1>
            <div class="form-floating">
                <input type="text" class="form-control" name="txt_usuario" id="txt_usuario" required>
                <label for="txt_usuario">Usuário</label>
            </div>
            <div class="form-floating">
                <input type="password" class="form-control" name="txt_senha" id="txt_senha" required>
                <label for="txt_senha">Senha</label>
            </div>
            <div class="form-floating">
                <input type="text" class="form-control" value="Usuário" readonly>
                <input type="hidden" name="list_user" value="0">
                <label for="list_user">Tipo de Usuário</label>
            </div>
            <button class="btn btn-success w-100 py-2" type="submit">Registrar</button>
            <p class="mt-5 mb-3 text-body-secondary text-center">&copy; 2024</p>
        </form>
    </main>
</body>
</html>   
<script>
    function Login()
    {
        window.location = 'sistema.php';
    }
</script>  