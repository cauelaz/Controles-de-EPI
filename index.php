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
    <title>Login - EPIs</title>
    <link href="assets/css/index.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body class="d-flex align-items-center py-4 bg-body-tertiary">
    <main class="form-signin w-100 m-auto">
        <form action="src/login.php" method="post">
            <h1 class="h3 mb-3 fw-normal text-center" style="font-family: Arial, Helvetica, sans-serif;">Realize o Login</h1>
            <div class="form-floating">
                <input type="text" class="form-control" name="txt_usuario" id="txt_usuario">
                <label for="txt_usuario">Usuário</label>
            </div>
            <div class="form-floating">
                <input type="password" class="form-control" name="txt_senha" id="txt_senha">
                <label for="txt_senha">Senha</label>
            </div>
            <div class="form-check text-start my-3">
                <a href="registrar.php" class="btn btn-link text-decoration-none">Novo Usuário</a>
            </div>
            <div class="form-check text-start my-3">
                <input class="form-check-input" type="checkbox" value="true" name="check_lembrar" id="check_lembrar">
                <label class="form-check-label" for="check_lembrar">Manter-me conectado</label>
            </div>
            <button class="btn btn-primary w-100 py-2" type="submit">Entrar</button>
            <p class="mt-5 mb-3 text-body-secondary text-center">&copy; 2024</p>
        </form>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>