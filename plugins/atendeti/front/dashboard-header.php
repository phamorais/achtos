<?php
require_once("../../../inc/includes.php");
include_once('../inc/tema.class.php');
include_once('../inc/dashboard.class.php');
Session::checkLoginUser();
$profile_id = $_SESSION['glpiactiveprofile']['id'];
if (Session::getCurrentInterface() == "helpdesk") {
    HTML::redirect($CFG_GLPI["root_doc"] . "/plugins/atendeti");
}

?>
<!doctype html>
<html lang="pt-BR">

<head>
    <title> Configuração - AtendeTI </title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" name="viewport" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <!--     Fonts and icons     -->
    <link rel="stylesheet" type="text/css" href="../assets/lib/css/material-icon.css" />
    <link rel="stylesheet" href="../assets/lib/css/font-awesome.min.css" type="text/css">
    <!-- Material Kit CSS -->
    <link href="../assets/css/material-dashboard.css?v=2.1.1" rel="stylesheet" />
    <link rel="stylesheet" href="../assets/css/bootstrap-select.min.css">
    <link rel="stylesheet" href="../assets/css/data-table.css">
    <link rel="stylesheet" href="../assets/css/data-table-button.css">

    <link rel='shortcut icon' type='images/x-icon' href='../assets/img/atende-logo.png'>

</head>

<body>
    <div class="wrapper ">
        <div class="sidebar" data-color="purple" data-background-color="white">

            <div class="logo text-center">
                <a href="home.php"> <img src="../assets/img/logo-atendeti-dashboard.png" alt="Logo AtendeTI" class="img-container" width="150px"></a>
            </div>
            <div class="sidebar-wrapper">
                <ul class="nav">
                    <?php if ($profile_id == 4) { ?>
                        <li class="nav-item ">
                            <a class="nav-link" href="dashboard.php">
                                <i class="material-icons">dashboard</i>
                                <p>ADM Formulários</p>
                            </a>
                        </li>
                        <li class="nav-item ">
                            <a class="nav-link" href="rules.php">
                                <i class="material-icons">gavel</i>
                                <p>Regras e Funcionalidades</p>
                            </a>
                        </li>
                    <?php } ?>
                    <li class="nav-item active ">
                        <a class="nav-link collapsed" data-toggle="collapse" href="#formsExamples" aria-expanded="false">
                            <i class="material-icons">graphic_eq</i>
                            <p> Relatórios
                                <b class="caret"></b>
                            </p>
                        </a>
                        <div class="collapse" id="formsExamples" style="">
                            <ul class="nav">
                                <li class="nav-item active ">
                                    <a class="nav-link" href="perfilCategoriaRelatorio.php">
                                        <span class="sidebar-mini"> <i class="material-icons">supervised_user_circle</i> </span>
                                        <span class="sidebar-normal"> Perfil por Categoria </span>
                                    </a>
                                </li>
                                <li class="nav-item active ">
                                    <a class="nav-link" href="utaCategoriaRelatorio.php">
                                        <span class="sidebar-mini"> <i class="material-icons">recent_actors</i> </span>
                                        <span class="sidebar-normal"> UTA's por Categoria </span>
                                    </a>
                                </li>

                            </ul>
                        </div>
                    </li>
                    <!-- your sidebar here -->
                </ul>

            </div>
        </div>
        <div class="main-panel">
            <!-- Navbar -->
            <nav class="navbar navbar-expand-lg navbar-transparent navbar-absolute fixed-top ">
                <div class="container-fluid">
                    <div class="navbar-wrapper">
                        <a class="navbar-brand" href="#pablo">Painel Admin</a>
                    </div>
                    <button class="navbar-toggler" type="button" data-toggle="collapse" aria-controls="navigation-index" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="navbar-toggler-icon icon-bar"></span>
                        <span class="navbar-toggler-icon icon-bar"></span>
                        <span class="navbar-toggler-icon icon-bar"></span>
                    </button>
                    <div class="collapse navbar-collapse justify-content-end">
                        <ul class="navbar-nav">
                            <li class="nav-item">
                                <a href="<?= $CFG_GLPI["root_doc"] ?>/front/logout.php?noAUTO=1" class="nav-link" title="Sair">
                                    <i class="material-icons">exit_to_app</i> Sair
                                </a>
                            </li>
                            <!-- your navbar here -->
                        </ul>
                    </div>
                </div>
            </nav>
            <!-- End Navbar -->
            <div class="content">