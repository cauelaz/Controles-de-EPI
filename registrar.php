<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - EPIs</title>
    <!--Jquery-->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <!--Bootstrap-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/index.css" rel="stylesheet">
    <script src="assets/js/registrar.js"></script>
</head>
<body class="d-flex align-items-center py-4 bg-body-tertiary">
    <button class="btn btn-outline-primary btn-login" onclick="Login()">
        <i class="bi bi-box-arrow-in-right"></i> Tela de Login
    </button>
    <main class="form-signin w-100 m-auto">
        <form id="registrarform">
            <h1 class="h3 mb-3 fw-normal text-center">Registro de Usuário</h1>
            <div class="form-floating">
                <input type="text" class="form-control" id="txt_usuario" required placeholder="Usuário">
                <label for="txt_usuario">Usuário</label>
            </div>
            <div class="form-floating">
                <input type="password" class="form-control" id="txt_senha" required placeholder="Senha">
                <label for="txt_senha">Senha</label>
            </div>
            <div class="form-floating">
                <input type="text" class="form-control" value="Usuário" readonly>
                <input type="hidden" id="list_user" name="list_user" value="0">
                <label for="list_user">Tipo de Usuário</label>
            </div>
            <button class="btn btn-success w-100 py-2" type="button" onclick="Registrar()">Registrar</button>
            <p class="mt-5 mb-3 text-body-secondary text-center">EPIs Control &copy; 2024</p>
        </form>
    </main>
</body>
</html> 