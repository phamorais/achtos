<?php

/**
 * ---------------------------------------------------------------------
 * Formcreator is a plugin which allows creation of custom forms of
 * easy access.
 * ---------------------------------------------------------------------
 * LICENSE
 *
 * This file is part of Formcreator.
 *
 * Formcreator is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * Formcreator is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Formcreator. If not, see <http://www.gnu.org/licenses/>.
 * ---------------------------------------------------------------------
 * @author    Thierry Bugier
 * @author    Jérémy Moreau
 * @copyright Copyright © 2011 - 2019 Teclib'
 * @license   http://www.gnu.org/licenses/gpl.txt GPLv3+
 * @link      https://github.com/pluginsGLPI/formcreator/
 * @link      https://pluginsglpi.github.io/formcreator/
 * @link      http://plugins.glpi-project.org/#/plugin/formcreator
 * ---------------------------------------------------------------------
 */



if (!defined('GLPI_ROOT')) {
    die("Sorry. You can't access this file directly");
}

class PluginFrontRules extends CommonTreeDropdown
{
    static function getAllRules()
    {

        global $DB;
        foreach ($DB->request('glpi_plugin_front_rules') as $field) {
            $rules[] = $field;
        }

        return $rules;
    }
    static function updateRoleStatus($role_id)
    {
        global $DB;
        foreach ($DB->request('glpi_plugin_front_rules', ['id' => $role_id]) as $field) {
            $rule = $field;
        }

        if ($rule['status']) {
            $rule['status'] = 0;
        } else {
            $rule['status'] = 1;
        }        
       
        $DB->update('glpi_plugin_front_rules', [
            'status' =>   $rule['status'],
        ], ['WHERE'  => ['id' => $role_id]]);

      

        return;
    }
}
