<?php 

require_once("../../../inc/includes.php");
include_once('dashboard-header.php');

include_once('../inc/rules.class.php');

$rules = PluginFrontRules::getAllRules();

?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header card-header-icon card-header-info">
                    <div class="card-icon">
                        <i class="material-icons">dns</i>
                    </div>
                    <h4 class="card-title ">Regras e Funcionalidades</h4>

                </div>
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th class="text-center">#</th>
                                <th>Descrição</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($rules as $rule) {
                                ?>
                                <tr>
                                    <td class="text-center"><?= $rule['id'] ?></td>
                                    <td><?= $rule['description'] ?></td>
                                    <td>

                                        <div class="togglebutton">
                                            <label>
                                                <input type="checkbox" class="rule_update" name="<?= $rule['id'] ?>" <?php if ($rule['status'] == 1) {
                                                                                                                                echo "checked='true'";
                                                                                                                            } ?>>
                                                <span class="toggle"></span>
                                            </label>
                                        </div>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'dashboard-footer.php'; ?>
<script>
    $("input.rule_update").click(function(event) {

        $.ajax({
            url: "../ajax/dashboard.php",
            type: "POST",
            data: {
                rule_id: $(event.target).attr("name")
            }
        });
        
    });
</script>