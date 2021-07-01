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


//header('Content-Type: text/javascript');
?>

<script>

    var currentCategory
    var currentPage
    var totalPages
    var totalForms
    var currentFormList = []

    $("#formulario").hide();

    $('#searchloader').hide();

    function getBrowser() {

        return window.navigator.userAgent
    }

    $("#formSearch").keyup(function (e) {

        if (e.which == 13) {            
         $("#formSearchSubmit").submit();         
        }
        
        var form = $("#formSearch").val();

        /* ADD VALUE INPUT HIDDEN FOR ANALICTS  */
        if (form.length < 13) {
            $("#frontsearch").val(form)
        }

        if (form.length >= 2) {
            $('#searchloader').show()
        }

    }); //end keyup

    $("#formSearch").autocomplete({
            minLength: 2,
            source: "../inc/search_db.php",
            select:  function(event, ui) { 
                $("#formSearch").val(ui.item.label);
                $("#formSearchSubmit").submit(); 
            },
            response: function () {
                $('#searchloader').hide(200)
            },
            
            open: function(event, ui)  {
                var acData = $(this).data('ui-autocomplete');   
                acData
                .menu
                .element
                .find('li div')
                .each(function () {
                    const me = $(this);
                    const keywords = acData.term.toLowerCase();
                    const text = me.text().toLowerCase();
                    const index = text.indexOf(keywords);

                    if (index === 0) {
                        const searchText = me.text().slice(0, keywords.length);
                        const boldText = '<b>' + me.text().slice(keywords.length) + '</b>';
                        me.html(searchText + boldText);
                    } 
                    if (index > 0) {
                        const startText = '<b>' + me.text().slice(0, index) + '</b>';
                        const searchText = me.text().slice(index, keywords.length + index);
                        const boldText = '<b>' + me.text().slice(index + keywords.length ) + '</b>';
                        me.html(startText + searchText +  boldText);
                    }
                });
                $("#formSearch").autocomplete ("widget").css("width","auto").css("minwidth","392px");
            } 
    });

    $(window).scroll(function () {
        $(".ui-autocomplete-input").autocomplete("close");
        console.log(window.innerHeight, document.body.clientHeight)
        console.log(window.innerHeight + window.scrollY + 50, document.body.clientHeight)
        if (window.innerHeight + window.scrollY + 100 > document.body.clientHeight) {
            // $('#footer-container').css({"display":"unset"})
            $('#footer').css({"position":"fixed"})
        } else {
            // $('#footer-container').css({"display":"none"})
            $('#footer').css({"position":"relative"})
        }
    });

    if(window.innerHeight < document.body.clientHeight) {
        // $('#footer-container').css({"display":"none"})
        $('#footer').css({"display":"relative"})
    }

    currentPageUrl = window.location.pathname.split('/')
    fileNamePage = currentPageUrl[currentPageUrl.length - 1]
    if(fileNamePage === 'aprovacao.php') {
        $('#footer').css({"position":"fixed"})
    }

    $('#formSearch').blur(function () {

        $('#searchloader').hide()
    });

    $('.categoria_nome').on("click", function () {

        var p = $(this).find("h5").html();
        $(".categoria_selecionada").html(p);

    });

    function  loadPesquisa() {
        document.getElementById("formSearch").focus();
    }


    $( "#title-form-tooltip" ).tooltip();

    /*
   *  Realiza a requisição Ajax
   *  Trata o JSON e insere o html na view
   */
    function showFormsCategories(categoryId) {
        currentCategory = categoryId
        currentPage = 1
        //apresenta a div dos formularios
        $("#formulario").show(100);
        //apresenta o loading
        $('.loader').show();

        //variaveis
        var last
        var sons
        var cats

        $.ajax({

            url: "../ajax/home_form.php",
            type: "GET",
            data: {
                categoryID: categoryId
            },
            dataType: "json"

        }).done(function (response) {
            console.log(response)
            //limpa a div dos formularios
            $('#formularios').empty()
            currentFormList = []

            //verifica se é subcategoria ou apenas formularios
            if (response) {
                response.forEach(function (obj, index) {
                    last = index;
                })
                if (response[0].comment !== undefined) {
                    //separa as subcategorias do formulários
                    response.forEach(function (obj, index) {

                        if (index == last) {
                            sons = obj
                            response.pop(sons)
                            cats = response
                        }

                    })
                    var sonForm = []
                    var sonInfo = []

                    cats.forEach(function (cat, index) {
                        i = 0
                        sonForm = []
                        sonInfo = []
                        if (sons != null) {
                            sons.forEach(function (son, index) {
                                if (cat.id == son.form_categorie_id && i < 3) {
                                    sonForm.push(
                                        `<div id="title-form-tooltip" title="${son.name_form}" class="card-form-name-content">
                                        <a href="../../formcreator/front/formdisplay.php?id=${son.id_form}" class="categoria_nome link-form-sistemas form-name line-clamp-content">
                                                ${son.name_form}
                                            </a>
                                        </div>`
                                        )
                                    if(son.form_description) {
                                        sonInfo.push(
                                            `
                                                ${son.name_form}:
                                                ${son.form_description}

                                            `
                                        )
                                    }
                                    i += 1
                                }
                            })
                            if(sonForm.length > 0) {
                                var html = `<div class="col-sm-6 col-md-6 col-lg-4 col-xl-3">
                                                        <div class="card">
                                                            <div class="card-body">
                                                                <div class="face front>
                                                                    <div class="inner">
                                                                        <div class="card-direction">
                                                                            <div class="custom-card-space">
                                                                                <div class="icone icone-acesso">
                                                                                <i class="icone-acesso-sistemas"
                                                                                    style=" background-image:url('../assets/img/91.png')"></i>
                                                                                </div>
                                                                            </div>
                                                                            <div class="card-content">
                                                                                <div class="card-title-content">
                                                                                    <span id="title-form-tooltip" class="card-title-category line-clamp-title" title="${cat.name} - ${cat.comment}">${cat.name} - ${cat.comment}</span>
                                                                                    <div class="icon-help-form">
                                                                                        <i class="fa fa-question-circle" title="${sonInfo.join("")}"></i>
                                                                                    </div>
                                                                                </div>
                                                                                ${sonForm.join("")}
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                </div>`
                                currentFormList.push(html)
                            }
                        }
                    })

                    totalForms = currentFormList.length
                    totalPages = Math.ceil(totalForms / 12)
                    drawPagination()
                    drawFormlist()
                } else {

                    totalForms = response.length
                    totalPages = Math.ceil(totalForms / 12)
                    drawPagination()

                    response.forEach(function (obj, index) {
                        html = `<div class="col-sm-6 col-md-6 col-lg-4 col-xl-3">
                                    <a href="../../formcreator/front/formdisplay.php?id=${obj.id}" class="categoria_nome">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="face front>
                                                    <div class="inner">
                                                        <div class="card-direction">
                                                            <div class="custom-card-space">
                                                                <div class="icone icone-acesso">
                                                                    <i class="${obj.icon !== '' ? obj.icon : 'fas fa-paste'} icon-props"></i>
                                                                </div>
                                                            </div>
                                                            <div class="card-content">
                                                                <div class="card-title-content-geral">
                                                                    <span id="title-form-tooltip" class="card-title-category line-clamp-title" title="${obj.categoria}">${obj.categoria}</span>
                                                                    <div class="icon-help-form" title="${obj.description}">
                                                                        <i class="fa fa-question-circle"></i>
                                                                    </div>
                                                                </div>
                                                                <div id="title-form-tooltip" class="card-form-name-content">
                                                                    <span class="form-name-geral line-clamp-content" title="${obj.name}">${obj.name}</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>`

                        currentFormList.push(html)
                    })
                    drawFormlist()
                }
            } else {

                //apresenta mensagem na div
                $('#formularios').append('Você não possui formulários disponíveis.')

            }

        }).fail(function (jqXHR, textStatus, response) {
            //limpa a div
            $('#formularios').empty();
            //apresenta mensagem na div
            $("#formularios").html('<p>Formulários não disponíveis!</p>');
            //apresenta mensagem no console
            console.log("Request failed: " + textStatus);

        }).always(function () {

            //foca na div
            $(".footer").css("position", "inherit");
            $("#formulario").focus();
        })

    }

    function drawFormlist() {
        $('#formularios').empty()
        const pageSize = 12
        const startValue = (currentPage * pageSize) - pageSize
        const endValue= currentPage * pageSize
        let currentContent = currentFormList.slice(startValue, endValue)
        for (const item of currentContent) {
            $('#formularios').append(item)
        }


        $(".icon-help-form").tooltip({
            classes: {
                "ui-tooltip": "help-tooltip",
                "ui-tooltip-content": "help-tooltip-content"
            }
        });
        var opts = $(".icon-help-form").tooltip("option")
    }

    function drawPagination(){
        var paginationPages = []
        var spreadItem

        console.log('Current page ->', currentPage)

        for (let i = 1; i <= totalPages; i++) {
            paginationPages.push(`<li class="page-item ${currentPage === i ? "active" : ""}"><a class="page-link" onClick="moveToPage(${i})" >${i}</a></li>`)            
        }


        var paginationHtml = `
        <nav>
            <ul class="pagination justify-content-center">
                <li> <span ></span> </li>
                <li class="page-item">
                <a class="page-link" aria-label="Previous" onClick="decrementPage()">
                    <span aria-hidden="true">&laquo;</span>
                    <span class="sr-only">Previous</span>
                </a>
                </li>
                ${ paginationPages.join("") }
                <li class="page-item">

                <a class="page-link" aria-label="Next" onClick="incrementPage()" >
                    <span aria-hidden="true">&raquo;</span>
                    <span class="sr-only">Next</span>
                </a>
                </li>
            </ul>
            <div class="total-items"> <span>Total de formulários encontrados: ${totalForms}</span> </div>
        </nav>`

        $('#form-pagination').empty().append(paginationHtml)
    }

    function moveToPage(pageToGo) {
        currentPage = pageToGo
        drawFormlist()
        drawPagination()
    }

    function incrementPage() {
        console.log(currentPage, totalPages)
        if(currentPage < totalPages){
            currentPage ++
            drawFormlist()
            drawPagination()
        }
    }

    function decrementPage(){
        if(currentPage > 1){
            currentPage --
            drawFormlist()
            drawPagination()
        }
    }

    $(document).ready(function () {
        // Add scrollspy to <body>
        $('body').scrollspy({
            target: ".navbar2",
            offset: 100
        });
        // Add smooth scrolling on all links inside the navbar
        $("#menu a").on('click', function (event) {
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
                }, 800, function () {

                    // Add hash (#) to URL when done scrolling (default click behavior)
                    window.location.hash = hash;
                });
            } // End if
        });
    });
</script>

<?php if ($_SESSION['glpiactiveprofile']['id'] == 4) { ?>
    <script>



        // $('#getuta').change( () => {
        //     $.ajax({
        //         url: "../ajax/change_home.php",
        //         type: "GET",
        //         data: {
        //             diretoria_id: $('#getuta').val()
        //         }
        //     }).done(function( req ) {
        //         $('#uta_id').empty();
        //         $.each( req ,function(index, value){
        //             $('#uta_id').append(`<option value="${value.id}">${value.name}</option>`);
        //             $('#uta_id').removeAttr('disabled');
        //
        //         });
        //         $('#uta_id').selectpicker('refresh');
        //     });
        // });

        $('button.locationcategorie').click(function () {

            sub_id = $('#locationcontent').val();
            $.ajax({
                url: "../ajax/change_home.php",
                type: "POST",
                data: {
                    status: $(this).val(),
                    uta_id: $('#uta_id').val(),
                    sub_id: sub_id.toString(),
                    cat_id: $('#selectsub').val()
                }
            }).done(function (response) {
                ua = getBrowser()
                ie = ua.indexOf("Trident")
                if (ie > 1) {
                    location.reload()
                } else {
                    Swal.fire({
                        title: 'Bom trabalho!',
                        text: "Home atualizada com sucesso!",
                        type: 'success',
                        confirmButtonText: 'OK!'
                    });
                }
            }).fail(function (jqXHR, textStatus, response) {

                console.log("Request failed: " + textStatus);

            }).always(function () {

                console.log("Concluído");

            });

        });

        $('#selectsub').change(function () {


            $.ajax({

                url: "../ajax/change_home.php",
                type: "GET",
                dataType: 'json',
                data: {
                    uta_id: $('#uta_id').val(),
                    categoryID: $('#selectsub').val()
                }
            }).done(function (response) {

                $('#locationcontent').empty();

                response.forEach(function (obj, index) {

                    if (obj.status == 1) {
                        html = '<option  value="' + obj.id + '" selected>'
                    } else {
                        html = '<option  value="' + obj.id + '">'
                    }

                    html += obj.name
                    html += '</option>'


                    $('#locationcontent').append(html)
                });

                $('#locationcontent').selectpicker('refresh')

            }).fail(function (jqXHR, textStatus, response) {

                $('#locationcontent').html('<option selected disabled>Adicione uma SUB-CATEGORIA</option>');
                $('#locationcontent').selectpicker('refresh');
                console.log("Request failed: " + textStatus);

            }).always(function () {

                console.log("Concluído");

            });

        });

        $('button.update_home').click(function () {


            num = $('#profile-content').val();

            $.ajax({

                url: "../ajax/change_home.php",
                type: "POST",
                data: {
                    status: $(this).val(),
                    profile_id: $('#profileget').val(),
                    categoryID: num.toString()
                }
            }).done(function (response) {
                ua = getBrowser();
                ie = ua.indexOf("Trident");
                if (ie > 1) {
                    location.reload();
                } else {
                    Swal.fire({
                        title: 'Bom trabalho!',
                        text: "Home atualizada com sucesso!",
                        type: 'success',
                        confirmButtonText: 'OK!'
                    });
                }
            }).fail(function (jqXHR, textStatus, response) {

            }).always(function () {
                console.log("Concluído");
            });


        });

        $.fn.selectpicker.defaults = {
            selectAllText: 'Selecionar todos',
            deselectAllText: 'Selecionar nenhum'
        };

        $('#profileget').change(function () {
            console.log('alterar a categoria');
            $.ajax({

                url: "../ajax/home_form.php",
                type: "GET",
                data: {
                    profile_id: $('#profileget').val()
                },
                dataType: "json"

            }).done(function (response) {

                
                $('#profile-content').empty()

                response.forEach(function (obj, index) {
                    if (obj.status == 1) {
                        html = '<option  value="' + obj.id + '" selected>'
                    } else {
                        html = '<option  value="' + obj.id + '">'
                    }

                    html += obj.name
                    html += '</option>'


                    $('#profile-content').append(html)
                })

                $('#profile-content').selectpicker('refresh');

            }).fail(function (jqXHR, textStatus, response) {

                console.log("Request failed: " + textStatus);

            }).always(function () {

                console.log("Concluído");

            })


        });

    </script>

<?php } ?>
