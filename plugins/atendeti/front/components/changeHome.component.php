<!-- EDIÇÃO DA HOME BOX - SOMENTE PARA O ADMIN -->

<?php

$profiles = Tema::getHomeUser();
$locations = Tema::getAllLocations();
$categories = Tema::getAllCategories();


?>



<!-- Modal -->

<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Configuração da Home</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true" data-dismiss="modal" aria-label="Close">×</span>
                </button>



            </div>



            <div class="modal-body">

                <div class="col-12">
                    <ul class="nav nav-pills nav-pills-icons" role="tablist">
                        <!-- color-classes: "nav-pills-primary", "nav-pills-info", "nav-pills-success", "nav-pills-warning","nav-pills-danger" -->
                        <li class="nav-item">
                            <a class="nav-link active show" href="#dashboard-1" role="tab" data-toggle="tab" aria-selected="true">
                                Categorias
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#schedule-1" role="tab" data-toggle="tab" aria-selected="false">
                                Sub-categorias
                            </a>
                        </li>

                    </ul>
                    <div class="tab-content tab-space">
                        <div class="tab-pane active show" id="dashboard-1">
                            <select class="selectpicker col-md-12" multiple data-actions-box="true" data-live-search="true" id="profileget">


                                <?php foreach ($profiles as $profile) { ?>

                                    <option value="<?= $profile['id'] ?>" data-tokens="<?= $profile['name'] ?>"><?= $profile['name'] ?> </option>

                                <?php } ?>


                            </select>


                            <div>
                                <select class="selectpicker col-md-12" required data-live-search="true" multiple id="profile-content" data-actions-box="true">

                                </select>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                <button type="button" id="update_home" class="btn btn-primary">Salvar alterações</button>
                            </div>


                        </div>
                        <div class="tab-pane" id="schedule-1">
                        
                        <select class="selectpicker col-md-12" required multiple name="uta_id" id="getuta" data-live-search="true">
                                <option> Selecione uma UTA </option>

                                <?php foreach ($locations as $location) { ?>

                                    <option value="<?= $location['id'] ?>"  data-tokens="<?= $location['name'] ?>"><?= $location['name'] ?> </option>

                                <?php } ?>


                            </select>


                            <div>
                                <select class="selectpicker col-md-12" required name="categorie_id" id="selectsub" data-live-search="true">
                                <option> Selecione uma Categoria </option>
                                  <?php foreach ($categories as $categorie) { ?>

                                      <option value="<?= $categorie['id'] ?>" data-tokens="<?= $categorie['name'] ?>"><?= $categorie['name'] ?> </option>

                                  <?php } ?>

                                </select>
                            </div>

                            <div>
                                <select class="selectpicker col-md-12" required data-live-search="true" multiple id="locationcontent" data-actions-box="true" >

                                </select>
                            </div>


                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                <button type="button" id="locationcategorie" class="btn btn-primary">Salvar alterações</button>
                            </div>


                        </div>


                    </div>
                </div>




            </div>

        </div>
    </div>
</div>
<!-- END - EDIÇÃO DA HOME BOX - SOMENTE PARA O ADMIN -->
