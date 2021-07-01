/*=========================================================================================
    File Name: kanban.js
    Description: kanban plugin
    ----------------------------------------------------------------------------------------
    Item Name: Frest HTML Admin Template
    Version: 1.0
    Author: PIXINVENT
    Author URL: http://www.themeforest.net/user/pixinvent
==========================================================================================*/

$(document).ready(function () { 


  var kanban_curr_el, kanban_curr_item_id, kanban_item_title, kanban_data, kanban_item, kanban_users;  

  var kanban_board_data = $.ajax({
      type: "GET", 
      url: "../ajax/kanban.php", 
      async: false
    }).responseText;
  
   
  kanban_board_data = JSON.parse(kanban_board_data);  
  //kanban_board_data = JSON.stringify(kanban_board_data);  

  //combo categorias consultar na tabela dropdown_itilcategories
  //combo incidente x requisição buscar na tabela dropdown_type 
  //combo localização buscar na tabela dropdown_locations
  // Kanban Board
montarQuadro(kanban_board_data,false);

          
function montarQuadro(kanban_board_data,filtrado){
  $(".kanban-container").html("");
  var KanbanExample = new jKanban({
    
 

    element: "#kanban-wrapper", // selector of the kanban container
    //buttonContent: "+ Criar", // text or html content of the board button
    buttonContent: "", // text or html content of the board button
    
    // click on current kanban-item
    click: function (el) {
      // kanban-overlay and sidebar display block on click of kanban-item
 
      $('#detalheChamdo').modal();
      
      // Set el to var kanban_curr_el, use this variable when updating title
      kanban_curr_el = el;

      // Extract  the kan ban item & id and set it to respective vars
      kanban_item_title = $(el).contents()[0].data;
      kanban_curr_item_id = $(el).attr("data-eid");
      
      
      function obterIniciais(text) {
        if (!text) {
          return '--';
        }
        var frase = text;
    
          var index = frase.indexOf(' ');
    
          var a = frase.substring(0, index);
          var b = frase.substring(index + 1);

          return a.substr(0,1).toUpperCase() + b.substr(0,1).toUpperCase()
        } 

        function obterChatAcompanhamento(acompanhamentos){
            htmlChat="";
            acompanhamentos.forEach(acompanhamento => {
              
              if(acompanhamento.timeline_position == 1){
               

                  htmlChat = htmlChat +' <div class="chat chat-left" style="text-align: left;">' +
                  '<div class="chat-body">' +
                      '<div class="chat-message">'+
                          '<span class=" badge badge-circle badge-light-primary" >'+ obterIniciais(acompanhamento.firstname) +'</span>'+ acompanhamento.content +
                          '<span class="chat-time">'+  acompanhamento.date+'</span>' +  '</div> </div></div>';

              }
              else if(acompanhamento.timeline_position == 2){
                htmlChat = htmlChat +' <div class="chat chat-left ">' +
                '<div class="chat-body">' +
                    '<div class="chat-message">'+
                    '<span class=" badge badge-circle badge-light-primary" >'+ obterIniciais(acompanhamento.firstname) +'</span>'+ acompanhamento.content +
                        '<span class="chat-time">'+ acompanhamento.date +'</span>' +  '</div> </div></div>';


              }
              else if(acompanhamento.timeline_position == 3){
               

                htmlChat = htmlChat +' <div class="chat " style="text-align: left;">' +
                '<div class="chat-body">' +
                    '<div class="chat-message">'+
                        '<span class=" badge badge-circle badge-light-primary" >'+ obterIniciais(acompanhamento.firstname) +'</span>'+ acompanhamento.content +
                        '<span class="chat-time">'+  acompanhamento.date+'</span>' +  '</div> </div></div>';

            }
              else if(acompanhamento.timeline_position == 4){
                htmlChat = htmlChat +' <div class="chat ">' +
                '<div class="chat-body">' +
                    '<div class="chat-message">'+
                    '<span class=" badge badge-circle badge-light-primary" >'+ obterIniciais(acompanhamento.firstname) +'</span>'+ acompanhamento.content +
                        '<span class="chat-time">'+ acompanhamento.date +'</span>' +  '</div> </div></div>';


              }

            });

            return htmlChat;
        }

      $.ajax
      ({
          type: 'GET',
          dataType: 'html',
          url: '../ajax/kanban.php',          
          data: {"kanban_curr_item_id" : kanban_curr_item_id},
          success: function(retorno) 
          {             
              var obj = jQuery.parseJSON( retorno ); 
              $('#id').val(obj.id);
              $('#id').html(obj.id);
              $("#linkChamado").attr("href", "../../../front/ticket.form.php?id=" +obj.id );
              $('#name').html(obj.name);
              $('#date').val(obj.date);
              $('#date').html(obj.date);
              $('#name_tag').html(obj.name_tag);
              $('#proprietario').html(obj.proprietario.firstname);
              $('#atribuicao').html(obj.atribuicao.firstname);
              $('#iniciaisProprietario').html( obterIniciais(obj.proprietario.firstname));
              $('#iniciaisAtribuicao').html(obterIniciais(obj.atribuicao.firstname));
              $('#mostrar-dados').addClass('active');
              $('#mostrar-acompanhamento').removeClass('active');

              if(obj.color == null){
                $('#tag').attr('style',' display:none;');
              }else{
                  $('#tag').attr('style','color: #111211 !important;background-color:'+ obj.color);
              }    
              $('#content').html(obj.content.replaceAll('h1', 'h4').replaceAll('h2','h5'));
              $('#chat-acompanhamento').html(obterChatAcompanhamento(obj.acompanhamentos));

              // Scroll final do chat
              // Primeiro exibe os acompanhamentos
              $('#dados-acompanhamento-tab').tab('show');
              $('#dados-acompanhamento-tab').click();
              setTimeout(function() {
                // Atualiza o scrool
                $(".widget-chat-demo-scroll").prop('scrollTop', $(".widget-chat-demo-scroll").prop('scrollHeight'));
                // Volta para descrição;
                $('#dados-formulario-tab').tab('show');
                $('#dados-formulario-tab').click();
              }, 200);
              // $(".widget-chat-demo-scroll").scrollTop($(".widget-chat-demo-scroll").prop('scrollHeight'));
         

          },
          error: function(error)
          {
              $('#error',error);
          }
      });

      // set edit title
      $(".edit-kanban-item .edit-kanban-item-title").val(kanban_item_title);
    },
    

    buttonClick: function (el, boardId) {
      // create a form to add add new element
      var formItem = document.createElement("form");      
      formItem.setAttribute("class", "itemform");
      formItem.innerHTML =
        '<div class="form-group">' +
        '<textarea class="form-control add-new-item" rows="2" autofocus required></textarea>' +
        "</div>" +
        '<div class="form-group">' +
        '<button type="submit" class="btn btn-primary btn-sm mr-50">Submit</button>' +
        '<button type="button" id="CancelBtn" class="btn btn-sm btn-danger">Cancel</button>' +
        "</div>";

      // add new item on submit click
      KanbanExample.addForm(boardId, formItem);
      formItem.addEventListener("submit", function (e) {
        e.preventDefault();
        var text = e.target[0].value;
        KanbanExample.addElement(boardId, {
          title: text
        });
        formItem.parentNode.removeChild(formItem);
      });
      $(document).on("click", "#CancelBtn", function () {
        $(this).closest(formItem).remove();
      })
    },
    addItemButton: true, // add a button to board for easy item creation
    boards: kanban_board_data // data passed from defined variable
  });



  // Add html for Custom Data-attribute to Kanban item
  var board_item_id, board_item_el;  
  // Kanban board loop
  console.log("alterar tamanho");

  $('.kanban-container').hide(); 
  getApiConfigKanban(filtrado);
  $(".kanban-drag").css("max-height", $(window).height()-80);
  function obterIniciais(text) {
    if (!text) {
      return '--';
    }
    var frase = text;

      var index = frase.indexOf(' ');

      var a = frase.substring(0, index);
      var b = frase.substring(index + 1);

      return a.substr(0,1).toUpperCase() + b.substr(0,1).toUpperCase()
    } 
  for (kanban_data in kanban_board_data) {
  
    // Kanban board items loop    
    for (kanban_item in kanban_board_data[kanban_data].item) {
      var board_item_details = kanban_board_data[kanban_data].item[kanban_item]; // set item details
      board_item_id = $(board_item_details).attr("id"); // set 'id' attribute of kanban-item
      user_last_updater = $(board_item_details).attr("user_last_updater");
      user_recipient = $(board_item_details).attr("user_recipient");
      $(board_item_details).addClass( "card-kanban-overflow" );
      (board_item_el = KanbanExample.findElement(board_item_id)), // find element of kanban-item by ID
      (board_item_users = board_item_dueDate = board_item_comment = board_item_attachment = board_item_image = board_item_badge =
        " ");
      

      if( $(board_item_el).attr("data-eid") ){
          //console.log( board_item_el );  
          $(board_item_el).append(
          '<div class="kanban-footer d-flex justify-content-between mt-1">' +
            '<div class="kanban-footer-left d-flex"><div class="kanban-due-date d-flex align-items-center mr-50"> ' + 
            '<i class="bx bx-time-five font-size-small mr-25"></i><span class="font-size-small" title="Última modificação - '  +$(board_item_details).attr("date") +'">' +$(board_item_details).attr("date") + '</span></div>' + 
            '<div class="kanban-comment d-flex align-items-center mr-50">'+ 
            '<i class="bx bx-message font-size-small mr-25"></i><span class="font-size-small" title="Total de Acompanhamentos - '  + $(board_item_details).attr("qt_acompanhamento") + '">'+$(board_item_details).attr("qt_acompanhamento") +'</span></div><div class="kanban-attachment d-flex align-items-center">' + 
            '</div></div>' + 
            '<div class="kanban-footer-right"><div class="kanban-users"> <ul class="list-unstyled users-list m-0 d-flex align-items-center">' +
            '<li class="avatar pull-up my-0"> <span class=" badge badge-circle badge-light-primary" id="iniciaisBoard" name="iniciaisBoard" title="Requerente - '+user_recipient +'">' + obterIniciais(user_recipient) + '</span></li>' +
            '<li class="avatar pull-up my-0"><span class=" badge-atendente badge-circle badge-light-primary" id="iniciaisBoard2" name="iniciaisBoard2" title="Atendente - '+user_last_updater +'">' + obterIniciais(user_last_updater) + '</span></ul>' + 
            '</div></div></div>'
        );
        
      }
      
      if (
        typeof (
          $(board_item_el).attr("data-dueDate") ||
          $(board_item_el).attr("data-comment") ||
          $(board_item_el).attr("data-users") ||
          $(board_item_el).attr("data-attachment")
        ) !== "undefined"
      ) {
        $(board_item_el).append(
          '<div class="kanban-footer d-flex justify-content-between mt-1">' +
          '<div class="kanban-footer-left d-flex">' +
          board_item_dueDate +
          board_item_comment +
          board_item_attachment +
          "</div>" +
          '<div class="kanban-footer-right">' +
          '<div class="kanban-users">' +
          board_item_badge +
          '<ul class="list-unstyled users-list m-0 d-flex align-items-center">' +
          board_item_users +
          "</ul>" +
          "</div>" +
          "</div>" +
          "</div>"
        );
      }      
    }
  }
    if(!filtrado){
      $( "#checkFiltrar" ).prop( "checked", false);
      $( "#checkFiltrar" ).val( "off");
    }  
  // Kanban board dropdown
  // ---------------------
 
  var kanban_dropdown = document.createElement("div");
  kanban_dropdown.setAttribute("class", "dropdown");

  dropdown();

  

  function dropdown() {
    kanban_dropdown.innerHTML =
      '<div class="dropdown-toggle cursor-pointer" role="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="bx bx-dots-vertical-rounded"></i></div>' +
      '<div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton"> ' +
      '<a class="dropdown-item" href="#"><i class="bx bx-link-alt mr-50"></i>Copy Link</a>' +
      '<a class="dropdown-item kanban-delete" id="kanban-delete" href="#"><i class="bx bx-trash mr-50"></i>Delete</a>' +
      "</div>";
    if (!$(".kanban-board-header div").hasClass("dropdown")) {
      $(".kanban-board-header").append(kanban_dropdown);
    }
  }
  


  $("#kanban-wrapper > div.kanban-container > div:nth-child(1)").addClass('bg-info bg-light');
  $("#kanban-wrapper > div.kanban-container > div:nth-child(2)").addClass('bg-primary bg-light');
  $("#kanban-wrapper > div.kanban-container > div:nth-child(3)").addClass('bg-primary bg-light');
  $("#kanban-wrapper > div.kanban-container > div:nth-child(4)").addClass('bg-warning bg-light');
  $("#kanban-wrapper > div.kanban-container > div:nth-child(5)").addClass('bg-success bg-light');
  $("#kanban-wrapper > div.kanban-container > div:nth-child(6)").addClass('bg-secondary bg-light');

}

  //Kanban-overlay and sidebar hide
  // --------------------------------------------
  $(
    ".kanban-sidebar .delete-kanban-item, .kanban-sidebar .close-icon, .kanban-sidebar .update-kanban-item, .kanban-overlay"
  ).on("click", function () {
    $(".kanban-overlay").removeClass("show");
    $(".kanban-sidebar").removeClass("show");
  });

  // Updating Data Values to Fields
  // -------------------------------
  $(".update-kanban-item").on("click", function (e) {
    // var $edit_title = $(".edit-kanban-item .edit-kanban-item-title").val();
    // $(kanban_curr_el).txt($edit_title);
    e.preventDefault();
  });

  // Delete Kanban Item
  // -------------------
  $(".delete-kanban-item").on("click", function () {
    $delete_item = kanban_curr_item_id;
    addEventListener("click", function () {
      KanbanExample.removeElement($delete_item);
    });
  });

  // Making Title of Board editable
  // ------------------------------
  $(".kanban-title-board").on("mouseenter", function () {
    $(this).attr("contenteditable", "false");
    $(this).addClass("line-ellipsis");
  });

  // select default bg color as selected option
  $("select").addClass($(":selected", this).attr("class"));

  // change bg color of select form-control
  $("select").change(function () {
    $(this)
      .removeClass($(this).attr("class"))
      .addClass($(":selected", this).attr("class") + " form-control text-white");
  });

  $(function(){

    

      $("#checkPrivado").click(function(e){
          
          if($('#customSwitch1').val() == "on"){
            $('#customSwitch1').val("off");
          }else{
            $('#customSwitch1').val("on");
          }
      });

      $("#checkFiltrar").click(function(e){
      
            if($('#checkFiltrar').val() == "on"){

                  limparFiltros();

                  $('#checkFiltrar').val("off");
                  $('#loading').show(); 
                  $('.kanban-container').hide(); 
                  var kanban_board_data = $.ajax({
                  type: "GET", 
                  url: "../ajax/kanban.php", 
                  async: false
                  }).responseText;
                  
                  kanban_board_data = JSON.parse(kanban_board_data); 

                  montarQuadro(kanban_board_data,false);

            }else{
                   $('#checkFiltrar').val("on");
                   $('#loading').show(); 
                   $('.kanban-container').hide(); 
                   dataFinalAbertura = "";
                   dataInicioAbertura = "";
                   filtroLocal="";
                   filtroCategoria="";
                   filtroCategoria="";
                  filtroAbertoPorMim = $( "#colorCheckbox1" ).prop("checked");
                  filtroAtribuidoParaMim = $( "#colorCheckbox2" ).prop("checked");
                  filtroAtribuidoParaMinhaEquipe =  $( "#colorCheckbox3" ).prop("checked");
                  filtroLocal = $( "#locations" ).val();
                  filtroTipo = $( "#tipo" ).val();
                  filtroCategoria = $( "#categories" ).val();
                  dataAbertura = $("#dataAbertura" ).val().split('-', 2);
                  if(dataAbertura != ""){
                      dataInicioAbertura = dataAbertura[0];
                      dataFinalAbertura = dataAbertura[1];
                  }    

                  exutarFiltro(filtroAbertoPorMim,filtroAtribuidoParaMim,filtroAtribuidoParaMinhaEquipe,filtroLocal,filtroTipo,filtroCategoria,dataInicioAbertura,dataFinalAbertura);

            }
        });

      function exutarFiltro (filtroAbertoPorMim,filtroAtribuidoParaMim,filtroAtribuidoParaMinhaEquipe,filtroLocal,filtroTipo,filtroCategoria,dataInicioAbertura,dataFinalAbertura){

                  var kanban_board_data = $.ajax({
                  type: "POST", 
                  url: "../ajax/kanban.php", 
                  data: {requisicao:"filtrar","filtroAbertoPorMim" : filtroAbertoPorMim  ,"filtroAtribuidoParaMim" :filtroAtribuidoParaMim , "filtroAtribuidoParaMinhaEquipe" :filtroAtribuidoParaMinhaEquipe,"filtroLocal" : filtroLocal,"filtroTipo" :filtroTipo ,"filtroCategoria" : filtroCategoria,"dataInicioAbertura" : dataInicioAbertura ,"dataFinalAbertura": dataFinalAbertura },
                  async: false
                  }).responseText;
                  
                  kanban_board_data = JSON.parse(kanban_board_data); 
                  montarQuadro(kanban_board_data,true);

      }
        


      function limparFiltros(){
        $( "#colorCheckbox1" ).prop( "checked", false);
        $( "#colorCheckbox2" ).prop( "checked", false);
        $( "#colorCheckbox3" ).prop( "checked", false);
        $( "#dataAbertura" ).val("");
      }

    $("#label_check_column_new").click(function(e){

      if( $('#label_check_column_new').val() == "on"){
        $("#kanban-wrapper > div.kanban-container > div:nth-child(1)").hide();
        $('#label_check_column_new').val("off");
        $('#check_column_new').prop('checked', false);
        column_new= 0;
        setUpdateConfig();
      }else{
        $("#kanban-wrapper > div.kanban-container > div:nth-child(1)").show();
        $('#label_check_column_new').val("on");
        $('#check_column_new').prop('checked', true);
        column_new= 1;
        setUpdateConfig();
      }
      
    });


    $("#label_check_column_processing_assigned").click(function(e){

      if( $('#label_check_column_processing_assigned').val() == "on"){
        $("#kanban-wrapper > div.kanban-container > div:nth-child(2)").hide();
        $('#label_check_column_processing_assigned').val("off");
        $('#check_column_processing_assigned').prop('checked', false);
        column_processing_assigned = 0;
        setUpdateConfig();
      }else{
        $("#kanban-wrapper > div.kanban-container > div:nth-child(2)").show();
        $('#label_check_column_processing_assigned').val("on");
        $('#check_column_processing_assigned').prop('checked', true);
        column_processing_assigned = 1;
        setUpdateConfig();

      }
      
    });


    $("#label_check_column_processing_planned").click(function(e){

      if( $('#label_check_column_processing_planned').val() == "on"){
        $("#kanban-wrapper > div.kanban-container > div:nth-child(3)").hide();
        $('#label_check_column_processing_planned').val("off");
        $('#check_column_processing_planned').prop('checked', false);
        column_processing_planned=0;
        setUpdateConfig();
      }else{
        $("#kanban-wrapper > div.kanban-container > div:nth-child(3)").show();
        $('#label_check_column_processing_planned').val("on");
        $('#check_column_processing_planned').prop('checked', true);
        column_processing_planned=1;
        setUpdateConfig();
      }
      
    });

    $("#label_check_column_pending").click(function(e){

      if( $('#label_check_column_pending').val() == "on"){
        $("#kanban-wrapper > div.kanban-container > div:nth-child(4)").hide();
        $('#label_check_column_pending').val("off");
        $('#check_column_pending').prop('checked', false);
        column_pending=0;
        setUpdateConfig();
      }else{
        $("#kanban-wrapper > div.kanban-container > div:nth-child(4)").show();
        $('#label_check_column_pending').val("on");
        $('#check_column_pending').prop('checked', true);
        column_pending=1;
        setUpdateConfig();

      }
      
    });

    $("#label_check_column_solved").click(function(e){

      if( $('#label_check_column_solved').val() == "on"){
        $("#kanban-wrapper > div.kanban-container > div:nth-child(5)").hide();
        $('#label_check_column_solved').val("off");
        $('#check_column_solved').prop('checked', false);
        column_solved=0;
        setUpdateConfig();
      }else{
        $("#kanban-wrapper > div.kanban-container > div:nth-child(5)").show();
        $('#label_check_column_solved').val("on");
        $('#check_column_solved').prop('checked', true);
        column_solved=1;
        setUpdateConfig();

      }
      
    });

    $("#label_check_column_closed").click(function(e){

      if( $('#label_check_column_closed').val() == "on"){
        $("#kanban-wrapper > div.kanban-container > div:nth-child(6)").hide();
        $('#label_check_column_closed').val("off");
        $('#check_column_closed').prop('checked', false);
        column_closed=0;
        setUpdateConfig();
      }else{
        $("#kanban-wrapper > div.kanban-container > div:nth-child(6)").show();
        $('#label_check_column_closed').val("on");
        $('#check_column_closed').prop('checked', true);
        column_closed=1;
        setUpdateConfig();

      }
      
    });


    
  });




  // Kanban Quill Editor
  // -------------------
  // var composeMailEditor = new Quill(".snow-container .compose-editor", {
  //   modules: {
  //     toolbar: ".compose-quill-toolbar"
  //   },
  //   placeholder: "Write a Comment... ",
  //   theme: "snow"
  // });

  // Making Title of Board editable
  // ------------------------------
  $(".kanban-title-board").on("mouseenter", function () {
    $(this).attr("contenteditable", "true");
    $(this).addClass("line-ellipsis");
  });

  // kanban Item - Pick-a-Date
  $(".edit-kanban-item-date").pickadate();

  // Perfect Scrollbar - card-content on kanban-sidebar
  if ($(".kanban-sidebar .edit-kanban-item .card-content").length > 0) {
    new PerfectScrollbar(".card-content", {
      wheelPropagation: false
    });
  }

  // select default bg color as selected option
  $("select").addClass($(":selected", this).attr("class"));

  // change bg color of select form-control
  $("select").change(function () {
    $(this)
      .removeClass($(this).attr("class"))
      .addClass($(":selected", this).attr("class") + " form-control text-white");
  });

  $(window).resize(function(){
    $(".kanban-drag").css("max-height", $(window).height()-80);

    console.log("a página foi redimensionada ");
  })


  function swapModalButtons(){
    $("button.cancel").before($("button.confirm"))
  }



//configuração de exibição das colunas
var column_new;
var column_processing_assigned;
var column_processing_planned;
var column_pending;
var column_solved;
var column_closed;

  
 function getApiConfigKanban(filtrado){

        $.ajax
        ({
            type: 'GET',
            dataType: 'html',
            url: '../ajax/apiConfigKanban.php',          
            data: {"requisicao":"get_config"},
            success: function(retorno) 
            {     

                   var obj = jQuery.parseJSON( retorno ); 
                  column_new =obj.column_new;
                  column_processing_assigned = obj.column_processing_assigned;
                  column_processing_planned = obj.column_processing_planned;
                  column_pending = obj.column_pending;
                  column_solved  = obj.column_solved;
                  column_closed = obj.column_closed;
                    if(!filtrado){
                     carregarComboConfiguracaoFiltro();
                    }  
                    configuraExibicaoColunas(obj,filtrado);
                 
                return true;
        
            },
            error: function(error)
            { 
                $('#error',error);
                return false;
            }
        });
  }
  


  function carregarComboConfiguracaoFiltro(){
          $.ajax
          ({
              type: 'GET',
              dataType: 'html',
              url: '../ajax/apiTicket.php',          
              data: {requisicao:"get_categories"},
              success: function(retorno) 
              {   
                  
                  if (retorno != null) {
                    var data =  jQuery.parseJSON( retorno ); ;
                    var selectbox = $('#categories');
                    selectbox.find('option').remove();
                    $('<option>').val(0).text("Todos").appendTo(selectbox);
                    $.each(data, function (i, d) {
                        $('<option>').val(d.id).text(d.category).appendTo(selectbox);
                    });
                  }
              },
              error: function(error)
              { 
                  $('#error',error);
                  return false;
              }
          });


         
          var selectbox = $('#tipo');
          selectbox.find('option').remove();
          $('<option>').val(0).text("Todos").appendTo(selectbox);
          $('<option>').val(1).text("Incidente").appendTo(selectbox);
          $('<option>').val(2).text("Requisições").appendTo(selectbox);

          $.ajax
          ({
              type: 'GET',
              dataType: 'html',
              url: '../ajax/apiTicket.php',          
              data: {requisicao:"get_locations"},
              success: function(retornoLocais) 
              {   
               
                if (retornoLocais != null) {
                  var data =  jQuery.parseJSON( retornoLocais ); ;
                  var selectbox = $('#locations');
                  selectbox.find('option').remove();
                  $('<option>').val(0).text("Todos").appendTo(selectbox);
                  $.each(data, function (i, d) {
                      $('<option>').val(d.id).text(d.locations).appendTo(selectbox);
                  });
                }

              },
              error: function(error)
              { 
                  $('#error',error);
                  return false;
              }
          }); 

          $( "#colorCheckbox1" ).prop( "checked", false);
          $( "#colorCheckbox2" ).prop( "checked", false);
          $( "#colorCheckbox3" ).prop( "checked", false);
          $( "#dataAbertura" ).val("");

  }




  function setUpdateConfig(){

    $.ajax
    ({
        type: 'POST',
        dataType: 'html',
        url: '../ajax/apiConfigKanban.php',          
        data: {"requisicao":"update_config","column_new": column_new, "column_processing_assigned":column_processing_assigned, "column_processing_planned":column_processing_planned,"column_pending":column_pending,"column_solved":column_solved,"column_closed":column_closed},
        success: function(retorno) 
        {     

            return true;
    
        },
        error: function(error)
        { 
            $('#error',error);
            return false;
        }
    });
}



  function configuraExibicaoColunas(obj,filtrado){
      if(!filtrado){
        $('#dataAbertura').daterangepicker({
          "locale": {
            "format": "DD/MM/YYYY",
            "separator": " - ",
            "applyLabel": "Aplicar",
            "cancelLabel": "Cancelar",
            "daysOfWeek": [
            "Dom",
            "Seg",
            "Ter",
            "Qua",
            "Qui",
            "Sex",
            "Sab"
            ],
            "monthNames": [
            "Janeiro",
            "Fevereiro",
            "Março",
            "Abril",
            "Maio",
            "Junho",
            "Julho",
            "Agosto",
            "Setembro",
            "Outubro",
            "Novembro",
            "Dezembro"
            ],
            "firstDay": 1
            }
            
        })
        $( "#dataAbertura" ).val("");
      }   
      if(!obj.column_new){
        $("#kanban-wrapper > div.kanban-container > div:nth-child(1)").hide();
        $('#check_column_new').prop('checked', false);
        $('#label_check_column_new').val("off");
      }else{
        $("#kanban-wrapper > div.kanban-container > div:nth-child(1)").show();
        $('#check_column_new').prop('checked', true);
        $('#label_check_column_new').val("on");
      }
      if(!obj.column_processing_assigned){
        $("#kanban-wrapper > div.kanban-container > div:nth-child(2)").hide();
        $('#check_column_processing_assigned').prop('checked', false);
        $('#label_check_column_processing_assigned').val("off");
      }else{
        $("#kanban-wrapper > div.kanban-container > div:nth-child(2)").show();
        $('#check_column_processing_assigned').prop('checked', true);
        $('#label_check_column_processing_assigned').val("on");
      }
      if(!obj.column_processing_planned){
        $("#kanban-wrapper > div.kanban-container > div:nth-child(3)").hide();
        $('#check_column_processing_planned').prop('checked', false);
        $('#label_check_column_processing_planned').val("off");
      }else{
        $("#kanban-wrapper > div.kanban-container > div:nth-child(3)").show();
        $('#check_column_processing_planned').prop('checked', true);
        $('#label_check_column_processing_planned').val("on");
      }
      if(!obj.column_pending){
        $("#kanban-wrapper > div.kanban-container > div:nth-child(4)").hide();
        $('#check_column_pending').prop('checked', false);
        $('#label_check_column_pending').val("off");
      }else{
        $("#kanban-wrapper > div.kanban-container > div:nth-child(4)").show();
        $('#check_column_pending').prop('checked', true);
        $('#label_check_column_pending').val("on");
      }
      if(!obj.column_solved){
        $("#kanban-wrapper > div.kanban-container > div:nth-child(5)").hide();
        $('#check_column_solved').prop('checked', false);
        $('#label_check_column_solved').val("off");
      }else{
        $("#kanban-wrapper > div.kanban-container > div:nth-child(5)").show();
        $('#check_column_solved').prop('checked', true);
        $('#label_check_column_solved').val("on");
      }
      if(!obj.column_closed){
        $("#kanban-wrapper > div.kanban-container > div:nth-child(6)").hide();
        $('#check_column_closed').prop('checked', false);
        $('#label_check_column_closed').val("off");
      }else{
        $("#kanban-wrapper > div.kanban-container > div:nth-child(6)").show();
        $('#check_column_closed').prop('checked', true);
        $('#label_check_column_closed').val("on");
      }

      $('#loading').hide(); 
      $('.kanban-container').show(); 
  }



});




async function movendoKanban(e, t, n,b){


  
      id_status_origem= n.parentNode.dataset.id;
      id_status_destino = t.parentNode.dataset.id;
      tickets_id= e.getAttribute("data-eid");

       if(id_status_origem == "6"){
        b.drake.cancel(!0);
         return false;
        }
     
      
      if(id_status_destino == "5" && id_status_origem != "5" ){
         await obterTextoSolucaoChamado(id_status_destino,tickets_id,b).then(res =>
           console.log(res)
           );
      }else{
        return await getApiAtualizaStatus(id_status_destino,tickets_id);
      }


       
}


function validarPermissaoMovimentacaoCard(){

}


function fecharChamado(){

  textoSolucao= obterTextoSolucaoChamado();

  if(textoSolucao ==""){
    return false;
  }
}


async function obterTextoSolucaoChamado(id_status_destino,tickets_id,b){

  const { value: text } =  Swal.fire({
    input: 'textarea',
    inputLabel: 'Informe a Solução do chamado',
    inputPlaceholder: 'Solução do chamado...',
    confirmButtonText: 'Confirmar',
    cancelButtonText:  'Cancelar',
    allowOutsideClick:false,
    preConfirm: (text) => {

      if(text != ""){
          getApiAtualizaStatus(id_status_destino,tickets_id,text);
          return true;
        }else{
          Swal.showValidationMessage(
            `Informe a solução do chamado...`
          )
        }

     
    },
    inputAttributes: {
      'aria-label': 'Type your message here'
    },
    showCancelButton: true,
    showConfirmButton: true
    
  }).then((result) => {
      console.log(result);
      if (result.dismiss =="cancel") {
        window.location.reload();
         return false;
        }
  })
  

}



async function getApiAtualizaStatus(id_status_destino,tickets_id,text_solution){

  $.ajax
  ({
      type: 'POST',
      dataType: 'html',
      url: '../ajax/apiTicket.php',          
      data: {"requisicao":"update_status","id_status" : id_status_destino,"tickets_id" : tickets_id,"text_solution" : text_solution},
      success: function(retorno) 
      {   
          return true;

      },
      error: function(error)
      { 
          $('#error',error);
          return false;
      }
  });
}

