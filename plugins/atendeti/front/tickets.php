<?php
include 'header_simple.php';

$filter = null;
if(isset($_GET['filter'])){
    $filter = $_GET['filter'];
}
$tickets = 0;

if(isset($_GET['tickets'])){
    $tickets=  $_GET['tickets'];
}

if (isset($_GET['tickets']) && $_GET['tickets'] != '') {

    if (is_numeric($_GET['tickets'])) {
        $tickets = TicketBoard::getTickets($filter, $_GET['tickets'], 1);
    } else {
        $tickets = TicketBoard::getTickets($filter, $_GET['tickets'], 2);
    }
} else {
    
     $tickets = TicketBoard::getTickets($filter, $tickets);

}



$urlEspera = '/plugins/formcreator/front/issue.php?as_map=0&criteria%5B0%5D%5Blink%5D=AND&criteria%5B0%5D%5Bfield%5D=4&criteria%5B0%5D%5Bsearchtype%5D=equals&criteria%5B0%5D%5Bvalue%5D=process&criteria%5B1%5D%5Blink%5D=AND&criteria%5B1%5D%5Bfield%5D=8&criteria%5B1%5D%5Bsearchtype%5D=equals&criteria%5B1%5D%5Bvalue%5D='.$_SESSION['glpiID'].'&search=Pesquisar&itemtype=PluginFormcreatorIssue&start=0&_glpi_csrf_token='.Session::getNewCSRFToken();
$urlAtendimento = '/plugins/formcreator/front/issue.php?as_map=0&criteria%5B0%5D%5Blink%5D=AND&criteria%5B0%5D%5Bfield%5D=4&criteria%5B0%5D%5Bsearchtype%5D=equals&criteria%5B0%5D%5Bvalue%5D=process&criteria%5B1%5D%5Blink%5D=AND&criteria%5B1%5D%5Bfield%5D=8&criteria%5B1%5D%5Bsearchtype%5D=equals&criteria%5B1%5D%5Bvalue%5D='.$_SESSION['glpiID'].'&search=Pesquisar&itemtype=PluginFormcreatorIssue&start=0&_glpi_csrf_token='.Session::getNewCSRFToken();
$urlPendente = '/plugins/formcreator/front/issue.php?criteria%5B0%5D%5Bfield%5D=4&criteria%5B0%5D%5Bsearchtype%5D=equals&criteria%5B0%5D%5Bvalue%5D=4&criteria%5B1%5D%5Bfield%5D=8&criteria%5B1%5D%5Bsearchtype%5D=equals&criteria%5B1%5D%5Bvalue%5D='.$_SESSION['glpiID'].'&reset=reset';
$urlAtendido = '/plugins/formcreator/front/issue.php?as_map=0&criteria%5B0%5D%5Blink%5D=AND&criteria%5B0%5D%5Bfield%5D=4&criteria%5B0%5D%5Bsearchtype%5D=equals&criteria%5B0%5D%5Bvalue%5D=5&criteria%5B1%5D%5Blink%5D=AND&criteria%5B1%5D%5Bfield%5D=8&criteria%5B1%5D%5Bsearchtype%5D=equals&criteria%5B1%5D%5Bvalue%5D='.$_SESSION['glpiID'].'&search=Pesquisar&itemtype=PluginFormcreatorIssue&start=0&_glpi_csrf_token='.Session::getNewCSRFToken();
$urlFechado = '/plugins/formcreator/front/issue.php?as_map=0&criteria%5B0%5D%5Blink%5D=AND&criteria%5B0%5D%5Bfield%5D=4&criteria%5B0%5D%5Bsearchtype%5D=equals&criteria%5B0%5D%5Bvalue%5D=6&criteria%5B1%5D%5Blink%5D=AND&criteria%5B1%5D%5Bfield%5D=8&criteria%5B1%5D%5Bsearchtype%5D=equals&criteria%5B1%5D%5Bvalue%5D='.$_SESSION['glpiID'].'&search=Pesquisar&itemtype=PluginFormcreatorIssue&start=0&_glpi_csrf_token='.Session::getNewCSRFToken();


?>

<div class="container-fluid mt-4" style="margin-bottom:150px; padding-right:25px">
    <div class="row coluna-margem">

        <ul class="navbar-nav">
            <li class="nav-item dropdown" id="nav-filter">
                <a aria-expanded="false" aria-haspopup="true" class="" data-toggle="dropdown" href="#pablo" id="navbarDropdownMenuLink">
                    <i class="fa fa-filter" aria-hidden="true"></i> <span class="notification" id="text-filter"> Todos </span>
                    <p><span class="d-lg-none d-md-block">Some Actions</span></p>
                </a>
                <div aria-labelledby="navbarDropdownMenuLink" class="dropdown-menu dropdown-menu-right">
                    <a class="dropdown-item" href="?filter=1">Melhorar</a>
                    <a class="dropdown-item" href="?filter=2">Manter</a>
                    <a class="dropdown-item" href="<?= $CFG_GLPI["root_doc"] ?>/plugins/atendeti/front/tickets.php">Todos</a>
            </li>
        </ul>

    </div>
    <div class="row coluna-margem" >
        <div class="col-sm coluna mr-2" id="aguardando">
            <div class="row ticket-title text-center header-content">
                <div class="kamban-header-title">
                    <span>Em espera</span>
                </div>
                <div class="kamban-header-icon">
                    <ul class="pagination pagination-primary">
                        <li class="active page-item">
                            <a class="page-link " id="aguardando-number" href="<?=$urlEspera?>"  data-toggle="popover" data-trigger="hover" data-content='Visualizar todos' data-html="true">
                                <?= $tickets['aguardando']['total'] ?>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="tickets">
                <?php foreach ($tickets['aguardando'] as $tick) {
                    if (isset($tick['id'])) {
                        ?>
                        <div class="card">
                            <div class="card-body">
                                <a href="<?= $CFG_GLPI["root_doc"] ?>/front/ticket.form.php?id=<?= $tick['id'] ?>">
                                    <h5 class="card-title"><?= $tick['name'] ?></h5>

                                    <p class="card-text"><a tabindex="0" href="<?= $CFG_GLPI["root_doc"] ?>/front/ticket.form.php?id=<?= $tick['id'] ?>" role="button" data-toggle="popover" data-trigger="hover" title="<?= $tick['id'] ?>" data-content='' data-html="true">n° <?= $tick['id'] ?></a></p>

                                </a>
                            </div>
                        </div>
                <?php
                    }
                }
                ?>
            </div>
        </div>
        <div class="col-sm coluna mr-2" id="atendendo">
            <div class="row ticket-title text-center header-content">
                <div class="kamban-header-title">
                    <span>Em atendimento</span>
                </div>
                <div class="kamban-header-icon">
                    <ul class="pagination pagination-primary">
                        <li class="active page-item">
                            <a class="page-link " id="atendendo-number" href="<?=$urlAtendimento?>"  data-toggle="popover" data-trigger="hover" data-content='Visualizar todos' data-html="true">
                                <?= $tickets['atendendo']['total'] ?>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="tickets">
                <?php
                foreach ($tickets['atendendo'] as $tick) {
                    if (isset($tick['id'])) {
                        ?>

                        <div class="card">
                            <div class="card-body">
                                <a href="<?= $CFG_GLPI["root_doc"] ?>/front/ticket.form.php?id=<?= $tick['id'] ?>">
                                    <h5 class="card-title"><?= $tick['name'] ?></h5>

                                    <p class="card-text"><a tabindex="0" href="<?= $CFG_GLPI["root_doc"] ?>/front/ticket.form.php?id=<?= $tick['id'] ?>" role="button" data-toggle="popover" data-trigger="hover" title="<?= $tick['id'] ?>" data-content='' data-html="true"> n° <?= $tick['id'] ?></a></p>

                                </a>

                            </div>
                        </div>
                <?php
                    }
                }
                ?>
            </div>
        </div>
        <div class="col-sm coluna mr-2" id="pendente">
            <div class="row ticket-title text-center header-content">
                <div class="kamban-header-title">
                    <span>Pendente</span>
                </div>
                <div class="kamban-header-icon">
                    <ul class="pagination pagination-primary" >
                        <li class="active page-item">
                            <a class="page-link " id="pendente-number" href="<?=$urlPendente?>"  data-toggle="popover" data-trigger="hover" data-content='Visualizar todos' data-html="true">
                                <?= $tickets['pendente']['total'] ?>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="tickets">
                <?php
                foreach ($tickets['pendente'] as $tick) {
                    if (isset($tick['id'])) {
                        ?>
                        <div class="card">
                            <div class="card-body">
                                <a href="<?= $CFG_GLPI["root_doc"] ?>/front/ticket.form.php?id=<?= $tick['id'] ?>">
                                    <h5 class="card-title"><?= $tick['name'] ?></h5>

                                    <p class="card-text"> <a tabindex="0" href="<?= $CFG_GLPI["root_doc"] ?>/front/ticket.form.php?id=<?= $tick['id'] ?>" role="button" data-toggle="popover" data-trigger="hover" title="<?= $tick['id'] ?>" data-content='' data-html="true">n° <?= $tick['id'] ?> </a> </p>

                                </a>
                            </div>
                        </div>
                <?php
                    }
                }
                ?>
            </div>
        </div>
        <div class="col-sm coluna mr-2" id="validacao">
            <div class="row ticket-title text-center header-content">
                <div class="kamban-header-title">
                    <span>Atendido</span>
                </div>
                <div class="kamban-header-icon">
                    <ul class="pagination pagination-primary">
                        <li class="active page-item">
                            <a class="page-link " id="validacao-number" href="<?=$urlAtendido?>"  data-toggle="popover" data-trigger="hover" data-content='Visualizar todos' data-html="true">
                                <?= $tickets['validacao']['total'] ?>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="tickets">
                <?php
                foreach ($tickets['validacao'] as $tick) {
                    if (isset($tick['id'])) {
                        ?>
                        <div class="card">
                            <div class="card-body">
                                <a href="<?= $CFG_GLPI["root_doc"] ?>/front/ticket.form.php?id=<?= $tick['id'] ?>">
                                    <h5 class="card-title"><?= $tick['name'] ?></h5>
                                    <p class="card-text"> <a tabindex="0" href="<?= $CFG_GLPI["root_doc"] ?>/front/ticket.form.php?id=<?= $tick['id'] ?>" role="button" data-toggle="popover" data-trigger="hover" title="<?= $tick['id'] ?>" data-content='' data-html="true">n° <?= $tick['id'] ?></a></p>
                                </a>
                            </div>
                            <div class="card-bottom card-bottom-validacao">
                                <a id="<?= $tick['id'] ?>" class="btn btn-sm negar" data-toggle="modal" data-target="#modalApprove">
                                    Avaliar solução
                                </a>
                            </div>
                        </div>
                <?php
                    }
                }
                ?>
            </div>
        </div>
        <?php    
        $fechado= [];
        $totalFechado=0;
        if(isset($tickets['fechado'])){
            $fechado = $tickets['fechado'];
            $totalFechado = $tickets['fechado']['total'];
        }
        
        ?>
        <div class="col-sm coluna mr-2" id="fechado">
            <div class="row ticket-title text-center header-content">
                <div class="kamban-header-title">
                    <span>Fechados</span>
                </div>
                <div class="kamban-header-icon">
                    <ul class="pagination pagination-primary">
                        <li class="active page-item">
                            <a class="page-link " id="fechado-number" href="<?=$urlFechado?>"  data-toggle="popover" data-trigger="hover" data-content='Visualizar maior que 90 dias' data-html="true">
                                <?= $totalFechado ?>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="tickets">
                <?php
          

                foreach ($fechado as $tick) {
                    if (isset($tick['id'])) {
                        ?>
                        <div class="card">
                            <div class="card-body">
                                <a href="<?= $CFG_GLPI["root_doc"] ?>/front/ticket.form.php?id=<?= $tick['id'] ?>">
                                    <h5 class="card-title">                                    
                                        <?= $tick['name'] ?>                                    
                                    </h5>
                                    <p class="card-text"> <a tabindex="0" href="<?= $CFG_GLPI["root_doc"] ?>/front/ticket.form.php?id=<?= $tick['id'] ?>" role="button" data-toggle="popover" data-trigger="hover" title="<?= $tick['id'] ?>" data-content='' data-html="true">n° <?= $tick['id'] ?></a></p>
                                </a>
                            </div>                           
                        </div>
                <?php
                    }
                }
                ?>
            </div>
        </div>

    </div>
</div>



<div class="modal fade" id="modalApprove" tabindex="-1" role="dialog" aria-labelledby="modalApprove" aria-hidden="true" style="z-index:999999999;">
    <div class="modal-dialog" role="document">

        <div class="modal-content" style="border: 2px solid #cecece;">
            <button type="button" id="buttonCloseModal" class="close" style="margin-left: 470px;" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>

            <div class="modal-header" style="margin:0px auto!important;padding: 0px!important;">
                <h5 class="modal-title text-center" id="exampleModalLabel"><b>Solução </b>
                </h5>
            </div>

            <div class="modal-body" id="modalMessageBody">
                <form name="form" method="post" action="<?= $CFG_GLPI["root_doc"] ?>/plugins/atendeti/inc/ticket-solution.php">
                    <span style="margin-right:20px">Avalie o seu atendimento:</span>
                    <select id="backing3b" name="satisfaction">
                        <option value="1" selected="selected"></option>
                        <option value="2"></option>
                        <option value="3"></option>
                        <option value="4"></option>
                        <option value="5"></option>
                    </select>
                    <div class="rateit" data-rateit-resetable="false" data-rateit-backingfld="#backing3b"></div>
                    <br>
                    <label for="content">Comentário *</label>
                    <textarea name="content" id="content" style="width:100%" placeholder="" oninput="checarComentario()"></textarea>
                    <small id="emailHelp" class="form-text text-muted"> * Justificar em caso de recusa</small>

                    <input type="hidden" id="input_id_ticket" name="tickets_id" value="">
                    <input type="hidden" name="requesttypes_id" value="0">
                    <input type="hidden" name="_glpi_csrf_token" value="<?= Session::getNewCSRFToken() ?>">
            </div>
            <div class="modal-footer">
                <input type="submit" name="add_close" value="Aceitar a solução" class="btn btn-sm btn-success" style="background: #80CEAD; color: #3A5D4E;">
                <input type="submit" name="add_reopen" id="botao_close" value="Recusar a solução" class="btn btn-sm btn-warning" disabled='disabled' style="background: #ffb800; color: #957218;">
                </form>
            </div>
        </div>
    </div>
</div>




<?php

include 'footer.php';



?>


<script type="text/javascript">

   function checarComentario (){  if($('#content').val().length > 0){ $('#botao_close').prop("disabled", false); }else{$('#botao_close').prop("disabled", true);} }  

    $('a.negar').click(function() {
        $('#input_id_ticket').val($(this).attr('id'));
    });

    $('#meusChamados').addClass('active');



    $('#formSearch').css('display', 'none');
    $('#formSearch').attr('disabled', 'true');
    $('#spanInput').append('<input class="form-control form-control-lg" id="inputFilterBoard" type="text" name="tickets" placeholder="Pesquise pelo ID do chamado ou titulo">');
    $('#formSearchSubmit').prop('action', 'tickets.php');

    $('#checkFilter').on('click', function() {
        if ($(this).is(':checked')) {
            //$('#filterHelper').text('Pesquisar formulários');
            $('#formSearch').css('display', 'block');
            $('#inputFilterBoard').remove();
            $('#formSearch').removeAttr('disabled');
            $('#formSearchSubmit').prop('action', 'header.php');
        } else {
            //$('#filterHelper').text('Pesquisar pelo Board');
            $('#formSearch').css('display', 'none');
            $('#formSearch').attr('disabled', 'true');
            $('#spanInput').append('<input class="form-control form-control-lg" id="inputFilterBoard" type="text" name="tickets" placeholder="Pesquise pelo ID do chamado ou titulo">');
            $('#formSearchSubmit').prop('action', 'tickets.php');
        }
    });


    $('.popover-dismiss').popover({
        trigger: 'focus'
    })
    var filter = window.location.search;
    var haveFilter = <?= count($_GET)  ?>;

    if (!haveFilter) {
        $('#cleanFilter').css('display', 'none');
    }

    if (filter == '?filter=1') {
        $('#text-filter').text('Melhorar');
    } else if (filter == '?filter=2') {
        $('#text-filter').text('Manter');
    } else {

        $('#text-filter').text('Filtrar');
    }
</script>
