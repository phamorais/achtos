</div>
</div>

<br>
<br>


<!-- <footer id="footer" class="footer footer-default">
    <div id="footer-container" >
        <div class="container">
        <img src="../assets/img/logo-poupex-rodape-branca.png" />
            
        </div>
    </div>
</footer> -->


<!--   Core JS Files   -->
<script src="../assets/js/core/jquery.min.js" type="text/javascript"></script>
<script src="../assets/js/core/popper.min.js" type="text/javascript"></script>
<script src="../assets/js/core/bootstrap-material-design.min.js" type="text/javascript"></script>
<script src="../assets/js/plugins/moment.min.js"></script>

<!--	Plugin for the Datepicker, full documentation here: https://github.com/Eonasdan/bootstrap-datetimepicker -->
<script src="../assets/js/plugins/bootstrap-datetimepicker.js" type="text/javascript"></script>
<!--  Plugin for the Sliders, full documentation here: http://refreshless.com/nouislider/ -->
<script src="../assets/js/plugins/nouislider.min.js" type="text/javascript"></script>


<!--  Google Maps Plugin  -->

<!-- Place this tag in your head or just before your close body tag. -->
<script async defer src="https://buttons.github.io/buttons.js"></script>

<!-- Control Center for Material Kit: parallax effects, scripts for the example pages etc -->
<script src="../assets/js/material-kit.js?v=2.0.5" type="text/javascript"></script>

<script src="../assets/js/bootstrap-select.min.js" type="text/javascript"></script>
<script src="../assets/js/sweetalert.js" type="text/javascript"></script>
<script src="../assets/plugins/reateit/jquery.rateit.min.js" type="text/javascript"></script>


<!-- <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script> -->
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js" integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU=" crossorigin="anonymous"></script>
<script src="<?= $CFG_GLPI["root_doc"] ?>/plugins/atendeti/assets/js/bootstrap-notify.min.js" type="text/javascript"></script>



<?php if (count($_SESSION['MESSAGE_AFTER_REDIRECT']) >= 1) {
    
    $messages = $_SESSION['MESSAGE_AFTER_REDIRECT'];
    $_SESSION['MESSAGE_AFTER_REDIRECT'] = [];

    foreach ($messages as $message) {  ?>

        <script>
            $.notify({              
                message: "<?= $message[0] ?>"
            }, {
                type: 'danger',
                placement: {
                    from: "bottom",
                    align: "right"
                },
                offset: 20,
                spacing: 10,
                z_index: 1031,
                delay: 5000,
                timer: 1000,
                url_target: '_blank',
                mouse_over: null,
                animate: {
                    enter: 'animated fadeInRight',
                    exit: 'animated fadeOutRight'
                }
            });
        </script>

<?php }
} ?>

<?php
include '../assets/js/ajax/footer-ajax.php';
include 'pexiacode.php';
?>


<script>
    $(document).ready(function() {

        // Add scrollspy to <body>
        $('body').scrollspy({
            target: ".navbar2",
            offset: 100
        });

        // Add smooth scrolling on all links inside the navbar
        $(".ancora a").on('click', function(event) {
            // Make sure this.hash has a value before overriding default behavior
            if (this.hash !== "") {
                // Prevent default anchor click behavior
                event.preventDefault();

                // Store hash
                var hash = this.hash;

                // Using jQuery's animate() method to add smooth page scroll
                // The optional number (800) specifies the number of milliseconds it takes to scroll to the specified area
                $('html, body').animate({
                    scrollTop: $(hash).offset().top
                }, 800, function() {

                    // Add hash (#) to URL when done scrolling (default click behavior)
                    window.location.hash = hash;
                });
            } // End if
        });
    });
</script>



<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src='https://www.googletagmanager.com/gtag/js?id=UA-140325506-1'></script>
<script>
    window.dataLayer = window.dataLayer || [];

    function gtag() {
        dataLayer.push(arguments);
    }

    gtag('js', new Date());

    gtag('config', 'UA-140325506-1');
</script>


</body>
</html>
