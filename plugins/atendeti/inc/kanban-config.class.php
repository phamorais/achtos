<?php
include_once('../../../inc/includes.php');


if (!defined('GLPI_ROOT')) {
    die("Sorry. You can't access this file directly");
}

Session::checkLoginUser();

include_once(GLPI_ROOT . "/inc/based_config.php");
include_once(GLPI_ROOT . "/inc/define.php");
include_once(GLPI_ROOT . "/inc/dbconnection.class.php");

class KanbanConfig
{


    public static function getCofigKanban(){
        global $DB;
        $users_id = $_SESSION['glpiID'];

        $query = "SELECT COUNT(*) AS total FROM glpi_plugin_kanban_config WHERE user_id = $users_id";
        $temKanban = 0;
        if ($result = $DB->query($query)) {
            $data = $DB->fetchAssoc($result);
            $temKanban = (integer) $data['total'];
         }

         if($temKanban > 0){
             $query = "SELECT *  FROM glpi_plugin_kanban_config WHERE user_id = $users_id";
             $result = $DB->request($query);
             return $result->next();
         }else{
             self::insertShowColumnKanban();
             $query = "SELECT *  FROM glpi_plugin_kanban_config WHERE user_id = $users_id";
             $result = $DB->request($query);
             $result = $result->next();  
             return $result;
         }
        
    }

    public static function insertShowColumnKanban(){
        $users_id = $_SESSION['glpiID'];
        global $DB;
        
        $users_id = $_SESSION['glpiID'];
        $DB->insert('glpi_plugin_kanban_config', [
            'user_id' =>  $users_id,                
            'column_new'  => true,
            'column_processing_assigned'=>true,
            'column_processing_planned'=>true,                        
            'column_pending'=> true,
            'column_solved'  =>true,
            'column_closed'  => true
        ]);
    }

    public static function setUpdateConfigKanban($post){
        global $DB;
        $users_id = $_SESSION['glpiID'];
        return $DB->update('glpi_plugin_kanban_config', ['column_new' => $post['column_new'],'column_processing_assigned' => $post['column_processing_assigned'],'column_processing_planned' => $post['column_processing_planned'],'column_pending' => $post['column_pending'],'column_solved' => $post['column_solved'],'column_closed' => $post['column_closed']], ['user_id' =>$users_id]);
       
    }

}