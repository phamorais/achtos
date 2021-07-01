<?php

include "../../../inc/includes.php";
Session::checkLoginUser();
include('../inc/ticket-board.class.php');

include('../inc/tema.class.php');
include('../inc/ticketPendente.class.php');

include('../inc/LastSearchForms.class.php');
include('../inc/search.class.php');

$search = new Search();
$profile_id = $_SESSION['glpiactiveprofile']['id'];




?>

<!doctype html>
<html lang="pt-br">

<head>
    <title>AtendeTI</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" name="viewport" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <!--     Fonts and icons     -->
    <link rel="stylesheet" type="text/css" href="../assets/lib/css/material-icon.css" />
    <link rel="stylesheet" type="text/css" href="../assets/lib/css/font-awesome.min.css">
    <!-- Material Kit CSS -->
    <link href="../assets/css/material-kit.min.css?v=2.0.5" rel="stylesheet" />

    <!-- BOOTSTRAP SELECT -->
    <link rel="stylesheet" href="../assets/css/bootstrap-select.min.css" type="text/css">
    <link rel="stylesheet" href="../assets/plugins/reateit/rateit.css" type="text/css">

    <!-- Custom CSS -->
    <link href="../assets/css/style2.css" rel="stylesheet" type="text/css" />
    <link rel='shortcut icon' type='images/x-icon' href='../assets/img/atende-logo.png'>
    <link rel="stylesheet" href="../assets/lib/css/jquery-ui.css" type="text/css">

    <link href="../assets/css/tickets_board.css" rel="stylesheet" type="text/css">
    <link href="../assets/css/animate.css" rel="stylesheet" type="text/css">

</head>

<body data-spy="scroll" data-target=".navbar2" data-offset="500" id="nav">

    <nav class="navbar navbar-expand-lg bg-default">
        <div class="container">
            <div class="navbar-translate">
                <a class="navbar-brand" href="<?= $CFG_GLPI["root_doc"] ?>/plugins/atendeti/front/home.php#nav">
                    <i class="icone-logo" style="background-size: 101px!important;background-position: 0px 0px!important;"></i>
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="navbar-toggler-icon"></span>
                    <span class="navbar-toggler-icon"></span>
                    <span class="navbar-toggler-icon"></span>
                </button>
            </div>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav">

                    <!-- EDIÇÃO DA HOME BOX - SOMENTE PARA O ADMIN -->
                    <li class="nav-item">
                        <!-- Botão que aciona o modal -->

                        <?php

                        if (Session::getCurrentInterface() != "helpdesk") {
                            echo ' <a class="nav-link" href="dashboard.php">Administração</a>';
                        }
                        ?>
                    </li>
                    <!-- APRESENTAR PARA TODOS MENOS USUÁRIO COMUM-->
                    <?php if (Session::getCurrentInterface() != "helpdesk") { ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= $CFG_GLPI["root_doc"] ?>/front/central.php">Antigo AtendeTI</a>
                        </li>
                    <?php } ?>
                    <!-- END - APRESENTAR PARA TODOS MENOS USUÁRIO COMUM-->

                    <!-- END - EDIÇÃO DA HOME BOX - SOMENTE PARA O ADMIN -->
                    <li class="nav-item">
                        <a href="<?= $CFG_GLPI["root_doc"] ?>/plugins/atendeti/front/aprovacao.php" class="nav-link" title="Notificações">Aprovações
                            <?php if ($checkTicketsPendente = TicketPendente::checkTicketPendente()) {
                                echo '<span class="badge badge-pill badge-danger float-right" style="margin-left: 3px;">' . $checkTicketsPendente . '</span>';
                            } ?>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" id="meusChamados" href="<?= $CFG_GLPI["root_doc"] ?>/plugins/atendeti/front/tickets.php">Meus
                            chamados</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="<?= $CFG_GLPI["root_doc"] ?>/plugins/atendeti/front/home.php">Serviços</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="<?= $CFG_GLPI["root_doc"] ?>/plugins/formcreator/front/issue.php?reset=reset">Versão padrão</a>
                    </li>

                </ul>

                </ul>


                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a href="../../../front/logout.php?noAUTO=1" class="nav-link" title="Sair">
                            <i class="material-icons">exit_to_app</i>Sair</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        <form class="card card-sm search" id="formSearchSubmit" name="formcreator_search" action="tickets.php" method="GET" style="margin:0px auto!important; padding:6px!important;width:  56%;">
            <div class="card-body row no-gutters align-items-center">
                <div class="col-1">
                    <i class="material-icons icon-search">search</i>
                </div>
                <!--end of col-->
                <div class="col-10">
                    <span class="bmd-form-group bmd-form-group-lg" id="spanInput">
                        <input id="formSearch" style="display:none;" class="form-control form-control-lg form-control-borderless" type="text" name="formSearch" required="" placeholder="Pesquise por serviços, sistemas, ID do chamado">
                    </span>
                    <span id="formcreator_search_input_bar"></span>

                </div>
                <!--end of col-->
                <div class="col-1" id="cleanFilter">
                    <a href="<?= $CFG_GLPI["root_doc"] ?>/plugins/atendeti/front/tickets.php">
                        <img src="<?= $CFG_GLPI["root_doc"] ?>/plugins/atendeti/assets/img/undo-button.svg" width="32px" height="32px">
                    </a>
                </div>
                <div id="searchloader">
                    <div class="loader"></div>
                </div>

            </div>
            <input type="hidden" id="frontsearch" name="frontsearch" value="">
            <input type="submit" id="searchForm" style="display: none;" name="searchForm">


        </form>
        <div id="filterBox">
            <input type="checkbox" class="form-check-input" id="checkFilter">
            <small id="filterHelper" class="form-text text-muted">Pesquisar formulários</small>

        </div>

    </div>