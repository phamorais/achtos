<?php

include('../inc/tema.class.php');
include('../inc/dashboard.class.php');
Session::checkLoginUser();
/* Recupera os valores da requisição */

if( isset($_REQUEST['categoryID'])){
    $categoriaId = $_REQUEST['categoryID'];
}

if( isset($_REQUEST['profile_id'])){
    $profile_id = $_REQUEST['profile_id'];
}


if (isset($_REQUEST['categoryID'])) {

    /*
    **  CASO SEJA ENVIADO (ID DAS CATEGORIA)
    **  EXECUTA O MÉTODO QUE ATUALIZA A LISTA DE FORMULÁRIOS
    */
    $tema = Tema::showCategorieForm($categoriaId);
    
}else if (isset($_REQUEST['profile_id'])) {

    /*
    **  CASO SEJA ENVIADO (ID DO PERFIL)
    **  EXECUTA O MÉTODO QUE RETORNA AS CATEGORIAS PARA A ATUALIZAÇÃO DA HOME
    */
   
    $tema = Dashboard::callGetCategorieHomeUser($profile_id);
}

echo $tema;

