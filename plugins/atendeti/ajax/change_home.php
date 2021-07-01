<?php

include('../inc/dashboard.class.php');
Session::checkLoginUser();


/* Recupera os valores da requisição */
$profile_id = $_REQUEST['profile_id'];
$categorie_id = $_REQUEST['categoryID'];
$cat_id = $_REQUEST['cat_id'];
$uta_id = $_REQUEST['uta_id'];
$sub_id = $_REQUEST['sub_id'];
//$diretoria_id = $_REQUEST['diretoria_id'];
$status = $_POST['status'];
if ($profile_id && $status) {

    /*
    **  CASO SEJA ENVIADO OS 2 PARÂMETROS (ID DO PERFIL E ID DAS CATEGORIAS)
    **  EXECUTA O MÉTODO QUE ATUALIZA A HOME
    */
    if($categorie_id){
        $tema = Dashboard::callUpdateHomeCategorie($status,$_REQUEST['profile_id'], strval($_REQUEST['categoryID']));
    }else{
        $tema = Dashboard::callUpdateHomeCategorie($status,$_REQUEST['profile_id']);
    }

}else if($uta_id && $categorie_id){

    /*
    **  CASO SEJA ENVIADO OS 2 PARÂMETROS (UTA_ID E ID DAS CATEGORIAS)
    **  TRAZ AS SUB CATEGORIAS
    */
    $tema = Dashboard::callGetSubCategories($uta_id,$categorie_id);

}else if( $status && $uta_id && $sub_id && $cat_id){

    /*
    **  CASO SEJA ENVIADO OS 2 PARÂMETROS (UTA_ID E ID DA SUB_CATEGORIAS)
    **  TRAZ AS SUB CATEGORIAS
    */
    $sub_id = explode(',',$sub_id);
    $tema = Dashboard::callUpdateHomeSubCategorie($status,$uta_id,$sub_id,$cat_id);

}


return $tema;
