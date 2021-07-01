<?php
require_once('../../../inc/includes.php');


if (!defined('GLPI_ROOT')) {
    die("Sorry. You can't access this file directly");
}

Session::checkLoginUser();

include_once(GLPI_ROOT . "/inc/based_config.php");
include_once(GLPI_ROOT . "/inc/define.php");
include_once(GLPI_ROOT . "/inc/dbconnection.class.php");


class Dashboard
{
    public static function getAllCategoryProfile()
    {

        global $DB;
        $query_forms = "SELECT perfilTable.name as perfil, 
                        cat.name as categoria,home.id
                        FROM glpi_plugin_front_categories_home as home
                        INNER JOIN glpi_plugin_formcreator_categories as cat ON cat.id = home.categorie_id
                        INNER JOIN glpi_profiles AS perfilTable ON  perfilTable.id = home.profile_id
                        ORDER BY Perfil ASC ";

        $result = $DB->query($query_forms);

        while ($res = $DB->fetchArray($result)) {
            $array[] = $res;
        }

        foreach ($array as $arr) {
            $return[$arr[0]]['perfil'] = $arr[0];
            $return[$arr[0]]['categorias'][] = $arr['categoria'];

        }

        return $return;
    }

    public static function getAllCategoryUTA()
    {

        global $DB;
        $query_forms = "SELECT  location.name as UTA,cat.name as Categoria 
        FROM glpi_locations as location
        INNER JOIN glpi_plugin_front_location_home as uta_cat 
        ON  location.id = uta_cat.location_id 
        INNER JOIN glpi_plugin_formcreator_categories as cat 
        ON  cat.id = uta_cat.categorie_id
        ORDER BY location.name ASC";

        $result = $DB->query($query_forms);
        if (!mysqli_num_rows($result)) {
            return false;
        } else {
            while ($res = $DB->fetchArray($result)) {
                $array[] = $res;
            }
            
            foreach ($array as $arr) {
                $return[$arr[0]][] = $arr['Categoria'];          
            }
        
            return $return;
        }   
    }

    public static function getHomeUser()
    {
        global $DB;

        // TABELA QUE CONTÉM OS ID DAS SUBCATEGORIAS QUE TEM QUE APARECER
        $profile_table = 'glpi_profiles';

        // QUERY QUE TRAZ OS ID DE ACORDO COM A CATEGORIA SELECIONADA
        $query_home_forms = "SELECT  $profile_table.id,$profile_table.name
                            FROM  $profile_table ";

        // EXECUTA A QUERY
        $result_profiles = $DB->query($query_home_forms);


        while ($profiles_table = $DB->fetchArray($result_profiles)) {

            $profiles[] = $profiles_table;
        }

        return $profiles;
    }

    public static function callGetCategorieHomeUser($profile_id)
    {
        
        if (count($profile_id) > 1 && is_array($profile_id)) {
            foreach ($profile_id as $profile) {

                $retorno = self::getCategorieHomeUser($profile);
            }
        } else {
            $retorno = self::getCategorieHomeUser(reset($profile_id));
        }

       // return $retorno;
        header('Content-Type: application/json');
        echo json_encode($retorno,JSON_UNESCAPED_UNICODE);
       
    }

    public static function getCategorieHomeUser($profile_id)
    {

        // VARIÁVEL QUE EXECUTA A QUERY
        global $DB;

        // TABELAS UTILIZADAS NA QUERY
        $cat_table = 'glpi_plugin_formcreator_categories';

        // TABELA QUE CONTÉM OS ID DAS SUBCATEGORIAS QUE TEM QUE APARECER
        $profile_cat_table = 'glpi_plugin_front_categories_home';

   
        // QUERY QUE TRAZ OS ID DE ACORDO COM A CATEGORIA SELECIONADA
        $query_home_forms = "SELECT $profile_cat_table.id as profile_id,
                                    $cat_table.id,
                                    $cat_table.name
                                FROM $profile_cat_table
                                INNER JOIN  $cat_table ON
                                $profile_cat_table.categorie_id =  $cat_table.id
                                WHERE $profile_cat_table.profile_id =  $profile_id
                                AND $profile_cat_table.level = 1";

        // EXECUTA A QUERY
        $result_home_user = $DB->query($query_home_forms);

        // ATRIBUI O RESULTADO NO ARRAY
        while ($id = $DB->fetchArray($result_home_user)) {
            $id['status'] = 1;
            $categories[] = $id;
        }

        // QUERY QUE TRAZ OS ID DE ACORDO COM A CATEGORIA SELECIONADA
        $query_categories = "SELECT cat.id, cat.name FROM  $cat_table as cat
                                WHERE  cat.level = 1 ";

        // EXECUTA A QUERY
        $result_categories = $DB->query($query_categories);

        // ATRIBUI O RESULTADO NO ARRAY
        while ($id = $DB->fetchArray($result_categories)) {
            $id['status'] = 0;
            $categories_new[] = $id;
        }

        foreach ($categories as $cat) {

            foreach ($categories_new as $key => $i) {

                if ($cat['id'] == $i['id']) {
                    unset($categories_new[$key]);
                }
            }
        }

        foreach ($categories_new as $cat) {
            $categories[] = $cat;
        }
        return $categories;

    }

    public static function callUpdateHomeCategorie($status, $profile_id, $categorie_id = 0)
    {
        if ($_SESSION['glpiactiveprofile']['id'] != 4) {
            die('Você não tem acesso!');
        }
        Session::checkRight("entity", UPDATE);
        if ($status == 'add') {
            if (count($profile_id) == 1) {
                self::addPerfilCategoria(reset($profile_id), $categorie_id);
            } else if (count($profile_id) > 1 && is_array($profile_id)) {
                foreach ($profile_id as $profile) {
                    self::addPerfilCategoria($profile, $categorie_id);
                }
            }
        } else if ($status == 'remove') {
            if (count($profile_id) == 1) {
                self::removePerfilCategoria(reset($profile_id), $categorie_id);
            } else if (count($profile_id) > 1 && is_array($profile_id)) {
                foreach ($profile_id as $profile) {
                    self::removePerfilCategoria($profile, $categorie_id);
                }
            }

        }

    }

    private function addPerfilCategoria($profile_id, $categorie_id)
    {
        // VARIÁVEL QUE EXECUTA A QUERY
        global $DB;


        // TABELA QUE CONTÉM OS ID DAS SUBCATEGORIAS QUE TEM QUE APARECER
        $profile_cat_table = 'glpi_plugin_front_categories_home';
        $categories = $categorie_id;

        $categories_id = explode(',', $categorie_id);


        foreach ($categories_id as $categorie_id) {


            $query_categorias = "SELECT profile_cat.id FROM   $profile_cat_table AS profile_cat
                                    WHERE profile_cat.categorie_id =    $categorie_id
                                    AND profile_cat.profile_id =  $profile_id
                                    AND profile_cat.level = 1";

            // EXECUTA A QUERY
            $result_categories = $DB->query($query_categorias);

            if (!mysqli_num_rows($result_categories)) {

                $query_insert_home_categorie = "INSERT INTO  $profile_cat_table ( profile_id, categorie_id, level) VALUES (  $profile_id,  $categorie_id, '1')";
                $DB->query($query_insert_home_categorie);
            }
        }

    }

    function removePerfilCategoria($profile_id, $categorie_id){

        // VARIÁVEL QUE EXECUTA A QUERY
        global $DB;


        // TABELA QUE CONTÉM OS ID DAS SUBCATEGORIAS QUE TEM QUE APARECER
        $profile_cat_table = 'glpi_plugin_front_categories_home';
        $categories = $categorie_id;

        $categories_id = explode(',', $categorie_id);

        $query_categorias_not = "SELECT profile_cat.id FROM   $profile_cat_table AS profile_cat
                                    WHERE profile_cat.categorie_id IN (  $categories )
                                    AND profile_cat.profile_id =  $profile_id
                                    AND profile_cat.level = 1";


        // EXECUTA A QUERY
        $result_categories_not = $DB->query($query_categorias_not);

        if (!mysqli_num_rows($result_categories_not)) {

            return;
        } else {

            // ATRIBUI O RESULTADO NO ARRAY
            while ($content = $DB->fetchArray($result_categories_not)) {

                $categories_new_not[] = $content;
            }


            foreach ($categories_new_not as $categorie_new_not) {

                $id_cat[] = $categorie_new_not['id'];
            }

            // CONVERTE OS ID PARA UMA STRING, PARA ADEQUAR NO SELECT
            $id_cat = implode(',', $id_cat);

            $query_delete_category_home = "DELETE FROM  $profile_cat_table WHERE id IN ( $id_cat )";
            $DB->query($query_delete_category_home);
        }
    }

    public static function getAllDiretorias()
    {
        global $DB;

        $locations_table = 'glpi_locations';

        // QUERY QUE TRAZ TODAS AS UTA'S
        $query_locations_home = "SELECT $locations_table.id,$locations_table.name
                                    FROM  $locations_table ";
        // WHERE level = 0";

        // EXECUTA A QUERY
        $result_locations = $DB->query($query_locations_home);
        if (!mysqli_num_rows($result_locations)) {
            return true;
        } else {
            while ($location = $DB->fetchArray($result_locations)) {

                $locations[] = $location;
            }

            return $locations;
        }
    }

    public static function findCategoriaToDiretoria($id)
    {
        global $DB;
        $locations_table = 'glpi_locations';

        // QUERY QUE TRAZ TODAS AS UTA'S
        $query_locations_home = "SELECT $locations_table.id,$locations_table.name
                                    FROM  $locations_table 
                                    WHERE locations_id = $id";

        // EXECUTA A QUERY
        $result_locations = $DB->query($query_locations_home);
        while ($location = $DB->fetchArray($result_locations)) {
            $categorias[] = $location;
        }

        header('Content-Type: application/json');
        echo json_encode($categorias);
    }

    public static function getAllCategories()
    {
        global $DB;

        $categories_table = 'glpi_plugin_formcreator_categories';

        // QUERY QUE TRAZ TODAS AS CATEGORIAS QUE TEM SUBCATEGORIA
        $query_categories = "SELECT  $categories_table.id,$categories_table.name
                            FROM  $categories_table
                            WHERE $categories_table.id 
                                    IN( SELECT DISTINCT  cat.plugin_formcreator_categories_id 
                                        FROM $categories_table as cat 
                                        WHERE cat.plugin_formcreator_categories_id > 0 )
                            AND level = 1";

        // EXECUTA A QUERY
        $result_categories = $DB->query($query_categories);
        if (!mysqli_num_rows($result_categories)) {
            return true;
        } else {


                while ($categorie = $DB->fetchArray($result_categories)) {

                    $categories[] = $categorie;
                }

                return $categories;
            }
    }

    public static function callGetSubCategories($uta_id, $categoryid)
    {

        if (count($uta_id) == 1) {
            $array = self::getSubCategories(reset($uta_id), $categoryid);
        } else {
            foreach ($uta_id as $uta) {
                $array = self::getSubCategories($uta, $categoryid);
            }
        }
        echo json_encode($array);
        header('Content-Type: application/json');
    }

    private function getSubCategories($uta_id, $categoryid)
    {
        global $DB;

        $categories_table = 'glpi_plugin_formcreator_categories';
        $uta_cat_table = 'glpi_plugin_front_location_home';

        // QUERY QUE TRAZ TODAS AS SUB-CATEGORIAS DAQUELA CATEGORIAS
        $query_categories = "SELECT  $categories_table.id,$categories_table.name
                                FROM  $categories_table
                                where $categories_table.level = 2
                                AND $categories_table.plugin_formcreator_categories_id = $categoryid";

        // EXECUTA A QUERY
        $result_categories = $DB->query($query_categories);

        while ($categorie = $DB->fetchArray($result_categories)) {
            $categorie['status'] = 0;
            $categories[$categorie['id']] = $categorie;
            $categories_id[] = $categorie['id'];
        }

        $categories_id = implode(',', $categories_id);

        // QUERY QUE TRAZ TODAS AS UTA'S
        $query_categories_location = "SELECT uta_cat.categorie_id as id, cat.name
                                FROM  $uta_cat_table as uta_cat
                                INNER JOIN $categories_table as cat ON
                                cat.id = uta_cat.categorie_id
                                WHERE uta_cat.categorie_id IN ( $categories_id )
                                AND uta_cat.location_id = $uta_id order by cat.name ASC";
       
        $result_categories_location = $DB->query($query_categories_location);

        while ($categorie_location = $DB->fetchArray($result_categories_location)) {
            $categorie_location['status'] = 1;
            $id_categorie_location[] = $categorie_location;
        }


        foreach ($categories as $cat) {

            foreach ($id_categorie_location as $i) {

                if ($cat['id'] == $i['0']) {
                    unset($categories[$cat['id']]);
                }
            }
        }

        foreach ($categories as $cat) {
            $id_categorie_location[] = $cat;
        }

        return $id_categorie_location;

    }

    public static function callUpdateHomeSubCategorie($status, $uta_id, $sub_categories_id, $categoriaId = 0)
    {

        if ($_SESSION['glpiactiveprofile']['id'] != 4) {
            return false;
        }

        if ($status == 'add') {
            if (count($uta_id) == 1) {
                self::addSubCategoriaUta(reset($uta_id), $sub_categories_id, $categoriaId);
            } else {
                foreach ($uta_id as $uta) {
                    self::addSubCategoriaUta($uta, $sub_categories_id, $categoriaId);
                }
            }

        } else if ($status == 'remove') {
            if (count($uta_id) == 1) {
                self::removeSubCategoriaUta(reset($uta_id), $sub_categories_id, $categoriaId);
            } else {
                foreach ($uta_id as $uta) {
                    self::removeSubCategoriaUta($uta, $sub_categories_id, $categoriaId);
                }
            }
        }


    }

    private function addSubCategoriaUta($uta_id, $sub_categories_id, $categoriaId = 0)
    {


        // VARIÁVEL QUE EXECUTA A QUERY
        global $DB;


        // TABELA QUE CONTÉM OS ID DAS SUBCATEGORIAS QUE TEM QUE APARECER
        $uta_cat_table = 'glpi_plugin_front_location_home';
        $categories_table = 'glpi_plugin_formcreator_categories';


        foreach ($sub_categories_id as $sub_categorie) {

            $query_categorias = "SELECT uta_cat.categorie_id FROM   $uta_cat_table AS uta_cat
                                    WHERE uta_cat.categorie_id =    $sub_categorie
                                    AND uta_cat.location_id =  $uta_id ";


            $result_categories = $DB->query($query_categorias);


            if (!mysqli_num_rows($result_categories)) {

                $query_insert_home_categorie = "INSERT INTO  $uta_cat_table (location_id, categorie_id) VALUES ( $uta_id, $sub_categorie)";
                $DB->query($query_insert_home_categorie);
            }
        }


    }

    private function removeSubCategoriaUta($uta_id, $sub_categories_id, $categoriaId = 0)
    {

        // VARIÁVEL QUE EXECUTA A QUERY
        global $DB;

        // TABELA QUE CONTÉM OS ID DAS SUBCATEGORIAS QUE TEM QUE APARECER
        $uta_cat_table = 'glpi_plugin_front_location_home';
        $categories_table = 'glpi_plugin_formcreator_categories';

        $sub_categories_id = implode(',', $sub_categories_id);
        $query_categorias_not = "SELECT uta_cat.id FROM   $uta_cat_table AS uta_cat
                                    INNER JOIN  $categories_table AS cat ON
                                    cat.id = uta_cat.categorie_id
                                    WHERE uta_cat.categorie_id IN (  $sub_categories_id )
                                    AND cat.plugin_formcreator_categories_id = $categoriaId
                                     AND uta_cat.location_id =  $uta_id";


        // EXECUTA A QUERY
        $result_categories_not = $DB->query($query_categorias_not);
        if (!mysqli_num_rows($result_categories_not)) {
            return true;
        } else {
            // ATRIBUI O RESULTADO NO ARRAY
            while ($content = $DB->fetchArray($result_categories_not)) {
                $categories_new_not[] = $content;
            }

            foreach ($categories_new_not as $categorie_new_not) {

                $id_cat[] = $categorie_new_not['id'];
            }

            // CONVERTE OS ID PARA UMA STRING, PARA ADEQUAR NO SELECT
            $id_cat = implode(',', $id_cat);
            $query_delete_category_home = "DELETE FROM  $uta_cat_table WHERE id IN ( $id_cat )";
            $DB->query($query_delete_category_home);
        }
    }

    /* Amtecnologia code */
    static function getContentAlert($params = [])
   {
      
       if ($alerts = PluginNewsAlert::findAllToNotify()) {

           for( $i = 0; $i < count($alerts); $i++) {

               switch ($alerts[$i]['type']) {
                   case 1:
                       $alerts[$i]['type'] = "success";
                       $alerts[$i]['icon'] = "check";

                       break;
                   case 2:
                       $alerts[$i]['type'] = "info";
                       $alerts[$i]['icon'] = "info_outline";
                       break;
                   case 3:
                       $alerts[$i]['type'] = "warning";
                       $alerts[$i]['icon'] = "warning";
                       break;
                   case 4:
                       $alerts[$i]['type'] = "danger";
                       $alerts[$i]['icon'] = "error_outline";
                       break;
               }
           }
           return $alerts;
       }
       return false;
   }

   
static function console_log( $data ){
    echo '<script>';
    echo 'console.log('. json_encode( $data ) .')';
    echo '</script>';
  }

}