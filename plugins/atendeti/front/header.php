<?php

include "../../../inc/includes.php";
Session::checkLoginUser();


include('../inc/tema.class.php');
include('../inc/dashboard.class.php');
include('../inc/ticketPendente.class.php');
include_once(GLPI_ROOT . "/inc/based_config.php");
include_once(GLPI_ROOT . "/inc/define.php");
include_once(GLPI_ROOT . "/inc/dbconnection.class.php");
/* FormSearch */
include('../inc/LastSearchForms.class.php');
include('../inc/search.class.php');

$search = new Search();
$profile_id = $_SESSION['glpiactiveprofile']['id'];



//IS Number ?
if (ctype_digit($assunto = filter_input(INPUT_GET, 'formSearch', FILTER_SANITIZE_STRING))) {
    $assunto = filter_input(INPUT_GET, 'formSearch', FILTER_SANITIZE_STRING);
    //location
    if ($assunto) {
        header('Location: ../../formcreator/front/issue.php?as_map=0&criteria%5B0%5D%5Bfield%5D=2&criteria%5B0%5D%5Bsearchtype%5D=contains&criteria%5B0%5D%5Bvalue%5D=' . $assunto . '&search=Pesquisar&itemtype=PluginFormcreatorIssue&start=0&_glpi_csrf_token=4fedcdd2a429d60f6f04b71b9576d2ed');
    } else {
        header('Location: ../index.php');
    }
} elseif (is_string($assunto = filter_input(INPUT_GET, 'formSearch', FILTER_SANITIZE_STRING))) {
    $IDForm = $search->RetornaIdForm($assunto);

    if ($IDForm) {

        header('Location: ../../formcreator/front/formdisplay.php?id=' . $IDForm . '&frontsearch=' . $_GET['frontsearch']);
    } else {

        header('Location: ../index.php?frontsearch=' . $_GET['frontsearch']);
    }
}/*END ELSEIF*/
                 

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
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.3/css/all.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.3/css/v4-shims.css">
    <!-- Material Kit CSS -->
    <link href="../assets/css/material-kit.min.css?v=2.0.5" rel="stylesheet" />

    <!-- BOOTSTRAP SELECT -->
    <link rel="stylesheet" href="../assets/css/bootstrap-select.min.css">
    <link rel="stylesheet" href="../assets/plugins/reateit/rateit.css">


    <!-- Custom CSS -->
    <link href="../assets/css/style2.css" rel="stylesheet" />
    <link rel='shortcut icon' type='images/x-icon' href='../assets/img/atende-logo.png'>
    <link rel="stylesheet" href="../assets/lib/css/jquery-ui.css">
    <link href="../assets/css/animate.css" rel="stylesheet">
</head>

<body data-spy="scroll" data-target=".navbar2" data-offset="500" id="nav">
    <nav class="navbar navbar-color-on-scroll navbar-transparent fixed-top navbar-expand-lg"  color-on-scroll="100"> 
        <div class="container-fluid" >
            <div class="navbar-translate" >
                <a class="navbar-brand" href="<?= $CFG_GLPI["root_doc"] ?>/plugins/atendeti/front/home.php#nav">
                    <i class="icone-logo">
                      <img src="../assets/img/logo.png" style="height: 35px; width: 100px" />
                    </i> <b><b>
                </a>

                <button class="navbar-toggler" type="button" data-toggle="collapse" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="navbar-toggler-icon"></span>
                    <span class="navbar-toggler-icon"></span>
                    <span class="navbar-toggler-icon"></span>
                </button>
            </div>
                
            <div class="collapse navbar-collapse ">
           
                                    
  
                  
                <ul class="navbar-nav">

                    <?php
                    
                    $alerts = Dashboard::getContentAlert();

                   // Dashboard::console_log($alerts);
                    if($alerts){
                                $totalAlert = count($alerts);
                                if ($alerts) { ?>                             
                                    <li class=" nav-contact dropdown">
                                       
                                        <a href="#" class="nav-link">
                                            <i class="material-icons">phone</i>
                                            <span>(61)3314-7505 / 1#61017505</span>
                                            <!-- <div class="ripple-container"></div> -->
                                        </a>
                                        <a href="mailto:atendeti@poupex.com.br" target="_blank" class="nav-link">
                                            <i class="material-icons">email</i>
                                            <span>atendeti@poupex.com.br</span>
                                            <!-- <div class="ripple-container"></div> -->
                                        </a>
                                    </li>
                                    <li class="dropdown nav-contact">
                                        <a href="#" class="dropdown-toggle nav-link" data-toggle="dropdown" aria-expanded="true">
                                            <i class="material-icons">notification_important</i>
                                            <span class="badge badge-pill badge-danger float-right"><?= $totalAlert ?></span>
                                            <div class="ripple-container"></div>
                                        </a>

                                        <div class="dropdown-menu" style="width:300px; background-color: #336099; padding:3px;">
                                            <?php
                                            for (
                                                $i = 0;
                                                $i < count($alerts);
                                                $i++
                                            ) {
                                                $type = $alerts[$i]['type'];
                                            ?>

                                                <?= "<div class='alert'>" ?>
                                                <div class="container">

                                                    <div class="alert-icon">
                                                        <i class="material-icons"><?= $alerts[$i]['icon'] ?></i>
                                                    </div>
                                                    <?= $alerts[$i]['name'] ?>
                                                    <p><?= Html::entity_decode_deep($alerts[$i]['message']); ?></p>

                                                </div>
                                        </div>

                                    <?php } ?>

                        </div>
                        </li>

                    <?php }
            } ?>

        <?php if (count($_SESSION["glpiprofiles"])>1): ?>
            <li class="nav-item dropdown">
                <?php
                if (Session::getCurrentInterface() == "helpdesk") {
                    $url = $CFG_GLPI["root_doc"] . "/front/helpdesk.public.php";
                } else {
                    $url = $CFG_GLPI["root_doc"] . "/front/central.php";
                }

                echo '<form id="profile-selector" method="post" action="' . $url .'" style="display: none">';
                echo Html::hidden('newprofile', [
                    'id' => 'newprofile',
                    'value' => '',
                ]);
                Html::closeForm();
                ?>
                <a class="nav-link dropdown-toggle" href="#" id="perfilDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="Trocar de Perfil">
                    <?php echo $_SESSION["glpiactiveprofile"]["name"] ?>
                </a>
                <div class="dropdown-menu" aria-labelledby="perfilDropdown">
                    <?php
                    foreach ($_SESSION["glpiprofiles"] as $key => $val) {
                        if ($key == $_SESSION["glpiactiveprofile"]["id"]) {
                            continue;
                        }
                        echo '<a class="dropdown-item" href="#" onclick="$(\'#newprofile\').val(\'' . $key .'\');$(\'#profile-selector\').submit();">' . $val['name'] . '</a>';
                    }
                    ?>
                </div>
            </li>
        <?php endif; ?>

        <li class="nav-item"> <a href="../../../front/logout.php?noAUTO=1" class="nav-link" title="Sair"> <i class="material-icons">exit_to_app</i>Sair</a></li>

        </ul>

        </div>
        <div class="collapse navbar-collapse ">
            <ul class="navbar-nav menu-mobile ">
                <li class="nav-item active">
                    <?php if (Session::getCurrentInterface() == "helpdesk") {
                        $url = $CFG_GLPI["root_doc"] . "/plugins/atendeti/front/home.php";
                    } else {
                        $url = $CFG_GLPI["root_doc"] . "/plugins/formcreator/front/formlist.php";
                    } ?>

                    <a class="nav-link active" href="<?= $url ?>">Serviços</a>
                </li>
                <li class="nav-item active">
                    <a class="nav-link active" href="#categorias">Mais Pedidos</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= $CFG_GLPI["root_doc"] ?>/plugins/formcreator/front/issue.php?reset=reset">Seus
                        Chamados</a>
                </li>
                <?php if (Session::getCurrentInterface() != "helpdesk") { ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= $CFG_GLPI["root_doc"] ?>/front/central.php">NextFlow</a>
                    </li>
                <?php } ?>
              

                <li class="nav-item">
                    <a href="<?= $CFG_GLPI["root_doc"] ?>/front/logout.php?noAUTO=1" class="nav-link" title="Sair">Sair</a>
                </li>

            </ul>
        </div>
        </div>
       
    </nav>

     
   
                    
       
    
    <div class="page-header header-filter navbar-color-on-scroll navbar-transparent " color-on-scroll="100" id="plugin_formcreator_searchBar" >
        
    <div class="container-fluid"> 
                <div class="row">   
                    <div class="col-md-8 ml-auto mr-auto">
                        <div class="brand text-center">
                            <p class="title-name"> Olá, <?php echo $_SESSION['glpirealname']; ?></p>
                            <h1></h1>
                            <div class="row justify-content-center">
                                <div class="col-12 col-md-10 col-lg-8 text-center">
                                <form class="card card-sm search" id="formSearchSubmit" name="formcreator_search" action="header.php" method="GET">
                                    <div class="card-body row no-gutters align-items-center">
                                        <div class="col-a">
                                            <i class="material-icons icon-search">search</i>
                                        </div>
                                        <!--end of col-->
                                        <div class="col">
                                            <span class="bmd-form-group bmd-form-group-lg">

                                                <input id="formSearch" class="form-control form-control-lg form-control-borderless" type="text" name="formSearch" required="" placeholder="Pesquise por serviços, sistemas, ID do chamado">
                                            </span>
                                            <span id="formcreator_search_input_bar"></span>
                                        </div>
                                        <!--end of col-->
                                        <div id="searchloader">
                                            <div class="loader"></div>
                                        </div>
                                    </div>
                                    <input type="hidden" id="frontsearch" name="frontsearch" value="">
                                    <input type="submit" id="searchForm" style="display: none;" name="searchForm">
                                    </form>
                                </div>
                                <!--end of col-->
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Acesso rapdido Ultimas pesquisas -->
                <?php

                // Historico
                if ($UserID = $_SESSION['glpiID']) {
                    $ids = (new LastSearchForms)->LastSearch($UserID);
                    $links = (new LastSearchForms)->ShowLinks($ids);
                }
                ?>
            </div>
    </div>
    
    

    <div class="main main-raised">
        <div class="container-fluid">        
            <!-- /*Menu*/ -->
            <!-- <div class="minhas-pesquisas text-center color-light">
          Minhas Pesquisas : <a href="#" class="minhas-pesquisas"><u>ERP</u></a>, <a href="#" class="minhas-pesquisas"><u>Acesso</u></a>
        </div>--> 
            <nav class="navbar2 " id="menu" data-offset-top="897">
                <ul class="nav justify-content-center">



                    <!-- EDIÇÃO DA HOME BOX - SOMENTE PARA O ADMIN -->
                    <li>
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
                        <a class="nav-link" href="<?= $CFG_GLPI["root_doc"] ?>/plugins/atendeti/front/tickets.php">Meus
                            chamados</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= $url ?>">SERVIÇOS</a>
                    </li>
                </ul>
            </nav>
            </div>
