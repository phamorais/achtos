<?php
require_once('../../../inc/includes.php');


if (!defined('GLPI_ROOT')) {
    die("Sorry. You can't access this file directly");
}

Session::checkLoginUser();
include_once(GLPI_ROOT . "/inc/based_config.php");
include_once(GLPI_ROOT . "/inc/define.php");
include_once(GLPI_ROOT . "/inc/dbconnection.class.php");
include_once('validar.class.php');


class Tema
{
    /*
    *   MÉTODO QUE VERIFICA SE A CATEGORIA TEM SUB-CATEGORIA
    *   PARÂMETRO -> CATEGORIA_ID
    *   RETORNO
    *   1 - SEM SUBCATEGORIA = 0
    *   2 - COM SUBCATEGORIA = > 0
    */
    public function selectFields($categoryId)
    {

        // VARIÁVEL QUE EXECUTA A QUERY
        global $DB;

        //TABELA DAS CATEGORIAS
        $cat_table = 'glpi_plugin_formcreator_categories';

        //QUERY QUE VERIFICA SEM TEM ALGUMA SUBCATEGORIA
        $query_forms = "SELECT count(cat.id) as id FROM $cat_table AS cat
        WHERE cat.plugin_formcreator_categories_id = $categoryId";

        $result_cats = $DB->query($query_forms);

        while ($cat = $DB->fetchArray($result_cats)) {

            $id = $cat['id'];
        }

        //RETORNA O TOTAL DE SUBCATEGORIA
        return $id;
    }


    /*
    *   MÉTODO RESPONSÁVEL POR RETORNAR AS CATEGORIAS DAQUELE USUÁRIO
    *   SEM PARÂMETRO
    *   RETORNO
    *       ARRAY COM AS CATEGORIAS DAQUELE PERFIL
    */
    public static function getCategories()
    {

        // VARIÁVEL QUE EXECUTA A QUERY
        global $DB;

        //TABELAS
        $cat_table = 'glpi_plugin_formcreator_categories';
        $profile_cat_table = 'glpi_plugin_front_categories_home';

        //PEGO O PERFIL ATIVO
        $profile_id = $_SESSION['glpiactiveprofile']['id'];

        // SELECT RETORNANDO O ID DAS CATEGORIA DE ACORODO COM O PERFIL
        $query_profile_cats = "SELECT $profile_cat_table.categorie_id FROM $profile_cat_table
                                   WHERE $profile_cat_table.profile_id = $profile_id
                                   AND $profile_cat_table.level =  1";

        $result_cats = $DB->query($query_profile_cats);

        //CASO NÃO TENHA NENHUMA CATEGORIA ATRIBUÍDA A ESTE PERFIL
        //RETORNO FALSE
        if (!mysqli_num_rows($result_cats)) {

            return false;
        }

        //CASO TENHA CATEGORIAS ATRIBUO TODOS OS ID EM UM ARRAY
        while ($profile_cat = $DB->fetchArray($result_cats)) {

            $id[] = $profile_cat['categorie_id'];
        }

        // CONVERTER OS ID PARA UMA STRING
        $id = implode(',', $id);

        //QUERY COM AS CATEGORIAS FILTRANDO PELOS ID
        $query_cats = "SELECT * FROM $cat_table AS cat
                      WHERE cat.id IN ($id)
                      AND cat.level = 1
                      ORDER BY cat.name ASC";


        $result_cats = $DB->query($query_cats);
        $categorias =[];
        while ($form = $DB->fetchArray($result_cats)) {
            $categorias[] = $form;
        }

        //RETORNA A VARIÁVEL COM OS DADOS
        return $categorias;
    }


    /*
    *   MÉTODO QUE PEGA TODOS OS PROFILES DO USUÁRIO LOGADO
    *   SEM PARÂMETRO
    *   RETORNO
    *       ARRAY COM OS PERFIS DAQUELE USUÁRIO
    */
    public function getUserProfiles()
    {

        //ADICIONO A UM ARRAY OS PROFILES COM INDEX ['id'] ['nome']
        foreach ($_SESSION["glpiprofiles"] as $key => $val) {

            $values[] = ['id' => $key, 'nome' => $val['name']];
        }

        //RETORNA O ARRAY COM O ID E O NOME DOS PROFILES
        // EXEMPLO __ ARRAY( [ 0 ] => ( [ 'id' => 0, 'nome' => 'USUÁRIO' ] ) )
        return $values;
    }

    /*
    *   MÉTODO QUE RENDERIZA OS FORMULÁRIOS
    *   COM PARÂMETRO
    *       CATEGORIA_ID
    *   RETORNO
    *       HTML COM OS FORMS DA CATEGORIA ENVIADA COMO PARÂMETRO
    */
    public static function showCategorieForm($categoryId)
    {
        // VARIÁVEL QUE EXECUTA A QUERY
        global $DB;

        $idUser = $_SESSION['glpiID'];

        // $profiles = (new self())->changeProfile();

        // TABELAS UTILIZADAS NA QUERY
        $cat_table = 'glpi_plugin_formcreator_categories';
        $form_table = 'glpi_plugin_formcreator_forms';
        $table_fp = 'glpi_plugin_formcreator_forms_profiles';

        // VERIFICA SE EXISTE SUBCATEGORIA
        $id = (new self())->selectFields($categoryId);

        //SHOW FORMS QUE NÃO TEM SUBCATEGORIA
        if ($id == 0) {

            //MÉTODO QUE RETORNA ID DOS FORMS QUE O USUÁRIO TEM ACESSO
            $forms_id = (new Validar)->validarForms($categoryId);

            // CONVERTER OS ID PARA UMA STRING
            $id_form_validated = implode(',', $forms_id);

            $query_forms = "SELECT
                            form.id, form.name, form.description, form.icon, categ.name as categoria
                            FROM $form_table AS form
                            INNER JOIN $cat_table as categ
                                on categ.id = form.plugin_formcreator_categories_id
                            LEFT JOIN  (SELECT * FROM glpi_plugin_front_laststickets WHERE glpi_plugin_front_laststickets.user_id = $idUser) glpi_plugin_front_laststickets  ON glpi_plugin_front_laststickets.plugin_formcreator_forms_id = form.id                                 
                            WHERE form.plugin_formcreator_categories_id = $categoryId
                            AND form.id IN ( $id_form_validated )
                            ORDER BY glpi_plugin_front_laststickets.count DESC";


            $result_forms = $DB->query($query_forms);

            // FOREACH DOS FORMULÁRIOS
            // RENDERIZA A VIEW DO BOX COM FORMS SELECIONADOS
            while ($form = $DB->fetchArray($result_forms)) {
                $forms[] = $form;
            }

            $json = $forms;

            // SHOW FORMS QUE TEM SUBCATEGORIA
        } else {


            // TABELA QUE CONTÉM OS ID DAS SUBCATEGORIAS QUE TEM QUE APARECER
            $profile_cat_table = 'glpi_plugin_front_categories_home';
            $profile_id_atual = $_SESSION['glpiactiveprofile']['id'];

            $id_locations_cat = (new self())->filtrarCategoriaLocation($categoryId);

            if (!$id_locations_cat) {

                return false;
            }

            // QUERY QUE TRAZ OS ID DE ACORDO COM A CATEGORIA SELECIONADA 
            $query_home_forms = "SELECT  cat.id from  $cat_table AS cat                                
                                WHERE cat.plugin_formcreator_categories_id = $categoryId
                                AND cat.id IN ($id_locations_cat)";

            // EXECUTA A QUERY
            $result_categories = $DB->query($query_home_forms);

            // ATRIBUI O RESULTADO NO ARRAY
            while ($id = $DB->fetchArray($result_categories)) {

                $form[] = $id['id'];
            }

            // CONVERTE OS ID PARA UMA STRING, PARA ADEQUAR NO SELECT
            $id_cat = implode(',', $form);

           
            // QUERY QUE TRAZ OS FORMULÁRIOS
            $query_categorie_forms = "SELECT
                                    form.id AS id_form,
                                    form.name AS name_form,
                                    form.description AS form_description,
                                    form.plugin_formcreator_categories_id AS  form_categorie_id, 
                                    form.icon
                                    FROM $form_table AS form                                    
                                    WHERE form.plugin_formcreator_categories_id IN ($id_cat)                                     
                                    AND form.is_active = 1
                                    AND form.is_deleted = 0                                  
                                    ORDER BY form.name ASC";
            
            // EXECUTA A QUERY
            $result_categorie_forms = $DB->query($query_categorie_forms);


            // ATRIBUIÇÃO DO RESULTADO A UM ARRAY
            while ($cat_forms = $DB->fetchArray($result_categorie_forms)) {


                if (Validar::validaForm($cat_forms['id_form'])) {
                    $forms[] = $cat_forms;
                }
            }


            // QUERY QUE TRAZ AS CATEGORIAS           

             $query_categorie_id = "SELECT 
                                    cat.id,
                                    cat.name,
                                    cat.comment,
                                    cat.completename,
                                    cat.plugin_formcreator_categories_id,
                                    cat.level
                                    FROM glpi_plugin_formcreator_categories AS  cat
                                    LEFT JOIN glpi_plugin_formcreator_forms as form
                                    ON cat.id = form.plugin_formcreator_categories_id
                                    LEFT JOIN ( SELECT * FROM glpi_plugin_front_laststickets WHERE user_id = $idUser) glpi_plugin_front_laststickets 
                                    ON form.id = glpi_plugin_front_laststickets.plugin_formcreator_forms_id                                   
                                    WHERE cat.id IN ($id_cat)
                                    GROUP BY cat.name 
                                    ORDER BY sum(glpi_plugin_front_laststickets.count) DESC";

            // EXECUTAÇÃO DAS QUERYS ACIMA
            $result_categorie_id = $DB->query($query_categorie_id);

            // ATRIBUIÇÃO DO RESULTADO A UM ARRAY
            while ($cat_forms = $DB->fetchArray($result_categorie_id)) {
                $cat_form[] = $cat_forms;
            }

            $cat_form[] = $forms;
            $json = $cat_form;
        }



        echo json_encode($json);
    }


    /*
    *   MÉTODO QUE RETORNA OS ID'S DAS SUBCATEGORIAS
    *   PARÂMETRO
    *            CATEGORIA_ID
    *   RETORNO
    *            STRING COM OS ID'S EX.(33,99,15...) || FALSE - CASO NÃO TENHA NENHUM ATRIBUÍDO OU O USUÁRIO NÃO TENHA UTA
    */
    public function filtrarCategoriaLocation($categorieId)
    {

        global $DB;

        //TABELA COM AS UTAS
        $uta_table = 'glpi_locations';
        //TABELA COM AS UTAS E AS SUBCATEGORIAS QUE PODEM SER VISUALIZADOS
        $uta_cat_table = 'glpi_plugin_front_location_home';
        //TABELA COM OS USUÁRIOS
        $user_table = 'glpi_users';
        //TABELA COM AS CATEGORIAS
        $cat_table = 'glpi_plugin_formcreator_categories';

        //ATRIBUO O ID DO USUÁRIO LOGADO
        $user_id = $_SESSION['glpiID'];

        //SELECT QUE RETORNA OS ID'S DAS SUBCATEGORIAS QUE PODEM SER VISUALIZADAS POR AQUELE USUÁRIO DAQUELE GRUPO
        $query_get_locationId = "SELECT locationHome.categorie_id AS id FROM $user_table AS user
                                    INNER JOIN $uta_table AS location ON
                                    location.id = user.locations_id
                                    INNER JOIN $uta_cat_table AS locationHome ON
                                    locationHome.location_id = location.id
                                    INNER JOIN $cat_table AS cat ON
                                    cat.id = locationHome.categorie_id
                                    WHERE user.id = $user_id
                                    AND cat.plugin_formcreator_categories_id = $categorieId";
        //EXECUTA A QUERY
        $result_location_id = $DB->query($query_get_locationId);

        //ATRIBUO O RESULTADO EM UM ARRAY
        while ($location_id = $DB->fetchArray($result_location_id)) {
            $id_location[] = $location_id['id'];
        }


        //VERIFICO SE TEM DADOS NO ARRAY
        if ($id_location) {
            //CONVERTO EM STRING
            $location_id = implode(',', $id_location);
            //RETORNO
            return $location_id;
        } else {
            //RETORNO
            return false;
        }
    }

    /*
    *   MÉTODO QUE RETORNA O OPTION COM OS PERFIS
    *   SEM PARÂMETRO
    *
    *   RETORNO
    *       HTML( SELECT ) COM OS PERFIS
    */

    // public static function getAllProfile()
    // {

    //     if ($_SESSION['glpia'])

    //         $id_perfil_atual = $_SESSION['glpiactiveprofile']['id'];

    //     if ($id_perfil_atual != 4) {

    //         return 'Acesso não permitido';
    //     }

    //     $query_profiles = "SELECT * FROM glpi_profiles";

    //     // EXECUTA A QUERY
    //     $result_profiles = $DB->query($query_profiles);

    //     // ATRIBUIÇÃO DO RESULTADO A UM ARRAY
    //     while ($profiles = $DB->fetchArray($result_profiles)) {
    //         $profiles = $profiles;
    //     }

//        return $profiles;
    // }


}
