<?php

if (!defined('GLPI_ROOT')) {
    die("Sorry. You can't access this file directly");
}

class PluginFrontTicket extends CommonDBTM
{
    static function checkRuleStatus($name)
    {
        global $DB;

        foreach ($DB->request('glpi_plugin_front_rules', ['name' => $name]) as $field) {
            $rule = $field;
        }
       
        return $rule['status'];
        
    }

    static function beforeUpdate(Ticket $ticket)
    {
        global $DB;

        if (!self::checkRuleStatus('approve_ticket')) return;

        if (!is_array($ticket->input) || !count($ticket->input)) {
            // Already cancel by another plugin
            return false;
        }

        $dbu = new DbUtils();

        // Check is the connected user is a tech
        if (
            !is_numeric(Session::getLoginUserID(false))
            || !Session::haveRight('ticket', UPDATE)
        ) {
            return false; // No check
        }

        if (isset($ticket->input['status']) && $ticket->input['status'] >= 5) {
            $totalValidacao = [];
            foreach ($DB->request('glpi_ticketvalidations', ['status'   => '2', 'tickets_id'   => $ticket->input['id']]) as $validacoesPendentes) {
                $totalValidacao[] = $validacoesPendentes;
            }
            if (count($totalValidacao) > 0) {
                Session::addMessageAfterRedirect("É necessário que todas as aprovações sejam finalizadas!", 'atendeti', true, ERROR);
                Html::back();
            }
        }

        return;
    }





    static function beforeAddFollowUp(ItilSolution $solution)
    {
        
        global $DB;

        if (!self::checkRuleStatus('approve_ticket')) return;

        foreach ($DB->request('glpi_ticketvalidations', ['status'   => '2', 'tickets_id'   => $solution->fields['items_id']]) as $validacoesPendentes) {
            $totalValidacao[] = $validacoesPendentes;
        }
       
        if (count($totalValidacao) > 0) {
            Session::addMessageAfterRedirect("É necessário que todas as aprovações sejam finalizadas!", 'atendeti', true, ERROR);
            Html::back();
        }

        return;
    }
}
