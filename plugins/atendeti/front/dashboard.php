<?php

require_once('dashboard-header.php');

$profiles = Dashboard::getHomeUser();
$locations = Dashboard::getAllDiretorias();
$categories = Dashboard::getAllCategories();

if($profile_id == 4){
?>

    <div class="container-fluid">
    <div class="row">
        <div class="col-md-4">
            <div class="card ">
                <div class="card-header card-header-info card-header-icon">
                    <div class="card-icon">
                        <i class="material-icons">
                            queue</i>
                    </div>
                    <h4 class="card-title">ADM CATEGORIAS</h4>
                </div>
                <div class="card-body ">
                    <label for="profileget">SELECIONE O PERFIL:</label>
                    <select class="selectpicker col-md-12" multiple data-actions-box="true"
                            data-live-search="true" id="profileget">
                        <?php foreach ($profiles as $profile) { ?>
                            <option value="<?= $profile['id'] ?>"
                                    data-tokens="<?= $profile['name'] ?>"><?= $profile['name'] ?> </option>
                        <?php } ?>
                    </select>
                    <label for="profile-content">SELECIONE AS CATEGORIAS:</label>
                    <select class="selectpicker col-md-12" required data-live-search="true" multiple
                            id="profile-content" data-actions-box="true"></select>
                </div>
                <div class="card-footer">
                    <button type="button" value="add" class="update_home btn btn-sm btn-info">Adicionar</button>
                    <button type="button" value="remove" class="update_home btn btn-sm btn-danger">Remover</button>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card ">
                <div class="card-header card-header-info card-header-icon">
                    <div class="card-icon">
                        <i class="material-icons">subtitles</i>
                    </div>
                    <h4 class="card-title">ADM SUBCATEGORIA</h4>
                </div>
                <div class="card-body ">

<!--                    <label for="profileget"> Selecione uma Diretoria:</label><select class="selectpicker col-md-12" required  name="uta_id" id="getuta" data-live-search="true"></select>-->
                    <div>
                    <label for="uta_id"> SELECIONA A UTA:</label>
                        <select class="selectpicker col-md-12" multiple required name="sub_uta_id" id="uta_id"
                                data-live-search="true"  data-actions-box="true">                               
                            <?php foreach ($locations as $location) { ?>
                                <option value="<?= $location['id'] ?>"data-tokens="<?= $location['name'] ?>"><?= $location['name'] ?> </option>
                            <?php } ?>

                        </select>
                        <label for="selectsub"> SELECIONE A CATEGORIA:</label>
                        <select class="selectpicker col-md-12" required name="categorie_id" id="selectsub"
                                data-live-search="true">                                                          
                            <?php foreach ($categories as $categorie) { ?>
                                <option value="<?= $categorie['id'] ?>" data-tokens="<?= $categorie['name'] ?>"><?= $categorie['name'] ?> </option>
                            <?php } ?>
                        </select>
                    </div>

                    <div>
                    <label for="locationcontent">SELECIONE AS SUBCATEGORIAS:</label>
                        <select class="selectpicker col-md-12" required data-live-search="true" multiple
                                id="locationcontent" data-actions-box="true">
                                          
                        </select>
                    </div>
                </div>
                <div class="card-footer ">
                    <button type="submit" value="add" class="locationcategorie btn btn-fill btn-sm btn-info">Adicionar - SELECIONADOS</button>
                    <button type="submit" value="remove" class="locationcategorie btn btn-fill btn-sm btn-danger">Remover - SELECIONADOS</button>
                </div>
            </div>
        </div>
    </div>
    <?php }else{

    HTML::redirect($CFG_GLPI["root_doc"]."/plugins/atendeti/front/perfilCategoriaRelatorio.php");

} ?>
<?php include 'dashboard-footer.php'; ?>