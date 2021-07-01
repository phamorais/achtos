<?php
include 'header.php';
?>

<div id="categorias">

    <div class="titulo-mobile"><i class="material-icons">
            arrow_downward
        </i>Mais Pedidos
    </div>


    <div class="row ancora container-fluid" id="categoria">


        <?php


        $categorias = Tema::getCategories();

        //var_dump($categorias);

        if ($categorias) {
            foreach ($categorias as $categoria) {

                ?>

                <div class="col-sm-6  col-md-6 col-lg-4 col-xl-3 flip">
                    <a href="#formulario" class="categoria_nome" onClick="showFormsCategories(<?= $categoria['id'] ?>)">
                        <div class="card">
                            <div class="card-body">
                                <div class="face front">
                                    <div class="inner">
                                    <div class="card-direction">
                                            <div class="custom-card-space">
                                                <i class="icone icone-acesso"
                                                style=" background-image:url('../assets/img/<?= $categoria['id'] ?>.png')"></i>
                                            </div>
                                            <div class="card-content">
                                                <h5 class="card-title"><?= $categoria['name'] ?></h5>
                                                <p class="card-text">
                                                    <?= $categoria['comment'] ?></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>

                <?php
            }
        } else {

            echo '<h2>Seu perfil não possui categorias cadastradas!</h2>';
        }
        ?>


    </div>

</div>


<div class="section text-center" id="formulario">
    <h3 id="titleFormulario" class="title categoria_selecionada">Formulários</h3>
    <div class="container-fluid custom-padding">
        <div class="sistemas"></div>
        <div class="formularios">
            <div class="row" id="formularios">


                <div class="loader"></div>


            </div>
        </div>
    </div>
    <div id="form-pagination">

</div>

</div>


<?php

include 'footer.php';


?>
