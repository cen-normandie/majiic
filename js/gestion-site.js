var docref_actif_ ='';
var p_id_site_ ='';
var p_ddg_ ='';
var arr_doc_ref = new Array();
var list_doc_ref = new Array();
var directory_ = "majiic";
var i=0;
var last_insee ='';
var last_prefixe ='';
var last_section ='';


//////////////////////////////////
// DECLARATION DES AUTOCOMPLETIONS
$( "#doc_reference_autocomplete" ).autocomplete({
  //source: availableTags,
  source: function( request, response ) {
    $.ajax( {
      method   : "POST",
      url: "php/ajax/load_liste_convention_acquisition.js.php",
      dataType : "json",
      data: {
        term: request.term
      },
      success: function( data ) {
        var JSON_values_completed = [];
        $.each(data, function (index, value) {
            var id= value.split(' - ')[0];
            var nom= value.split(' - ')[1];
            //values_completed.items.push({label: nom_site, value: id_site, geometry: geom_site});
             JSON_values_completed.push({
                label: id + ' - ' + nom,
                value: id + ' - ' + nom
            });
        })
        response(JSON_values_completed);
      }
    } );
  },
  minLength : 3,
  select: function AutoCompleteSelectHandler(event, ui)
    {
        //event.preventDefault();
        docref_actif_ = ui.item.value.split(' - ')[0];
        $("#docref_actif").text(docref_actif_);
        $('#out_doc').removeClass("d-none");
    }
});

$('#site_data_autocomplete').on('input',function(e){
  document.getElementById("site_data_autocomplete").classList.remove('is-valid');
});
$( "#site_data_autocomplete" ).autocomplete({
      //source: availableTags,
      source: function( request, response ) {
        $.ajax( {
          method   : "POST",
          url: "php/ajax/search_autocomplete_site.js.php",
          dataType : "json",
          data: {
            term: request.term
          },
          success: function( data ) {
            var JSON_values_completed = [];
            $.each(data, function (index, value) {
                var id_site= value.split(' - ')[0];
                var nom_site= value.split(' - ')[1];
                //values_completed.items.push({label: nom_site, value: id_site, geometry: geom_site_centroid});
                 JSON_values_completed.push({
                    label: id_site + ' - '+nom_site,
                    value: id_site + ' - '+nom_site
                });
            })
            response(JSON_values_completed);
          }
        } );
      },
      minLength : 3,
      select: function AutoCompleteSelectHandler(event, ui)
        {               
            var name = ui.item.value;
            //console.log(ui.item);
            var id_site = name.split(' - ')[0];
            p_id_site_ = ui.item.value.split(' - ')[0];
            $("#site_actif").text(p_id_site_);
            $('#out_site').removeClass("d-none");
            $('#site_data_autocomplete').addClass("is-valid");
            get_data_parcelle_for_update(id_site);
            
        }
    });
$('#site_doc_autocomplete').on('input',function(e){
  document.getElementById("site_doc_autocomplete").classList.remove('is-valid');
});
$( "#site_doc_autocomplete" ).autocomplete({
      //source: availableTags,
      source: function( request, response ) {
        $.ajax( {
          method   : "POST",
          url: "php/ajax/search_autocomplete_site.js.php",
          dataType : "json",
          data: {
            term: request.term
          },
          success: function( data ) {
            var JSON_values_completed = [];
            $.each(data, function (index, value) {
                var id_site= value.split(' - ')[0];
                var nom_site= value.split(' - ')[1];
                var geom_site_centroid= value.split(' - ')[2];
                //values_completed.items.push({label: nom_site, value: id_site, geometry: geom_site_centroid});
                 JSON_values_completed.push({
                    label: id_site + ' - '+nom_site,
                    value: id_site + ' - '+nom_site
                });
            })
            response(JSON_values_completed);
            $('#site_doc_autocomplete').addClass("is-valid");
          }
        } );
      },
      minLength : 3,
      select: function AutoCompleteSelectHandler(event, ui)
        {               
            var name = ui.item.value;
            //console.log(ui.item);
            var id_site = name.split(' - ')[0];
            p_id_site_ = ui.item.value.split(' - ')[0];
            $("#site_actif").text(p_id_site_);
            $('#out_site').removeClass("d-none");
        }
    });
$( "#ddg_autocomplete" ).autocomplete({
      //source: availableTags,
      source: function( request, response ) {
        $.ajax( {
          method   : "POST",
          url: "php/ajax/search_autocomplete_ddg.js.php",
          dataType : "json",
          data: {
            term: request.term
          },
          success: function( data ) {
            var JSON_values_completed = [];
            $.each(data, function (index, value) {
                //var id_site= value.split(' - ')[0];
                //var nom_site= value.split(' - ')[1];
                var id_ddg= data[index]['id_doc_gestion']
                var nom_ddg= data[index]['nom_doc_gestion']
                 JSON_values_completed.push({
                    label: id_ddg,
                    value: nom_ddg
                });
            })
            response(JSON_values_completed);
          }
        } );
      },
      minLength : 3,
      select: function AutoCompleteSelectHandler(event, ui)
        {               
            var name = ui.item.value;
            //console.log(ui.item);
            p_ddg_ = ui.item.value;
            $("#ddg_actif").text(p_ddg_);
            $('#out_ddg').removeClass("d-none");
        }
    });

$( "#id_site_autre_doc" ).autocomplete({
      //source: availableTags,
      source: function( request, response ) {
        $.ajax( {
          method   : "POST",
          url: "php/ajax/search_autocomplete_site.js.php",
          dataType : "json",
          data: {
            term: request.term
          },
          success: function( data ) {
            var JSON_values_completed = [];
            $.each(data, function (index, value) {
                var id_site= value.split(' - ')[0];
                var nom_site= value.split(' - ')[1];
                //values_completed.items.push({label: nom_site, value: id_site, geometry: geom_site_centroid});
                 JSON_values_completed.push({
                    label: id_site + ' - '+nom_site,
                    value: id_site
                });
            })
            response(JSON_values_completed);
          }
        } );
      },
      minLength : 3,
      select: function AutoCompleteSelectHandler(event, ui)
        {               
            var name = ui.item.value;
        }
    });



$( "#site_autocomplete_todelete" ).autocomplete({
    //source: availableTags,
    source: function( request, response ) {
      $.ajax( {
        method   : "POST",
        url: "php/ajax/search_autocomplete_site.js.php",
        dataType : "json",
        data: {
          term: request.term
        },
        success: function( data ) {
          var JSON_values_completed = [];
          $.each(data, function (index, value) {
              var id_site= value.split(' - ')[0];
              var nom_site= value.split(' - ')[1];
               JSON_values_completed.push({
                  label: id_site + ' - '+nom_site,
                  value: id_site + ' - '+nom_site
              });
          })
          response(JSON_values_completed);
        }
      } );
    },
    minLength : 3,
    select: function AutoCompleteSelectHandler(event, ui)
      {
          //var name = ui.item.value;
          //console.log(ui.item);
          //var id_site = name.split(' - ')[0];
      }
});




function get_data_parcelle_for_update (id_site) {
    var id_site_ = id_site;
    //requete sur le parcellaire
    $.ajax({
        url      : "php/ajax/load_data_parcelles_for_update.js.php",
        data     : {id_site:id_site},
        method   : "POST",
        dataType : "json",
        async    : false,
        //error    : function(request, error) { alert("Erreur : responseText: "+request.responseText);},
        error    : function(request, error) { alert("Erreur : responseText: "+request.responseText);},
        success  : function(data) {
            var count_parcelles = 0;
            $('#site_parcelles_content').html('');
            i = 0;
            $(data.parcelles).each(function(key, values) {
                        var doc_gestion ="";
                        $('#tab_logic').append('<tr id="addr'+(i)+'"></tr>');
                        if(values.properties.id_doc_gestion == "null") {doc_gestion = "";};
                        $('#addr'+i).html(
                            "<td>"+ (i+1) +"</td>"+
                            "<td><input id='p_insee"+i+"' type='text' placeholder='14124' class='form-control input-sm' value=\""+values.properties.insee+"\" ></input></td>"+
                            "<td><input id='p_prefixe"+i+"' type='text' placeholder='000' class='form-control input-sm' value=\""+values.properties.prefixe+"\" ></input></td>"+
                            "<td><input id='p_section"+i+"' type='text' placeholder='AH'  class='form-control input-sm' value=\""+values.properties.section+"\"></input></td>"+
                            "<td><input id='p_num"+i+"' type='text' placeholder='124'  class='form-control input-sm'    value=\""+values.properties.numero+"\"></input></td>"+
                            "<td><input id='pp_"+i+"' type='checkbox' class='form-check-input' "+ (values.properties.pp ? 'checked' : '') +"></input></td>"+
                            "<td><p id='p_pci_v"+i+"'>oui</p></td>"+
                            "<td><input id='p_conv"+i+"' type='text' class='form-control input-sm' value=\""+values.properties.id_convention+"\"></input></td>"+
                            "<td><input id='p_acqu"+i+"' type='text' class='form-control input-sm' value=\""+values.properties.id_acquisition+"\"></input></td>"+
                            "<td><input id='p_site"+i+"' type='text' class='form-control input-sm' value=\""+values.properties.id_site+"\"></input></td>"+
                            "<td><input id='p_ddg"+i+"' type='text' class='form-control input-sm' value=\""+values.properties.id_doc_gestion+"\"></input></td>"+
                            "<td><input id='p_ore"+i+"' type='text' class='form-control input-sm' value=\""+values.properties.id_ore+"\"></input></td>"
                            );
                        //$('#tab_logic').append('<tr id="addr'+(i+1)+'"></tr>');
                        //$('#p_doc_reference'+i).val( $('#n_doc_reference').val() );
                        
                        $('#p_site'+i).val(p_id_site_);
                        
                        i++;
            });
       }
    });
};




/* $("#save_site").click(function(){
    var i_p = 0;
    $('#site_parcelles_content > tr').each(function() {
        var fd = new FormData();
        fd.append('count_parcelle', i_p);
        fd.append('p_insee',                $("#p_insee"+i_p).val());
        fd.append('p_prefixe',              $("#p_prefixe"+i_p).val());
        fd.append('p_section',              $("#p_section"+i_p).val());
        fd.append('p_num',                  $("#p_num"+i_p).val());
        if ($("#pp_"+i_p).is(':checked')) {
            fd.append('pp_',                    'true');
        } else {
            fd.append('pp_',                    'false');
        }
        fd.append('p_conv',               $("#p_conv"+i_p).val());
        fd.append('p_acqu',               $("#p_acqu"+i_p).val());
        fd.append('p_site',                 $("#p_site"+i_p).val());
        fd.append('p_ddg',                 $("#p_ddg"+i_p).val());
        fd.append('p_ore',                 $("#p_ore"+i_p).val());
        $.ajax({
        url      : "php/ajax/save_parcelles_in_table_2026.js.php",
        type     : 'POST',
        data     : fd ,
        processData : false,
        contentType : false,
        async    : false,
        error    : function(request, error) { alert("Erreur : responseText: "+request.responseText);},
        success  : function(data) {
                if (data == 1) {
                    
                } else {
                    alert(data);
                    
                }
            }
        });
        i_p++;
    });
}); */

$("#save_site").click(function() {
    var i_p = 0;
    $('#site_parcelles_content > tr').each(function() {
        var fd = new FormData();
        fd.append('count_parcelle', i_p);
        fd.append('p_insee', $("#p_insee" + i_p).val());
        fd.append('p_prefixe', $("#p_prefixe" + i_p).val());
        fd.append('p_section', $("#p_section" + i_p).val());
        fd.append('p_num', $("#p_num" + i_p).val());
        if ($("#pp_" + i_p).is(':checked')) {
            fd.append('pp_', 'true');
        } else {
            fd.append('pp_', 'false');
        }
        fd.append('p_conv', $("#p_conv" + i_p).val());
        fd.append('p_acqu', $("#p_acqu" + i_p).val());
        fd.append('p_site', $("#p_site" + i_p).val());
        fd.append('p_ddg', $("#p_ddg" + i_p).val());
        fd.append('p_ore', $("#p_ore" + i_p).val());

        $.ajax({
            url: "php/ajax/save_parcelles_in_table_2026.js.php",
            type: 'POST',
            data: fd,
            processData: false,
            contentType: false,
            async: false,
            error: function(request, error) {
                alert("Erreur : responseText: " + request.responseText);
            },
            success: function(data) {
                console.log(data); // Affiche les requêtes SQL dans la console
                if (data.includes("réussie")) {
                    // Succès
                } else if (!data.includes("Check SQL:") && !data.includes("Update SQL:") && !data.includes("Insert SQL:")) {
                    alert(data);
                }
            }
        });
        i_p++;
    });
});



$("#delete_site").click(function(){
    
    var sure_ = confirm("Êtes-vous certains de vouloir supprimer ce site ?");
    if (sure_) {
        var id_sitedelete = $("#site_autocomplete_todelete").val().split(' - ')[0];
        //console.log(id_sitedelete);
        var fd = new FormData();
        fd.append('id_site', id_sitedelete );
        $.ajax({
        // chargement du fichier externe monfichier-ajax.php
        url      : "php/ajax/delete_site.js.php",
        type     : 'POST',
        data     : fd ,
        processData : false,
        contentType : false,
        async    : false,
        error    : function(request, error) { alert("Erreur : responseText: "+request.responseText);},
        success  : function(data) {
                console.log(data);
                alert("Le site et les parcelles rattachées ont été supprimé");
                refresh_page();
                }
        });
    }
});

// Ajoute un site dans la table site (uniquement l'id
$("#add_site_liste").click(function(){
        var fd = new FormData();
        fd.append('new_site_id', $("#new_site_id").val());
        $.ajax({
        url      : "php/ajax/add_site_liste.js.php",
        type     : 'POST',
        data     : fd ,
        processData : false,
        contentType : false,
        async    : false,
        error    : function(request, error) { alert("Erreur : responseText: "+request.responseText);},
        success  : function(data) {
                console.log(data);
                }
        });
});


$(document).ready(function(){
    $("#add_row").click(function(){
        
        var fd = new FormData();
        fd.append('p_insee'         , $("#p_insee"+(i-1)   ).val());
        fd.append('p_prefixe'       , $("#p_prefixe"+(i-1) ).val());
        fd.append('p_section'       , $("#p_section"+(i-1) ).val());
        fd.append('p_num'           , $("#p_num"+(i-1)     ).val());
        
        if (i > 0) {
            last_insee = $("#p_insee"+(i-1)   ).val();
            last_prefixe = $("#p_prefixe"+(i-1) ).val();
            last_section = $("#p_section"+(i-1) ).val();
        };
        
        $.ajax({
        // chargement du fichier externe monfichier-ajax.php
        url      : "php/ajax/check_parcelle.js.php",
        type     : 'POST',
        data     : fd ,
        processData : false,
        contentType : false,
        async    : false,
        error    : function(request, error) { alert("Erreur : responseText: "+request.responseText);},
        success  : function(data) {
                    $("#p_pci_v"+(i-1)).text(data);
                }
        });
        $('#tab_logic').append('<tr id="addr'+(i)+'"></tr>');
        $('#addr'+i).html(
            "<td>"+ (i+1) +"</td>"+
            "<td><input id='p_insee"+i+"' type='text' placeholder='14124' class='form-control input-sm' value='"+((last_insee == '') ? '' : last_insee)+"' /> </td>"+
            "<td><input id='p_prefixe"+i+"' type='text' placeholder='000' class='form-control input-sm' value='"+((last_prefixe == '') ? '' : last_prefixe)+"' /> </td>"+
            "<td><input id='p_section"+i+"' type='text' placeholder='AH'  class='form-control input-sm' value='"+((last_section == '') ? '' : last_section)+"' ></td>"+
            "<td><input id='p_num"+i+"' type='text' placeholder='124'  class='form-control input-sm'></td>"+
            "<td><input id='pp_"+i+"' type='checkbox' class='form-check-input'/></td>"+
            "<td><p id='p_pci_v"+i+"'></p></td>"+
            "<td><input id='p_conv"+i+"' type='text' class='form-control input-sm' value='ø' ></td>"+
            "<td><input id='p_acqu"+i+"' type='text' class='form-control input-sm' value='ø' ></td>"+
            "<td><input id='p_site"+i+"' type='text' class='form-control input-sm' value='ø' ></td>"+
            "<td><input id='p_ddg"+i+"' type='text' class='form-control input-sm' value='ø'></td>"+
            "<td><input id='p_ore"+i+"' type='text' class='form-control input-sm' value='ø'></td>"
            );
        //$('#tab_logic').append('<tr id="addr'+(i+1)+'"></tr>');
        //$('#p_doc_reference'+i).val( $('#n_doc_reference').val() );
        $('#p_docref'+i).val(docref_actif_);
        
        switch (docref_actif_) {
            
        }
        switch(docref_actif_.substring(0, 3)) {
            case "GES":
                $('#p_conv'+i).val(docref_actif_);
                break;
            case "ACQ":
                $('#p_acqu'+i).val(docref_actif_);
                break;
            case "ORE":
                $('#p_ore'+i).val(docref_actif_);
                break;
            default:
                break;
        }
        
        
        $('#p_site'+i).val(p_id_site_);
        if (p_ddg_ !== '') {
            $('#p_ddg'+i).val(p_ddg_);
        }
        
        i++;  
        
    });
    $("#delete_row").click(function(){
        if(i>0){
            $("#addr"+(i-1)).remove();
            i--;
            console.log(i);
        }
    });
});


