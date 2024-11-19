<?php
    session_start();
    if (isset($_SESSION['logado'])) {
        header('LOCATION: sistema.php');
    }
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EPI's Control</title>
    <link href="assets/css/index.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!--Jquery-->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <!--Bootstrap-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/index.js"></script>
</head>
<body class="d-flex align-items-center py-4 bg-body-tertiary">
    <main class="form-signin w-100 m-auto">
        <form id="loginform">
            <h1 class="h3 mb-3 fw-normal text-center" style="font-family: Arial, Helvetica, sans-serif;">Login</h1>
            <div class="form-floating">
                <input type="text" class="form-control" id="txt_usuario" placeholder="Usuário">
                <label for="txt_usuario">Usuário</label>
            </div>
            <div class="form-floating mb-1">
                <input type="password" class="form-control" id="txt_senha" placeholder="Senha">
                <label for="txt_senha">Senha</label>
            </div>
            <div class="form-check text-start">        
                <a href="registrar.php" class="btn btn-link text-decoration-none">Cadastre-se</a>
            </div>
            <button class="btn btn-primary w-100 py-2 mt-1" onclick="login()">Entrar</button>
            <p class="mt-5 mb-3 text-body-secondary text-center">EPI's Control &copy; 2024</p>
        </form>
    </main>
</body>
</html>