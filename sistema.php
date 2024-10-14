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
    $dataPointsGraphic = array(
        array("label"=> 1997, "y"=> 254722.1),
        array("label"=> 1998, "y"=> 292175.1),
        array("label"=> 1999, "y"=> 369565),
        array("label"=> 2000, "y"=> 284918.9),
        array("label"=> 2001, "y"=> 325574.7),
        array("label"=> 2002, "y"=> 254689.8),
        array("label"=> 2003, "y"=> 303909),
        array("label"=> 2004, "y"=> 335092.9),
        array("label"=> 2005, "y"=> 408128),
        array("label"=> 2006, "y"=> 300992.2),
        array("label"=> 2007, "y"=> 401911.5),
        array("label"=> 2008, "y"=> 299009.2),
        array("label"=> 2009, "y"=> 319814.4),
        array("label"=> 2010, "y"=> 357303.9),
        array("label"=> 2011, "y"=> 353838.9),
        array("label"=> 2012, "y"=> 288386.5),
        array("label"=> 2013, "y"=> 485058.4),
        array("label"=> 2014, "y"=> 326794.4),
        array("label"=> 2015, "y"=> 483812.3),
        array("label"=> 2016, "y"=> 254484)
    );
    $dataPointsPizza = array(
        array("label"=> "Food + Drinks", "y"=> 590),
        array("label"=> "Activities and Entertainments", "y"=> 261),
        array("label"=> "Health and Fitness", "y"=> 158),
        array("label"=> "Shopping & Misc", "y"=> 72),
        array("label"=> "Transportation", "y"=> 191),
        array("label"=> "Rent", "y"=> 573),
        array("label"=> "Travel Insurance", "y"=> 126)
    );
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Controle de EPIs</title>
    <link href="assets/css/sistema.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.canvasjs.com/canvasjs.min.js"></script>
</head>
<body>
    <nav class="navbar navbar-light bg-light sticky-top shadow">
        <div class="container-fluid">
            <a class="navbar-brand bg-light" href="sistema.php">
                <img src="assets/img/trabalhador.png" alt="logo" width="30" height="30" class="d-inline-block align-text-top">
                <strong>Controle de EPIs</strong>
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
            <!-- Botão para menu offcanvas em dispositivos móveis -->
            <button class="navbar-toggler d-md-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasMenu">
                <span class="navbar-toggler-icon"></span>
            </button>
            <!-- Usuário logado à direita -->
            <div class="d-none d-md-flex align-items-center ms-auto">
                <p class="text-dark mb-0">
                    Usuário logado: 
                    <strong>
                    <?php
                        // Verifica se o nome do usuário está na sessão
                        if (isset($_SESSION['nome_usuario']) && !empty($_SESSION['nome_usuario'])) {
                            echo htmlspecialchars($_SESSION['nome_usuario']); // Proteção contra XSS
                        } else {
                            echo "Convidado";
                        }
                        ?>
                    </strong>
                </p>
            </div>
        </div>
    </nav>

    <!-- Offcanvas para dispositivos móveis -->
    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasMenu">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title">Menu</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
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
        </ul>
        <!-- Exibir o nome do usuário logado no offcanvas -->
        <div class="d-flex align-items-center justify-content-between mb-3 p-2 border-bottom">
            <p class="text-dark mb-0">
                Usuário logado:
                <strong>
                    <?php
                        // Verifica se o nome do usuário está na sessão
                        if (isset($_SESSION['nome_usuario']) && !empty($_SESSION['nome_usuario'])) {
                            echo htmlspecialchars($_SESSION['nome_usuario']); // Proteção contra XSS
                        } else {
                            echo "Convidado";
                        }
                    ?>
                </strong>
            </p>
        </div>
    </div>
    </div>
    <!-- Offcanvas para o menu lateral em dispositivos móveis -->
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
                <!-- Exibir o nome do usuário logado de forma destacada -->
                <div class="d-flex align-items-center justify-content-between mb-3 p-2 border-bottom">
                    <p class="text-dark mb-0">
                        Usuário logado:
                        <strong>
                            <?php
                                // Verifica se o nome do usuário está na sessão
                                if (isset($_SESSION['nome_usuario']) && !empty($_SESSION['nome_usuario'])) {
                                    echo htmlspecialchars($_SESSION['nome_usuario']); // Proteção contra XSS
                                } else {
                                    echo "Convidado";
                                }
                            ?>
                        </strong>
                    </p>
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
                        default:
                            echo 
                            '<div class="d-flex justify-content-center flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                                <h1 class="h2">Bem-vindo <strong>' . $_SESSION['nome_usuario'] . '</strong>!</h1>
                            </div>
                            <div id="chartContainerGraphic" style="height: 370px; width: 100%;"></div>
                            <div id="chartContainerGraphicPizza" style="height: 370px; width: 100%;"></div>';
                        break;
                    }
                ?>
            </main>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function sair() {
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
            var chartgraphic = new CanvasJS.Chart("chartContainerGraphic", {
                animationEnabled: true,
                title:{
                    text: "Salmon Production - 1997 to 2006"
                },
                axisX:{
                    crosshair: {
                        enabled: true,
                        snapToDataPoint: true
                    }
                },
                axisY:{
                    title: "in Metric Tons",
                    includeZero: true,
                    crosshair: {
                        enabled: true,
                        snapToDataPoint: true
                    }
                },
                toolTip:{
                    enabled: false
                },
                data: [{
                    type: "area",
                    dataPoints: <?php echo json_encode($dataPointsGraphic, JSON_NUMERIC_CHECK); ?>
                }]
            });
            chartgraphic.render();
            // Grafico Pizza
            var chartgraphicpizza = new CanvasJS.Chart("chartContainerGraphicPizza", {
                animationEnabled: true,
                exportEnabled: true,
                title:{
                    text: "Average Expense Per Day  in Thai Baht"
                },
                subtitles: [{
                    text: "Currency Used: Thai Baht (฿)"
                }],
                data: [{
                    type: "pie",
                    showInLegend: "true",
                    legendText: "{label}",
                    indexLabelFontSize: 16,
                    indexLabel: "{label} - #percent%",
                    yValueFormatString: "฿#,##0",
                    dataPoints: <?php echo json_encode($dataPointsPizza, JSON_NUMERIC_CHECK); ?>
                }]
            });
            chartgraphicpizza.render();
        }
    </script>
</body>
</html>