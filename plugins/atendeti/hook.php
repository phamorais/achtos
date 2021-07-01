<?php

/**
 * Install all necessary elements for the plugin
 * @return boolean True if success
 */
function plugin_atendeti_install()
{
   global $DB;
   spl_autoload_register('plugin_atendeti_autoload');
   $version = plugin_version_atendeti();

   //instanciate migration with version
   $migration = new Migration(100);

   //Create table only if it does not exists yet!
   if (!$DB->tableExists('glpi_plugin_front_rules')) {
      //table creation query
      $query = "CREATE TABLE `glpi_plugin_front_rules` ( 
         `id` INT(11) NOT NULL auto_increment PRIMARY KEY, 
         `name` VARCHAR(255) NOT NULL, 
         `status` INT NOT NULL, 
         `description` TEXT NOT NULL) 
         ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";

      $DB->queryOrDie($query, $DB->error());
      insert_to_rules();

   }else{
      //se a tabela existe valida se os registros estão ok
      $query = "SELECT COUNT(*) AS total FROM glpi_plugin_front_rules ";
      $resetRegistro = 0;
      if ($result = $DB->query($query)) {
          $data = $DB->fetchAssoc($result);
          $resetRegistro = (integer) $data['total'];

          if($resetRegistro < 2){
             $query = "delete from glpi_plugin_front_rules";
             $DB->query($query);
             insert_to_rules();
          }
      }

   }

   if (!$DB->tableExists('glpi_plugin_front_laststickets')) {
      //table creation query
      $query = "CREATE TABLE `glpi_plugin_front_laststickets`(
         `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
         `plugin_formcreator_forms_id` INT NOT NULL,
         `user_id` INT NOT NULL,
         `count` INT NOT NULL         
         )ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";

      $DB->queryOrDie($query, $DB->error());
   }

   if (!$DB->tableExists('glpi_plugin_front_location_home')) {
      //table creation query
      $query = "CREATE  TABLE `glpi_plugin_front_location_home` (
         `id` INT(11)  NOT NULL AUTO_INCREMENT ,
         `location_id` INT(11)  NOT NULL  ,
         `categorie_id` INT(11)  NOT NULL ,
         PRIMARY KEY (`id`) ,
         FOREIGN KEY (`location_id`)  REFERENCES `glpi_locations`(`id`),
         FOREIGN KEY (`categorie_id`) REFERENCES `glpi_plugin_formcreator_categories`(`id`)
       )ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";

      $DB->queryOrDie($query, $DB->error());
   }

   if (!$DB->tableExists('glpi_plugin_front_categories_home')) {
      //table creation query
      $query = "CREATE  TABLE `glpi_plugin_front_categories_home` (
         `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
         `profile_id` INT UNSIGNED NOT NULL  ,
         `categorie_id` INT UNSIGNED NOT NULL ,
          `level` INT UNSIGNED NOT NULL default 0,
         PRIMARY KEY (`id`) ,
           FOREIGN KEY (`profile_id`)  REFERENCES `glpi_profiles`(`id`),
         FOREIGN KEY (`categorie_id`) REFERENCES `glpi_plugin_formcreator_categories`(`id`)
       )ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";

      $DB->queryOrDie($query, $DB->error());
   }
   if (!$DB->tableExists('glpi_plugin_kanban_config')) {
      //table creation query
      $query = "CREATE  TABLE `glpi_plugin_kanban_config` (
         `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
         `user_id` INT  NOT NULL  ,
         `column_new` BOOLEAN  NULL ,
         `column_processing_assigned` BOOLEAN  NULL ,
         `column_processing_planned` BOOLEAN  NULL ,
         `column_pending` BOOLEAN  NULL ,
         `column_solved` BOOLEAN  NULL ,
         `column_closed` BOOLEAN  NULL ,
         PRIMARY KEY (`id`) 
         )ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";

      $DB->queryOrDie($query, $DB->error());
   }

   //execute the whole migration
   $migration->executeMigration();

   return true;
}

/**
 * Uninstall previously installed elements of the plugin
 */
function plugin_atendeti_uninstall()
{
   //$install = new PluginAtendetiInstall();
   //$install->uninstall();
}
/**
 * Cria Registros 
 */
function insert_to_rules(){

  global $DB; 
   $DB->insert(
      'glpi_plugin_front_rules',
      [
         'name'            => 'approve_ticket',
         'description'     => 'Ticket não poderá ser solucionado ou fechado caso tenha aprovações pendentes.',
         'status'          => 0,
      ]
   );

   $DB->insert(
      'glpi_plugin_front_rules',
      [
         'name'            => 'enable_ticket',
         'description'     => 'Habilitar kanban - Interface padrão.',
         'status'          => 0,
      ]
   );
}

/**
 * Ticket update interceptor
 *
 * @param Ticket $ticket
 */
if (!function_exists("plugin_atendeti_hook_pre_update_ticket")) {
   function plugin_atendeti_hook_pre_update_ticket(Ticket $ticket)
   {
      global $CFG_GLPI;

      $dir  = GLPI_ROOT . "/plugins/atendeti/inc/";
      $item = strtolower('Ticket');

      if (file_exists("$dir$item.class.php")) {
         include_once("$dir$item.class.php");
      }

      $ticketCheckAprove = new PluginFrontTicket();
      $ticketCheckAprove->beforeUpdate($ticket);
   }
}


if (!function_exists("plugin_atendeti_hook_pre_add_ticket_follow")) {
   function plugin_atendeti_hook_pre_add_ticket_follow(ITILSolution $solution)
   {
      global $CFG_GLPI;

      $dir  = GLPI_ROOT . "/plugins/atendeti/inc/";
      $item = strtolower('Ticket');

      if (file_exists("$dir$item.class.php")) {
         include_once("$dir$item.class.php");
      }

      $ticketCheckAproveFollow = new PluginFrontTicket();
      $ticketCheckAproveFollow->beforeAddFollowUp($solution);
   }

   if (!function_exists("plugin_atendeti_hook_pos_add_ticket")) {
      function plugin_atendeti_hook_pos_add_ticket(Ticket $ticket)
      {         

         global $CFG_GLPI;
       
         $dir  = GLPI_ROOT . "/plugins/atendeti/inc/";
         $item = strtolower('plugin_front_historico');

         if (file_exists("$dir$item.php")) {            
            include_once ("$dir$item.php");            
         }
         
         if($_REQUEST['plugin_formcreator_forms_id']){
            $IDForm = $_REQUEST['plugin_formcreator_forms_id'];         
         }else{        
            return true;   
         }
         
         $UserID = $_SESSION['glpiID'];
         
         $historico = new LastSearchForms();
         
         if ($historico->hasLinks($IDForm, $UserID)) {
            $historico->AddCount($IDForm, $count = 1);
         } else {
            $historico->AddSearch($IDForm, $UserID);
         }
       
      }
   }
}
