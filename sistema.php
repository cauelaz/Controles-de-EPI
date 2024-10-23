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
    if(!isset($_COOKIE['id_user'])) //Caso não existir o cookie com o id do usuário, deleta a sessão
    {
        session_destroy();
    }
    include_once 'src/class/BancodeDados.php';
    $banco = new BancodeDados;
    $sql = 'SELECT COALESCE(count(id_emprestimo), "0") AS qtd_emprestimos
         , colaboradores.nome_colaborador AS nome_colaborador
         , COALESCE(departamentos.nome_departamento, "Nenhum empréstimo feito") AS nome_departamento
         FROM equipamentos_emprestimo
         JOIN emprestimos ON emprestimos.id_emprestimo = equipamentos_emprestimo.emprestimo
         JOIN colaboradores ON colaboradores.id_colaborador = emprestimos.colaborador
         LEFT JOIN departamentos ON departamentos.id_departamento = colaboradores.id_departamento';
    $equipamentos = $banco->Consultar($sql, [], true);
    $dataPointsPizza_totalemprestimospordepartamento = [];
    foreach ($equipamentos as $equipamento) 
    {
        $dataPointsPizza_totalemprestimospordepartamento[] = 
        [
            "label" => $equipamento['nome_departamento'], // Nome do departamento como label
            "y"     => $equipamento['qtd_emprestimos']       // Quantidade de empréstimos como valor
        ];
    }
    $sql = 'SELECT COUNT(id_colaborador) AS qtd_colaboradores
                 , COALESCE(departamentos.nome_departamento, "Sem departamento") AS nome_departamento
                 FROM colaboradores
                 LEFT JOIN departamentos ON departamentos.id_departamento = colaboradores.id_departamento
                 WHERE colaboradores.ativo = 1
                 GROUP BY nome_departamento';
    $colaboradores = $banco->Consultar($sql, [], true);
    $dataPointsPizza_totalcolaboradorespordepartamento = [];
    foreach($colaboradores as $colaborador)
    {
        $dataPointsPizza_totalcolaboradorespordepartamento[] = 
        [
            "label" => $colaborador['nome_departamento'], // Nome do departamento como label
            "y"     => $colaborador['qtd_colaboradores']      // Quantidade de colaboradores como valor
        ];
    }
    $sql = 'SELECT equipamentos.descricao
                 , (equipamentos.qtd_estoque - COALESCE(SUM(CASE WHEN emprestimos.ativo = 1 THEN 1 ELSE 0 END), 0)) AS qtd_disponivel
                 FROM equipamentos
                 LEFT JOIN equipamentos_emprestimo ON equipamentos.id_equipamento = equipamentos_emprestimo.equipamento
                 LEFT JOIN emprestimos ON equipamentos_emprestimo.emprestimo = emprestimos.id_emprestimo AND emprestimos.ativo = 1
                 WHERE equipamentos.ativo = 1
                 GROUP BY equipamentos.id_equipamento, equipamentos.descricao, equipamentos.qtd_estoque';
    $estoquepordepartamento = $banco->Consultar($sql, [], true);
    $dataPointsPizza_estoquepordepartamento = [];
    foreach($estoquepordepartamento as $estoque)
    {
        $dataPointsPizza_estoquepordepartamento[] = 
        [
            "label" => $estoque['descricao'], // Nome do departamento como label
            "y"     => $estoque['qtd_disponivel']      // Quantidade de empréstimos como valor
        ];
    }
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EPI's Control</title>
    <link href="assets/css/sistema.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.canvasjs.com/canvasjs.min.js"></script>
</head>
<body style="overflow-x: hidden">
    <div>
        <nav class="navbar navbar-light bg-light sticky-top shadow">
            <div class="container-fluid">
                <a class="navbar navbar-expand-lg navbar-light bg-light" href="sistema.php">
                    <img src="assets/img/trabalhador.png" alt="logo" width="40" height="40" class="d-inline-block align-text-top">
                </a>
                <!-- Menu para dispositivos maiores -->
                <ul class="d-none d-md-flex flex-wrap align-items-center nav mx-auto">
                    <li class="nav-item">
                        <a class="nav-link d-flex ms-5" href="sistema.php?tela=equipamentos">
                            <i class="bi bi-hammer"></i> Equipamentos (EPIs)
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link d-flex ms-5" href="sistema.php?tela=colaboradores">
                            <i class="bi bi-people"></i> Colaboradores
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link d-flex ms-5" href="sistema.php?tela=departamentos">
                            <i class="bi bi-building"></i> Departamentos
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link d-flex ms-5" href="sistema.php?tela=usuarios">
                            <i class="bi bi-person"></i> Usuários
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link d-flex ms-5" href="sistema.php?tela=emprestimos">
                            <i class="bi bi-arrow-left-right"></i> Empréstimos
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link d-flex ms-5" href="#" onclick="sair()">
                            <i class="bi bi-box-arrow-right"></i> Sair
                        </a>
                    </li>
                </ul>
                <!-- Botão para menu offcanvas em celulares -->
                <button class="navbar-toggler d-md-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasMenu">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="d-none d-md-flex align-items-center ms-auto flex-column">
                    <p class="text-dark mb-0">
                        Usuário logado: 
                        <strong>
                            <?php
                                // Verifica se o nome do usuário está na sessão
                                if (isset($_SESSION['nome_usuario']) && !empty($_SESSION['nome_usuario'])) 
                                {
                                    echo htmlspecialchars($_SESSION['nome_usuario']);
                                } 
                                else 
                                {
                                    echo "Convidado";
                                }
                            ?>  
                        </strong>
                    </p>
                    <div id="temp_session" class="text-dark"></div> <!-- Mostra o tempo restante da sessão -->
                </div>
            </div>
        </nav>
        </div>
        <!-- Offcanvas para o menu lateral em celulares -->
        <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasMenu">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title">Menu</h5>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
                <!-- Menu de navegação no offcanvas -->
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link" href="sistema.php?tela=equipamentos">
                            <i class="bi bi-hammer"></i> Equipamentos (EPIs)
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="sistema.php?tela=colaboradores">
                            <i class="bi bi-people"></i> Colaboradores
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="sistema.php?tela=usuarios">
                            <i class="bi bi-person"></i> Usuários
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="sistema.php?tela=emprestimos">
                            <i class="bi bi-arrow-left-right"></i> Empréstimos
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" onclick="sair()">
                            <i class="bi bi-box-arrow-right"></i> Sair
                        </a>
                    </li>
                <!-- Exibir o nome do usuário logado e o tempo de sessão -->
                <div class="d-flex flex-column align-items-start justify-content-between mb-3 p-2 border-bottom">
                    <p class="text-dark mb-0">
                        Usuário logado:
                        <strong>
                            <?php
                                if (isset($_SESSION['nome_usuario']) && !empty($_SESSION['nome_usuario'])) {
                                    echo htmlspecialchars($_SESSION['nome_usuario']);
                                } else {
                                    echo "Convidado";
                                }
                            ?>
                        </strong>
                    </p>
                    <div id="temp_session_mobile" class="text-dark mt-1"></div>
                </div>
                </ul>
            </div>
        </div>
        <div class="container-fluid">
            <div class="row">
                <main>
                    <!-- Script para importar as telas do sistema -->
                    <?php
                        $tela = isset($_GET['tela']) ? $_GET['tela'] : '';
                        switch ($tela) 
                        {
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
                            case 'departamentos':
                                include 'telas/departamentos.php';
                            break;
                            default:
                                echo 
                                '<div class="d-flex justify-content-center flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                                    <h1 class="h2">Bem-vindo <strong>' . $_SESSION['nome_usuario'] . '</strong>!</h1>
                                </div>
                                    <div class="col-md-6 align-items-center" id="chartContainerGraphicDISPONIVELporDEPARTAMENTO" style="height: 370px;"></div>
                                <div class="row">
                                    <div class="col-md-6 align-items-center" id="chartContainerGraphicPizzaEMPRESTIMOS" style="height: 370px;"></div>
                                    <div class="col-md-6 align-items-center" id="chartContainerGraphicPizzaCOLABORADORES" style="height: 370px;"></div>
                                </div>';
                            break;
                        }
                    ?>
                </main>
            </div>
        </div>
    </body>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
            function sair() 
            {
                var confirmou = confirm('Deseja realmente sair do sistema?');
                if (confirmou) 
                {
                    window.location = 'src/logout.php';
                }
            }
            // Função para criar gráfico no Dashboard
            window.onload = function Graphic() 
            {
                // Grafico com crosshair
                var chartgraphicestoquepordepartamento = new CanvasJS.Chart("chartContainerGraphicDISPONIVELporDEPARTAMENTO", {
                    animationEnabled: true,
                    theme: "light2",
                    title:{
                        text: "Estoque Disponível por Departamento"
                    },
                    axisY: {
                        title: "Qtd. Disponível"
                    },
                    data: [{
                        type: "column",
                        yValueFormatString: "#",
                        dataPoints: <?php echo json_encode($dataPointsPizza_estoquepordepartamento, JSON_NUMERIC_CHECK); ?>
                    }]
                });
                chartgraphicestoquepordepartamento.render();
                // Grafico Pizza Empréstimos por Departamento
                var chartgraphicpizzaEMPRESTIMOS = new CanvasJS.Chart("chartContainerGraphicPizzaEMPRESTIMOS", {
                animationEnabled: true,
                exportEnabled: false,
                title:{
                    text: "Empréstimos por Departamento"
                },
                data: [{
                    type: "pie",
                    showInLegend: true,
                    legendText: "{label}",
                    indexLabelFontSize: 16,
                    indexLabel: "{label} - {y}",
                    yValueFormatString: "#,##0",
                    dataPoints: <?php echo json_encode($dataPointsPizza_totalemprestimospordepartamento, JSON_NUMERIC_CHECK); ?>
                }]
                });
                chartgraphicpizzaEMPRESTIMOS.render();
                //Gráfio Pizza Colaboradores por Departamento
                var chartgraphicpizzaCOLABORADORES = new CanvasJS.Chart("chartContainerGraphicPizzaCOLABORADORES", {
                animationEnabled: true,
                exportEnabled: false,
                title:{
                    text: "Colaboradores por Departamento"
                },
                data: [{
                    type: "pie",
                    showInLegend: true,
                    legendText: "{label}",
                    indexLabelFontSize: 16,
                    indexLabel: "{label} - {y}",
                    yValueFormatString: "#,##0",
                    dataPoints: <?php echo json_encode($dataPointsPizza_totalcolaboradorespordepartamento, JSON_NUMERIC_CHECK); ?>
                }]
                });
                chartgraphicpizzaCOLABORADORES.render();
            }
            function getCookie(name) 
            {
                let value = `; ${document.cookie}`;
                let parts = value.split(`; ${name}=`); 
                if (parts.length === 2) return parts.pop().split(';').shift(); 
            }

            // Pega o timestamp do login
            let loginTime = getCookie('login_time'); 
            if (loginTime) 
            {
                // Converte o valor do cookie de string para número e de segundos para milissegundos
                loginTime = parseInt(loginTime) * 1000;
                // Define o tempo máximo de sessão (30 minutos em milissegundos)
                let sessionDuration = 30 * 60 * 1000;
                function updateTimer() 
                {
                    let now = new Date().getTime();
                    let elapsedTime = now - loginTime; // Tempo decorrido desde o login
                    let timeRemaining = sessionDuration - elapsedTime; // Tempo restante

                    if (timeRemaining > 0) 
                    {
                        let minutes = Math.floor((timeRemaining % (1000 * 60 * 60)) / (1000 * 60));
                        let seconds = Math.floor((timeRemaining % (1000 * 60)) / 1000);
                        let sessionText = `Tempo restante da sessão: ${minutes}m ${seconds}s`;
                        // Atualiza tanto no layout principal quanto no offcanvas
                        document.getElementById("temp_session").innerHTML = sessionText;
                        document.getElementById("temp_session_mobile").innerHTML = sessionText;
                    } 
                    else 
                    {
                        clearInterval(sessionInterval); // Limpa o setInterval para evitar o loop
                        document.getElementById("temp_session").innerHTML = "Sessão expirada.";
                        document.getElementById("temp_session_mobile").innerHTML = "Sessão expirada.";
                        alert("Sua sessão expirou. Por favor, efetue o login novamente.");
                        window.location = 'index.php';
                    }
                }
                // Atualiza o timer a cada segundo
                let sessionInterval = setInterval(updateTimer, 100); 
            } 
            else 
            {
                document.getElementById("temp_session").innerHTML = "Nenhum cookie de login encontrado.";
                document.getElementById("temp_session_mobile").innerHTML = "Nenhum cookie de login encontrado.";
                window.location = 'index.php';
            }
        </script>
</html>