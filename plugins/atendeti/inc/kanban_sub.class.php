<?php
/**
 * ---------------------------------------------------------------------
 * GLPI - Gestionnaire Libre de Parc Informatique
 * Copyright (C) 2015-2018 Teclib' and contributors.
 *
 * http://glpi-project.org
 *
 * based on GLPI - Gestionnaire Libre de Parc Informatique
 * Copyright (C) 2003-2014 by the INDEPNET Development Team.
 *
 * ---------------------------------------------------------------------
 *
 * LICENSE
 *
 * This file is part of GLPI.
 *
 * GLPI is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * GLPI is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with GLPI. If not, see <http://www.gnu.org/licenses/>.
 * ---------------------------------------------------------------------
 */

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access this file directly");
}

/**
 * Template for task
 * @since 9.1
**/
class PluginAtendetiKanban_sub extends CommonGLPI {

   static function getMenuContent() {

      global $DB;
      $front_atendeti = "/plugins/atendeti/front";

      $menu = [];
  
      

      $query = "SELECT COUNT(*) AS total FROM glpi_plugin_front_rules WHERE name = 'enable_ticket' AND status = 1";
         $temKanban = 0;
         if ($result = $DB->query($query)) {
               $data = $DB->fetchAssoc($result);
               $temKanban = (integer) $data['total'];
         }
   
         if($temKanban){
            $menu['page']  = "$front_atendeti/kanban.php";
            $menu['title'] = self::getMenuName();
            $menu['icon'] = self::getIcon();
            

         }   
      return $menu;

 }

      static function getIcon() {
         return "fas fa-columns";
      }

      public static function getMenuName() {
         return __('Chamados');
      }

}