<?php
include "../../../inc/includes.php";

Session::checkLoginUser();
?>
<html>

<head>
    <!-- <link rel="stylesheet" type="text/css" href="../css/app-assets/css/components.min.css"> -->
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <link href="https://fonts.googleapis.com/css?family=Rubik:300,400,500,600%7CIBM+Plex+Sans:300,400,500,600,700" rel="stylesheet">

    <!-- BEGIN: Vendor CSS-->

    <link rel="stylesheet" type="text/css" href="../css/app-assets/vendors/css/vendors.min.css">
    <link rel="stylesheet" type="text/css" href="../css/app-assets/vendors/css/jkanban/jkanban.min.css">
    <link rel="stylesheet" type="text/css" href="../css/app-assets/vendors/css/editors/quill/quill.snow.css">
    <link rel="stylesheet" type="text/css" href="../css/app-assets/vendors/css/pickers/pickadate/pickadate.css">
    <link rel="stylesheet" type="text/css" href="../css/app-assets/vendors/css/pickers/daterange/daterangepicker.css">
    <link rel="stylesheet" type="text/css" href="../css/app-assets/vendors/css/forms/select/select2.min.css">
    <link rel="stylesheet" type="text/css" href="../css/app-assets/vendors/css/extensions/sweetalert2.min.css">
    <!-- END: Vendor CSS-->

    <!-- BEGIN: Theme CSS-->

    <link rel="stylesheet" type="text/css" href="../css/app-assets/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="../css/app-assets/css/bootstrap-extended.css">
    <link rel="stylesheet" type="text/css" href="../css/app-assets/css/colors.css">
    <link rel="stylesheet" type="text/css" href="../css/app-assets/css/components.css">

    <link rel="stylesheet" type="text/css" href="../css/app-assets/css/themes/dark-layout.css">
    <link rel="stylesheet" type="text/css" href="../css/app-assets/css/themes/semi-dark-layout.css">
    <!-- END: Theme CSS-->

    <!-- BEGIN: Page CSS-->
    <link rel="stylesheet" type="text/css" href="../css/app-assets/css/core/menu/menu-types/vertical-menu.css">
    <link rel="stylesheet" type="text/css" href="../css/app-assets/css/pages/app-kanban.css">
    <!-- END: Page CSS-->

    <!-- BEGIN: Custom CSS-->
    <link rel="stylesheet" type="text/css" href="../css/app-assets/css/style.css">
    <!-- END: Custom CSS-->
</head>

<body>


    <div class="modal fade" id="detalheChamdo" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">

                    <div class="row">
                        <div class="col-sm-11">
                            <h5 class="card-title"><b> <span class="span" id="name" name="name"></b></span></h5>
                            <p>
                                <label>Data de Abertura:</label>
                                <span class="span" id="date" name="date"></span>
                            <p>
                                <span style="font-size: 1.7em;">Número do chamado:</span> <a id="linkChamado" name="likChamado" style="font-size: 1.7em;"><span class="span" id="id" name="id"></span></a>
                                <input id="id" name="id" class="form-control edit-kanban-item-title" placeholder="kanban Title" type="hidden" readonly>
                                </input>
                        </div>
                        <div class="col-sm-1">
                            <button type="button" class="close close-icon" data-dismiss="modal">
                                <i class="bx bx-x"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="modal-body">
                    <div class="card shadow-none quill-wrapper">
                        <div class="card-content">
                            <div class="card-body">
                                <div class="content-area-wrapper">
                                    <input id="date" name="date" class="form-control edit-kanban-item-title" placeholder="kanban Title" type="hidden" readonly>
                                    <div class="form-group">
                                        <div class="badge2 badge-pill badge-light-success mr-1" id="tag"><span class="span" id="name_tag" name="name_tag"></span></div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm">
                                            <div class="form-group">
                                                <label>Requerentes(s): </label>
                                                <span class=" badge badge-circle badge-light-primary" id="iniciaisProprietario" name="iniciaisProprietario"></span>
                                                <span class="span" id="proprietario" name="proprietario"></span>
                                            </div>
                                        </div>
                                        <div class="col-sm"> </div>
                                        <div class="col-sm">
                                            <div class="form-group">
                                                <label>Atendente(s): </label>
                                                <span class=" badge-atendente badge-circle badge-light-primary" id="iniciaisAtribuicao" name="iniciaisAtribuicao"></span>
                                                <span class="span" id="atribuicao" name="atribuicao"></span>
                                            </div>
                                        </div>
                                    </div>

                                    <ul class="nav nav-tabs" id="ticket-tab" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link active" id="dados-formulario-tab" data-toggle="tab" href="#dados-formulario" aria-controls="dados-formulario" role="tab" aria-selected="true">
                                                <i class="bx bx-cog align-middle"></i>
                                                <span class="align-middle">Descrição</span>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="dados-acompanhamento-tab" data-toggle="tab" href="#dados-acompanhamento" aria-controls="dados-acompanhamento" role="tab" aria-selected="false">
                                                <i class="bx bx-user align-middle"></i>
                                                <span class="align-middle">Service</span>
                                            </a>
                                        </li>
                                    </ul>
                                    <div class="tab-content">
                                        <div class="tab-pane active" id="dados-formulario" aria-labelledby="dados-formulario-tab" role="tabpanel">
                                            <span class="span" id="content" name="content" style="text-align: left;"></span>
                                        </div>
                                        <div class="tab-pane" id="dados-acompanhamento" aria-labelledby="dados-acompanhamento-tab" role="tabpanel">
                                            <div class="widget-chat widget-chat-demo d-block">
                                                <div class="card mb-0">
                                                    <div class="card-header collapsed">
                                                        <h4 class="card-title"><label><span class="span" id="titulo" name="titulo">Acompanhamento</span></label></h4>
                                                    </div>
                                                    <div class="card-body widget-chat-container widget-chat-demo-scroll ps ps--active-y">
                                                        <div class="chat-content" id="chat-acompanhamento">
                                                            <div class="badge badge-pill badge-light-secondary my-1">Hoje</div>
                                                        </div>
                                                    </div>
                                                    <div class="card-footer border-top p-1">
                                                        <div id="chat">
                                                            <form class="d-flex" onsubmit="widgetChatMessageDemo();" action="javascript:void(0);">
                                                                <div class="custom-control custom-switch custom-switch-danger mb-1">
                                                                    <input type="checkbox" class="custom-control-input" checked="" id="customSwitch1">
                                                                    <label class="custom-control-label mr-1" for="customSwitch1" title="Privado" id="checkPrivado">
                                                                        <span class="switch-icon-left"><i class="bx bxs-lock"></i></span>
                                                                        <span class="switch-icon-right"><i class="bx bxs-lock"></i></span>
                                                                    </label>
                                                                </div>
                                                                <input type="text" class="form-control chat-message-demo mr-75" style=" border-radius: 27px;" placeholder="Digite...">
                                                                <button type="submit" class="btn btn-primary glow px-1" style="border-radius: 13px;"><i class="bx bx-paper-plane"></i></button>
                                                            </form>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="">

                                </div>

                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
    </div>
    <!-- BEGIN: Content-->
    <div class="search_page">
        <div id=scroll class="scroll">
            <div class="content-overlay"></div>
            <div class="content-wrapper">
                <div class="content-header row">
                </div>
                <div class="content-body">
                    <!-- Basic Kanban App -->
                    <div class="kanban-overlay"></div>
                    <section id="kanban-wrapper" id="quadro">

                        <div class="row">



                            <div class="card-body" id="loading">

                                <div class="row">
                                    <div class="col-sm-2">
                                        <div class="spinner-grow text-info" role="status">
                                            <span class="sr-only">Loading...</span>
                                        </div>
                                    </div>

                                    <div class="col-sm-2">
                                        <div class="spinner-grow text-primary" role="status">
                                            <span class="sr-only">Loading...</span>
                                        </div>
                                    </div>


                                    <div class="col-sm-2">
                                        <div class="spinner-grow text-primary" role="status">
                                            <span class="sr-only">Loading...</span>
                                        </div>
                                    </div>


                                    <div class="col-sm-2">
                                        <div class="spinner-grow text-warning" role="status">
                                            <span class="sr-only">Loading...</span>
                                        </div>
                                    </div>

                                    <div class="col-sm-2">
                                        <div class="spinner-grow text-success" role="status">
                                            <span class="sr-only">Loading...</span>
                                        </div>
                                    </div>

                                    <div class="col-sm-2">
                                        <div class="spinner-grow text-dark" role="status">
                                            <span class="sr-only">Loading...</span>
                                        </div>
                                    </div>
                                </div>
                            </div>






                        </div>

                        <!-- User new mail right area -->


                        <div class="kanban-sidebar ">

                            <!--/ User Chat profile right area -->
                    </section>
                    <!--/ Sample Project kanban -->

                </div>
            </div>
        </div>
    </div>
    <!-- END: Content-->



    <!-- FERRAMENTA DE CUSTOMIZACAO MENU -->
    <div class="customizer d-none d-md-block"><a class="customizer-close" href="#"><i class="bx bx-x"></i></a><a class="customizer-toggle" href="#" style="top: 104px;"><i class="bx bx-cog bx white"></i></a>
        <div class="customizer-content p-2 ps">
            <h5 class="text-uppercase mb-0">Configurações</h5>

            <hr>
            <!-- Theme options starts -->

            <!-- Exibir Coluna 1 -->

            <!-- Theme options starts -->


            <div class="collapsible collapse-icon accordion-icon-rotate">

                <div class="card collapse-header">
                    <div id="headingCollapse6" class="card-header" data-toggle="collapse" role="button" data-target="#collapse6" aria-expanded="false" aria-controls="collapse6">
                        <span class="collapse-title">
                            <i class="bx bx-show align-middle"></i>
                            <span class="align-middle">Exibir Colunas</span>
                        </span>
                    </div>
                    <div id="collapse6" role="tabpanel" aria-labelledby="headingCollapse2" class="collapse">
                        <div class="card-content">
                            <div class="card-body">
                                <div class="card-shadow d-flex justify-content-between align-items-center py-25">
                                    <div class="hide-scroll-title">
                                        <h5 class="pt-25">Novo</h5>
                                    </div>
                                    <div class="card-shadow-switch">
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox" class="custom-control-input" checked="" id="check_column_new">
                                            <label class="custom-control-label" for="card-shadow-switch" id="label_check_column_new"></label>
                                        </div>
                                    </div>
                                </div>
                                <!-- Exibir Coluna 2 -->
                                <div class="card-shadow d-flex justify-content-between align-items-center py-25">
                                    <div class="hide-scroll-title">
                                        <h5 class="pt-25">Processando(Atribuido)</h5>
                                    </div>
                                    <div class="card-shadow-switch">
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox" class="custom-control-input" checked="" id="check_column_processing_assigned">
                                            <label class="custom-control-label" for="card-shadow-switch" id="label_check_column_processing_assigned"></label>
                                        </div>
                                    </div>
                                </div>
                                <!-- Exibir Coluna 3 -->
                                <div class="card-shadow d-flex justify-content-between align-items-center py-25">
                                    <div class="hide-scroll-title">
                                        <h5 class="pt-25">Processando(Planejado)</h5>
                                    </div>
                                    <div class="card-shadow-switch">
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox" class="custom-control-input" checked="" id="check_column_processing_planned">
                                            <label class="custom-control-label" for="card-shadow-switch" id="label_check_column_processing_planned"></label>
                                        </div>
                                    </div>
                                </div>
                                <!-- Exibir Coluna 4 -->
                                <div class="card-shadow d-flex justify-content-between align-items-center py-25">
                                    <div class="hide-scroll-title">
                                        <h5 class="pt-25">Pendente</h5>
                                    </div>
                                    <div class="card-shadow-switch">
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox" class="custom-control-input" checked="" id="check_column_pending">
                                            <label class="custom-control-label" for="card-shadow-switch" id="label_check_column_pending"></label>
                                        </div>
                                    </div>
                                </div>
                                <!-- Exibir Coluna 5 -->
                                <div class="card-shadow d-flex justify-content-between align-items-center py-25">
                                    <div class="hide-scroll-title">
                                        <h5 class="pt-25">Solucionado</h5>
                                    </div>
                                    <div class="card-shadow-switch">
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox" class="custom-control-input" checked="" id="check_column_solved">
                                            <label class="custom-control-label" for="card-shadow-switch" id="label_check_column_solved"></label>
                                        </div>
                                    </div>
                                </div>
                                <!-- Exibir Coluna 6 -->
                                <div class="card-shadow d-flex justify-content-between align-items-center py-25">
                                    <div class="hide-scroll-title">
                                        <h5 class="pt-25">Fechado</h5>
                                    </div>
                                    <div class="card-shadow-switch">
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox" class="custom-control-input" checked="" id="check_column_closed">
                                            <label class="custom-control-label" for="card-shadow-switch" id="label_check_column_closed"></label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card collapse-header">
                    <div id="headingCollapse7" class="card-header" data-toggle="collapse" role="button" data-target="#collapse7" aria-expanded="false" aria-controls="collapse7">
                        <span class="collapse-title">
                            <i class="bx bx-filter-alt align-middle"></i>
                            <span class="align-middle">Filtros</span>
                        </span>
                    </div>
                    <div id="collapse7" role="tabpanel" aria-labelledby="headingCollapse7" class="collapse">
                        <div class="card-content">
                            <div class="card-body">
                                <h6>Filtrar:</h6>
                                <div class="row" style="    margin-top: -31px;
                  position: absolute;
                  right: -115px;
                  width: 186px;">
                                    <div class="custom-control custom-switch custom-switch-primary mb-1">
                                        <input type="checkbox" class="custom-control-input" checked="false" id="checkFiltrar">
                                        <label class="custom-control-label mr-1" for="checkFiltrar" title="Filtrar" id="labelCheckFiltrar">
                                            <span class="switch-icon-left"><i class="bx bx-filter-alt"></i></span>
                                            <span class="switch-icon-right"><i class="bx bx-filter-alt"></i></span>
                                        </label>
                                    </div>
                                </div>
                                <hr>
                                <ul class="list-unstyled mb-0">
                                    <li class="d-inline-block mr-2 mb-1">
                                        <fieldset>
                                            <div class="checkbox checkbox-primary">
                                                <input type="checkbox" id="colorCheckbox1" checked="">
                                                <label for="colorCheckbox1">Abertos por mim</label>
                                            </div>
                                        </fieldset>
                                    </li>
                                    <li class="d-inline-block mr-2 mb-1">
                                        <fieldset>
                                            <div class="checkbox checkbox-secondary">
                                                <input type="checkbox" id="colorCheckbox2" checked="">
                                                <label for="colorCheckbox2">Atribuídos para mim</label>
                                            </div>
                                        </fieldset>
                                    </li>
                                    <li class="d-inline-block mr-2 mb-1">
                                        <fieldset>
                                            <div class="checkbox checkbox-success">
                                                <input type="checkbox" id="colorCheckbox3" checked="">
                                                <label for="colorCheckbox3">Atribuídos para a minha equipe</label>
                                            </div>
                                        </fieldset>
                                    </li>

                                </ul>

                                <ul class="list-unstyled mb-0">
                                    <li>
                                        <fieldset class="form-group position-relative has-icon-left">
                                            <label>Data de Abertura Entre:</label>
                                            <input type="text" class="form-control dropup" placeholder="Selecione o Período" style="    font-size: 14px;    width: 293px;" id="dataAbertura">
                                            <div class="form-control-position">
                                                <i class='bx bx-calendar-check'></i>
                                            </div>

                                        </fieldset>

                                    </li>

                                </ul>
                                <label>Localizações:</label>
                                <div class="form-group">
                                    <select class="select2 form-control" multiple="multiple" id="locations" style="display: none;"></select>
                                </div>
                                <label>Tipo:</label>
                                <div class="form-group">
                                    <select class="select2 form-control" multiple="multiple" id="tipo" style="display: none;"></select>
                                </div>
                                <label>Categoria:</label>
                                <div class="form-group">
                                    <select class="select2 form-control" multiple="multiple" id="categories" style="display: none;"></select>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

            </div>


            <!-- Hide Scroll To Top Ends-->
            <div class="ps__rail-x" style="left: 0px; bottom: 0px;">
                <div class="ps__thumb-x" tabindex="0" style="left: 0px; width: 0px;"></div>
            </div>
            <div class="ps__rail-y" style="top: 0px; right: 0px;">
                <div class="ps__thumb-y" tabindex="0" style="top: 0px; height: 0px;"></div>
            </div>
        </div>
    </div>
    <div class="sidenav-overlay"></div>
    <div class="drag-target"></div>
    <input type="hidden" name="resultado" id="resultado">
    <!-- BEGIN: Vendor JS-->
    <script src="../css/app-assets/vendors/js/vendors.min.js"></script>
    <script src="../css/app-assets/fonts/LivIconsEvo/js/LivIconsEvo.tools.js"></script>
    <script src="../css/app-assets/fonts/LivIconsEvo/js/LivIconsEvo.defaults.js"></script>
    <script src="../css/app-assets/fonts/LivIconsEvo/js/LivIconsEvo.min.js"></script>
    <!-- BEGIN Vendor JS-->

    <!-- BEGIN: Page Vendor JS-->
    <script src="../css/app-assets/vendors/js/forms/select/select2.full.min.js"></script>
    <script src="../css/app-assets/vendors/js/jkanban/jkanban.min.js"></script>
    <script src="../css/app-assets/vendors/js/editors/quill/quill.min.js"></script>
    <script src="../css/app-assets/vendors/js/pickers/pickadate/picker.js"></script>
    <script src="../css/app-assets/vendors/js/pickers/pickadate/picker.date.js"></script>
    <script src="../css/app-assets/vendors/js/pickers/pickadate/picker.time.js"></script>
    <script src="../css/app-assets/vendors/js/pickers/pickadate/legacy.js"></script>
    <script src="../css/app-assets/vendors/js/pickers/daterange/moment.min.js"></script>
    <script src="../css/app-assets/vendors/js/pickers/daterange/daterangepicker.js"></script>
    <script src="../css/app-assets/vendors/js/ui/jquery.sticky.js"></script>
    <!-- END: Page Vendor JS-->
    <!-- BEGIN: Theme JS-->
    <script src="../css/app-assets/js/scripts/configs/vertical-menu-light.js"></script>
    <script src="../css/app-assets/js/core/app-menu.js"></script>
    <script src="../css/app-assets/js/core/app.js"></script>
    <script src="../css/app-assets/js/scripts/components.js"></script>
    <script src="../css/app-assets/js/scripts/customizer.js"></script>
    <script src="../css/app-assets/js/scripts/footer.js"></script>

    <!-- BEGIN: Page Vendor JS-->
    <!-- END: Page Vendor JS-->

    <!-- END: Theme JS-->

    <!-- BEGIN: Page JS-->
    <script src="../css/app-assets/js/scripts/navs/navs.js"></script>
    <script src="../css/app-assets/js/scripts/tooltip/tooltip.js"></script>
    <script src="../css/app-assets/vendors/js/extensions/sweetalert2.all.min.js"></script>
    <script src="../css/app-assets/js/scripts/forms/select/form-select2.js"></script>
    <!-- END: Page JS-->
    <script src="../css/app-assets/js/scripts/pages/app-kanban.js"></script>

    <!-- BEGIN: Page Vendor JS-->
    <!-- END: Page Vendor JS-->

    <!-- BEGIN: Page JS-->
    <!-- END: Page JS-->

</body>

</html>