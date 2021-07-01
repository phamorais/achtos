<?php
//include_once('../../../config/constants.php');

//echo "<script type=\"text/javascript\">
    //$(document).ready(function() {
        
    //     $.get('".PEXIA_HTTP."://".PEXIA_DOMAIN."', function() {
        
    //         $.get('".PEXIA_API_HTTP."://".PEXIA_API_DOMAIN."', function(response) {
                
    //             if (response.core) {
                
    //                 console.log('Pexia API is Online');
                  
    //                $('body').append(
    //                     '<style>' +
    //                     '.widget-pexia-container {' +
    //                     'box-sizing: content-box;' +
    //                     'padding: 5px 0px 0px 5px;' +
    //                     'font-size: 12px;' +
    //                     'position: fixed;' +
    //                     'bottom: 40px;' +
    //                     'right:10px!important;'+
    //                     'z-index: 2147483647;' +
    //                     'font-family: \'Bitstream Vera Sans\', Verdana, Tahoma, \'Sans serif\'!important;' +

    //                     '}' +


    //                     '.widget-pexia-start {' +
    //                    'background: url(" . PEXIA_HTTP . "://" . PEXIA_DOMAIN . "/assets/pexia.png) no-repeat center center;' +
    //                     'background-color: transparent !important;' +
    //                     'background-size: cover;' +
    //                     '-webkit-border-radius: 47px;' +
    //                     'border-radius: 47px;' +
    //                     'display: block;' +
    //                     'height: 83px;' +
    //                     'width: 86px;' +
    //                     'padding: 10px;' +
    //                     'cursor: pointer;' +
    //                     'box-sizing: unset;' +
    //                     '}' +

    //                     '.widget-pexia-container-chat {' +
    //                     'background-color: #FFFFFF;' +
    //                     'display: none;' +
    //                     'overflow: auto;' +
    //                     'border-radius: 6px;' +
    //                     '-webkit-box-shadow: 0px 0px 16px -2px rgba(0,0,0,0.72);' +
    //                     '-moz-box-shadow: 0px 0px 16px -2px rgba(0,0,0,0.72);' +
    //                     'box-shadow: 0px 0px 16px -2px rgba(0,0,0,0.72);' +

    //                     '}' +

    //                     '.widget-pexia-header {' +
    //                     'background-color: #b81f26;' +
    //                     'position: relative;' +
    //                     'line-height: 15px;' +
    //                     'text-align: right;' +
    //                     'padding: 10px 10px 5px 5px;' +
    //                     '}' +

    //                     '.widget-pexia-header i {' +
    //                     'color: #FFFFFF;' +
    //                     'font-size: 1.2em;' +
    //                     'padding-left: 8px;' +
    //                     'cursor: pointer;' +
    //                     '}' +

    //                     '.widget-pexia-iframe {' +
    //                     'position: inherit;' +
    //                     'height: 450px;' +
    //                     'overflow-y: hidden;' +
    //                     'background: #336099;' +

    //                     '}' +

    //                     '.widget-pexia-iframe iframe {' +
    //                     'border: 0;' +
    //                     '}' +

    //                     '.widget-pexia-help-message {' +
    //                     'display:none;'+    
    //                     ' margin-top: 18px;' +
    //                     'margin-left:-173px!important;'+
    //                     'padding-left:32px!important;'+
    //                     'width: 212px;' +
    //                     'height: 70px;' +
    //                     'border-radius: 10px;' +
    //                     'background: #023e87;' +
    //                     'position: absolute;' +
    //                     'color: #ffffff;' +
    //                     'padding-left: 60px;' +
    //                     'border: 1px solid #023e87;' +
    //                     'z-index: -1;' +
    //                     'font-weight: bold;' +
    //                     'box-sizing: border-box;'+
    //                     '}' +

    //                     '.widget-pexia-help-message p{' +

    //                     'font-size:10.8px!important;' +
    //                     'margin:5.8px 0px!important;' +


    //                     '}' +

    //                     '.widget-pexia-help-message-close {' +
    //                     'color: #023e87 !important;' +
    //                     'background: #fff;' +
    //                     'top: -25px !important;' +
    //                     'right:204px !important;'+
    //                     'border: 1px solid #023e87 !important;' +
    //                     'position: absolute !important;' +
    //                     'cursor: pointer;' +
    //                     'border-radius: 16px;' +
    //                     'padding: 1px 7px 0px 6px;' +
    //                     'font-size: 12px;' +
    //                     'line-height: 21px;' +
    //                     '}' +
    //                     '</style>' +
                              
            
    //                     '<div class=\"widget-pexia-container\">' +
    //                         '<div class=\"widget-pexia-help-message\">' +
    //                             '<i class=\"widget-pexia-help-message-close\" title=\"fechar\" onclick=\"pexiaCloseHelpMessage()\">×</i>' +
    //                             '<p>Olá, Eu sou a Pexia!</p>' +
    //                             '<p>Em que posso ajudar?</p>' +
    //                         '</div>' +
    //                         '<div class=\"widget-pexia-start\" onclick=\"pexiaStart()\"></div>' +
    //                     '</div>'
    //                 );
                    
    //             } else {
    //                 console.log('Pexia Core is Offline, call Widget LHC');
    //                 getLhc();
    //             }
                
    //         }).fail(function(apiError) {
    //             console.log('Pexia API is Offline, call Widget LHC');
    //             getLhc();
    //         });
            
    //     }).fail(function(frontendError) {
    //         console.log('Pexia FrontEnd is Offline, call Widget LHC');
    //         getLhc();
    //     });
        
    // });

    // function pexiaStart() {
    //     $('.widget-pexia-container').html(
    //             '<div class=\"widget-pexia-container-chat\">' +
    //                 '<div class=\"widget-pexia-header\">' +
    //                     '<i class=\"fa fa-window-restore\" onclick=\"pexiaOpenNewWindows()\" title=\"Abrir em nova janela\"></i>' +
    //                     '<i class=\"fa fa-window-minimize\" onclick=\"pexiaClose()\" title=\"Fechar\"></i>' +
    //                 '</div>' +
    //                 '<div class=\"widget-pexia-iframe\">' +
    //                     '<iframe width=\"320\" height=\"450\" src=\"".PEXIA_HTTP."://".PEXIA_DOMAIN."/ia\"></iframe>' +
    //                 '</div>' +
    //             '</div>'
    //         );
    //     $('.widget-pexia-container-chat').slideDown();
    // }

    // function pexiaOpenNewWindows() {
    //     $('.widget-pexia-container').html(
    //         '<div class=\"widget-pexia-container\">' +
    //             '<div class=\"widget-pexia-start\" onclick=\"pexiaStart()\"></div>' +
    //         '</div>'
    //     );

    //     var openPexia = window.open(\"".PEXIA_HTTP."://".PEXIA_DOMAIN."/ia\", \"windowOpenTab\", \"top=100,left=50, width=400,height=600\");
    // }
    
    // function pexiaClose() {
    //     $('.widget-pexia-container-chat').slideUp(function () {
    //         $('.widget-pexia-container').html(
    //             '<div class=\"widget-pexia-container\">' +
    //                 '<div class=\"widget-pexia-start\" onclick=\"pexiaStart()\"></div>' +
    //             '</div>'
    //         );
    //     });
    // }

    // function pexiaCloseHelpMessage() {
    //     $('.widget-pexia-help-message').remove();
    // }
    
    // function getLhc() {
    //     LHCChatOptions = {};
    //     LHCChatOptions.opt = {widget_height:340,widget_width:300,popup_height:520,popup_width:500,domain: '".LHC_DOMAIN."'};
    //     var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
    //     var referrer = (document.referrer) ? encodeURIComponent(document.referrer.substr(document.referrer.indexOf('://')+1)) : '';
    //     var location  = (document.location) ? encodeURIComponent(window.location.href.substring(window.location.protocol.length)) : '';
    //     po.src = '".LHC_HTTP."://".LHC_DOMAIN."/index.php/por/chat/getstatus/(click)/internal/(position)/bottom_right/(ma)/br/(top)/350/(units)/pixels/(leaveamessage)/true/(department)/1?r='+referrer+'&l='+location;
    //     var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
    // }
    
//</script>";


/*  Descomentar as linhas acima e retirar essa abaixo   */
echo "<script type='text/javascript'>
var LHCChatOptions = {};
LHCChatOptions.opt = {widget_height:340,widget_width:300,popup_height:520,popup_width:500};
(function() {
var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
var referrer = (document.referrer) ? encodeURIComponent(document.referrer.substr(document.referrer.indexOf('://')+1)) : '';
var location  = (document.location) ? encodeURIComponent(window.location.href.substring(window.location.protocol.length)) : '';
po.src = '//ccs.poupex.com.br/index.php/eng/chat/getstatus/(click)/internal/(position)/bottom_right/(ma)/br/(top)/350/(units)/pixels/(leaveamessage)/true?r='+referrer+'&l='+location;
var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
})();
</script>";
/*  POUPEX CODE   */

?>
