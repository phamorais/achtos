<?php

global $CFG_GLPI;

define('PLUGIN_ATENDETI_VERSION', '2.0.0');
define('ATENDETI_ROOTDOC', $CFG_GLPI['root_doc'] . '/plugins/atendeti');
define('PLUGIN_ATENDETI_GLPI_MIN_VERSION', '9.3.3');

/**
 * Define the plugin's version and informations
 *
 * @return Array [name, version, author, homepage, license, minGlpiVersion]
 */
if (!function_exists("plugin_version_atendeti")) {
   function plugin_version_atendeti() {
      $glpiVersion = rtrim(GLPI_VERSION, '-dev');

      if (!method_exists('Plugins', 'checkGlpiVersion') && version_compare($glpiVersion, PLUGIN_ATENDETI_GLPI_MIN_VERSION, 'lt')) {
         echo 'This plugin requires GLPI >= ' . PLUGIN_ATENDETI_GLPI_MIN_VERSION;
         return false;
      }

      $requirements = [
         'name' => 'Front Atende TI', 'Front Atende TI', 2, 'atendeti',
         'version' => PLUGIN_ATENDETI_VERSION,
         'author' => '<b>Nextflow</b>',
         'homepage' => 'https://github.com/phamorais/atendeti',
         'requirements' => [
            'glpi' => [
               'min' => PLUGIN_ATENDETI_GLPI_MIN_VERSION,
            ]
         ]
      ];

      return $requirements;
   }
}

/**
 * Check plugin's prerequisites before installation
 *
 * @return boolean
 */
if (!function_exists("plugin_atendeti_check_prerequisites")) {
   function plugin_atendeti_check_prerequisites() {
      return true;
   }
}

/**
 * Check plugin's config before activation (if needed)
 *
 * @return boolean
 */
if (!function_exists("plugin_atendeti_check_config")) {
   function plugin_atendeti_check_config() {
      return true;
   }
}

/**
 * Class autoload
 *
 * @param $classname
 * @return bool
 */
if (!function_exists("plugin_atendeti_autoload")) {
   function plugin_atendeti_autoload($classname) {
      if (strpos($classname, 'PluginFront') === 0) {
         $filename = __DIR__ . '/inc/' . strtolower(str_replace('PluginFront', '', $classname)) . '.class.php';
         if (is_readable($filename) && is_file($filename)) {
            include_once($filename);
            return true;
         }
      }

   }
}

/**
 * Initialize all classes and generic variables of the plugin
 */

if (!function_exists("plugin_init_atendeti")) {
   function plugin_init_atendeti() {
      global $PLUGIN_HOOKS, $CFG_GLPI,$DB;

      $PLUGIN_HOOKS['csrf_compliant']['atendeti'] = true;

      $PLUGIN_HOOKS['pre_item_update']['atendeti'] = ['Ticket' => 'plugin_atendeti_hook_pre_update_ticket'];
      $PLUGIN_HOOKS['pre_item_add']['atendeti'] = ['ITILSolution' => 'plugin_atendeti_hook_pre_add_ticket_follow'];
      $PLUGIN_HOOKS['pre_item_add']['atendeti'] = ['ITILSolution' => 'plugin_atendeti_hook_pre_add_ticket_follow'];
      $PLUGIN_HOOKS['item_add']['atendeti'] = ['Ticket' => 'plugin_atendeti_hook_pos_add_ticket'];
      $PLUGIN_HOOKS['config_page']['atendeti'] = 'front/dashboard.php';

      $plugin = new Plugin();
      if ($plugin->isInstalled('atendeti') && $plugin->isActivated('atendeti')) {

         if (isset($_SESSION['glpiactiveentities_string'])) {
            // Redirect to helpdesk replacement
            // Redirect in login to front
            if (strpos($_SERVER['REQUEST_URI'], "front/helpdesk.public.php") !== false) {
               if (!isset($_POST['newprofile']) && !isset($_GET['active_entity'])) {
                  // Not changing profile or active entity
                  if (isset($_SESSION['glpiactiveprofile']['interface'])
                        && isset($_SESSION['glpiactive_entity'])) {
                     // Interface and active entity are set in session
                     Html::redirect($CFG_GLPI["root_doc"]."/plugins/atendeti/index.php");

                  }
               }
            }

            Plugin::registerClass(KanbanTemplate::class);
            Plugin::registerClass(PluginAtendetiKanban::class, ['addtabon' => Central::class]);
            // Cria Menu kanban

            $PLUGIN_HOOKS['menu_toadd']['atendeti'] =['kanban' => ['PluginAtendetiKanban','PluginAtendetiKanban_sub']];

            // Redirect in wizard formcreator to front
            if (strpos($_SERVER['REQUEST_URI'], "formcreator/front/wizard.php") !== false) {
               if (!isset($_POST['newprofile']) && !isset($_GET['active_entity'])) {
                  // Not changing profile or active entity
                  if (isset($_SESSION['glpiactiveprofile']['interface'])
                        && isset($_SESSION['glpiactive_entity'])) {
                     // Interface and active entity are set in session
                     Html::redirect($CFG_GLPI["root_doc"]."/plugins/atendeti/index.php");

                  }
               }
            }

         }
      }

   }
}
