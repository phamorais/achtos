<?php

include "../../../inc/includes.php";

Session::checkLoginUser();

Html::header(__('Kanban', 'helpdesk'), $_SERVER['PHP_SELF'], 'kanban', 'PluginAtendetiKanban_sub');
?>
<iframe id="iframe-kanban" src="<?php echo ATENDETI_ROOTDOC . '/front/kanban.iframe.php' ?>" style="width: 100%; position: absolute; border: none; margin: 0; padding: 0; display: block;"></iframe>
<script type="text/javascript">
$(document).on('ready', function() {
    var $iframe = $('#iframe-kanban');
    var $parent = $iframe.parent();
    $parent.css('margin-top', 0);
    $parent.css('padding-right', 0);
    $parent.css('padding-left', 0);

    var updateHeight = function() {
        $iframe.css('min-height', $parent.innerHeight());
    }

    $(window).on('resize', updateHeight);
    updateHeight();
});
</script>
<?php
Html::footer();
