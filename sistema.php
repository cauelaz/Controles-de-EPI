<?php
    // Controle de sessão
    // Se NÃO existir uma sessão, redireciona o usuário para o index.php
    // Não permitindo que seja exibido o conteúdo da página, 
    // sem que exista uma sessão (login autenticado)
    session_start();
    if (!isset($_SESSION['logado'])) {
        header('LOCATION: index.php');
        exit;
    }
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Controle de EPIs</title>
    <link href="assets/css/sistema.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
</head>
<body>
<header class="navbar sticky-top bg-white flex-md-nowrap p-0 shadow" data-bs-theme="white">
    <div class="d-flex align-items-center col-md-3 col-lg-2">
        <!-- Envolvendo imagem e texto no link -->
        <a class="d-flex align-items-center text-dark text-decoration-none" href="sistema.php">
            <img class="img-thumbnail img-fluid me-2" src="assets/img/trabalhador.png" alt="logo" style="width: 64px; height: auto;">
            Controle de EPIs    
        </a>
        <ul class="d-flex flex-wrap align-items-center ms-auto justify-content-center justify-content-lg-start nav col-13 col-lg-auto">
            <li class="nav-link"><a class="nav-link d-flex ms-5" href="sistema.php?tela=equipamentos"><i class="bi bi-hammer"></i>Equipamentos</a></li>
            <li class="nav-link"><a class="nav-link d-flex ms-5" href="sistema.php?tela=colaboradores"><i class="bi bi-people"></i>Colaboradores</a></li>
            <li class="nav-link"><a class="nav-link d-flex ms-5" href="sistema.php?tela=usuarios"><i class="bi bi-person"></i>Usuários</a></li>
            <li class="nav-link"><a class="nav-link d-flex ms-5" href="sistema.php?tela=emprestimos"><i class="bi bi-arrow-left-right"></i>Empréstimos</a></li>
            <li class="nav-link"><a class="nav-link d-flex ms-5" href="#" onclick="sair()"><i class="bi bi-box-arrow-right"></i>Sair</a></li>
        </ul>
    </div>
    <ul class="navbar-nav flex-row d-md-none">
        <li class="nav-item text-nowrap">
            <button class="nav-link px-3 text-white" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarMenu">
                <svg class="bi">
                    <use xlink:href="#list"></use>
                </svg>
            </button>
        </li>
    </ul>
</header>
    <div class="container-fluid">
        <div class="row">
            <main>
                <!-- Script para importar as telas do sistema -->
                <?php
                    $tela = isset($_GET['tela']) ? $_GET['tela'] : '';
                    switch ($tela) {
                        case 'colaboradores':
                            include 'telas/colaboradores.php';
                            break;
                        case 'equipamentos':
                            include 'telas/equipamentos.php';
                            break;
                            case 'usuarios':
                                include 'telas/usuarios.php';
                            break;
                        case 'emprestimos':
                            include 'telas/emprestimo.php';
                            break;
                        default:
                            echo 
                            '<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                                <h1 class="h2">Bem-vindo <strong>' . $_SESSION['nome_usuario'] . '</strong>!</h1>
                            </div>';
                            break;
                    }
                ?>
            </main>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script>
        function sair() {
            var confirmou = confirm('Deseja realmente sair do sistema?');
            if (confirmou) {
                window.location = 'src/logout.php';
            }
        }
        // A função 'excluir' do Javascript recebe um valor via parâmetro
        // Esse valor é o id do cliente, que se deseja excluir
        // Esse valor foi impresso via php, na chamada da função excluir() na tabela de clientes
        function ExcluirColaborador(IdColaborador) 
        {
            var confirmou = confirm('Tem certeza que quer excluir este cliente?');
            if (confirmou) 
            {
                window.location = 'src/colaboradores/excluir_colaborador.php?IdColaborador=' + idCliente;
            }
        }
        function ExcluirEquipamento(IdEquipamento)
        {
            var confirmou = confirm('Tem certeza que deseja realmente excluir este equipamento?');
            if(confirmou)
            {
                window.location = 'src/equipamentos/excluir_equipamento.php?IdEquipamento=' + IdEquipamento;
            }
        }
        function ExcluirUsuario(IdUsuario)
        {
            var confirmou = confirm('Tem certeza que deseja realmente excluir este usuário?');
            if(confirmou)
            {
                window.location = 'src/usuarios/excluir_usuario.php?IdUsuario=' + IdUsuario;        
            }
        }
    </script>
    <?php
        $acao = isset($_GET['acao']) ? $_GET['acao'] : '';
        switch ($acao) {
            case 'alterarusuario':
                $id_usuario = isset($_GET['IdUsuario']) ? $_GET['IdUsuario'] : '';
                if (!empty($id_usuario))
                {
                    try 
                    {
                        include_once 'src/class/BancoDeDados.php';
                        $banco = new BancoDeDados;
                        $sql = 'SELECT * FROM usuarios WHERE id_usuario = ?';
                        $parametros = [ $id_usuario ];
                        $dados = $banco->consultar($sql, $parametros);
                        echo 
                        "<script>
                            EditarUsuarioModal();
                            document.getElementById('txt_id').value   = '{$dados['id_usuario']}';
                            document.getElementById('txt_nome').value = '{$dados['nome_usuario']}';
                            document.getElementById('txt_senha').value = '{$dados['senha']}';
                            document.getElementById('chk_administrador').checked = " . ($dados['administrador'] == 1 ? 'true' : 'false') . ";
                        </script>";
                    } 
                    catch (PDOException $erro) 
                    {
                        echo $erro->getMessage();
                    }
                }
            break;
            case 'alterarcolaborador':
                $id_usuario = isset($_GET['IdColaborador']) ? $_GET['IdColaborador'] : '';
                if (!empty($id_usuario)) 
                {
                    try 
                    {
                    include_once 'src/class/BancoDeDados.php';
                    $banco = new BancoDeDados;
                    $sql = 'SELECT * FROM colaboradores WHERE id_colaborador = ?';
                    $parametros = [ $id_usuario ];
                    $dados = $banco->consultar($sql, $parametros);
                    echo 
                    "<script>
                        EditarColaboradorModal();
                        document.getElementById('txt_id').value   = '{$dados['id_colaborador']}';
                        document.getElementById('txt_nome').value = '{$dados['nome_colaborador']}';
                        document.getElementById('txt_data_nasc').value = '{$dados['data_nascimento']}';
                        document.getElementById('txt_cpf_cnpj').value = '{$dados['cpf_cnpj']}';
                        document.getElementById('txt_rg').value = '{$dados['rg']}';
                        document.getElementById('txt_data_nasc').value = '{$dados['data_nascimento']}';
                        document.getElementById('txt_telefone').value = '{$dados['telefone']}';
                    </script>";
                    } 
                    catch (PDOException $erro) 
                    {
                        echo $erro->getMessage();
                    }
                }
           break;
        }    
    ?>
</body>
</html>