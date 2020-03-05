
//Function that add entity in array if not exist
//Use selected_layer = ""; entity_selected_array = {};
function add_to_selection(id_entity, id_layer) {
    //constitution de l'identifiant unique --> NomDossier_Identifiant
    var dirLayer_id_entity = (id_layer.split("|")[0])+"____"+id_entity
        //Test IF ENTITY is in the list
        if ($.inArray(dirLayer_id_entity,list_entity_id) == -1) {
            //Entity is NOT here ADD from list
            list_entity_id.push(dirLayer_id_entity);
            console.log("push " + dirLayer_id_entity);
            
        } else {
            //Entity is here REMOVE from list
            list_entity_id.splice(list_entity_id.indexOf(dirLayer_id_entity),1);
            console.log("splice " + dirLayer_id_entity);
        }
    console.log(list_entity_id);
    load_selected_from_array(list_entity_id);
}




//fonction recharger la liste des ellements selectionnés en fonction de l'identifiant
// exemple : com_14001 va charger dans le dossier com le fichier 14001.json
function load_selected_from_array (array_entities) {
    selection_layer.clearLayers();
    array_entities.forEach(function(dirLayer_id_entity)
    {
        var dir_layer = dirLayer_id_entity.split("____")[0];
        var id_entity = dirLayer_id_entity.split("____")[1];
        //requete ajax pour charger le geojson correspondant à la commune cliquee
        // ajoute cette commune à la couche de selection --> selection_layer
        $.ajax({
        dataType: "json",
        url: "geojson/"+dir_layer+"/"+id_entity+".json",
        success: function(data) {
            $(data.features).each(function(key, data) {
                selection_layer.addData(data);
            });
        }
        }).error(function() {
            //File not found... --> delete from list and reload
            console.log("File not found, entity came from another layer");
        });
    }
    );
}




function save_geojson_to_pg (geojson_obj, analyse_or_draw, str_name ) {
    //type_of_save --> "analyse" or "specific_area"
    var type_of_save = analyse_or_draw;
    var php_file = "-_-";
    console.log(geojson_obj+'+'+analyse_or_draw+'+'+str_name);
    $.ajax({
        type: "POST",
        url: "php/save_geojson.js.php",
        async: false,
        datatype: "text",
        data: {temp_geoson: geojson_obj , temp_name: str_name, area_type : analyse_or_draw },
        start: function() {$("#ajax_save_geojson").css("visibility", "visible");},
        success : function(data) {
            if (data == "true") {
                console.log("saved");
            } else {
                console.log("not saved");
            }
        $("#ajax_save_geojson").css("visibility", "hidden");
        }
    });
}




function display_active_layer_on_map(id_layer_to_remove,active_layer) {
    //supprime la couche active
    map.removeLayer(l_from_geojson_object[id_layer_to_remove]);
    //ajoute la couche active à la carte
    l_from_geojson_object[active_layer].addTo(map);
    //zoom et centre la carte sur la couche selectionnee
    map.fitBounds(l_from_geojson_object[active_layer].getBounds());
}




//EVENT FOR ELEMENT EXCEPT MAP
// Button event0
function get_selection_to_send () {
    if (list_entity_id.length > 0) {
        if ($('#analyseName').attr("value") != "") {
            //L'utilisateur fait une nouvelele analyse
                for (x in list_entity_id) {
                    var dir_layer = list_entity_id[x].split("____")[0];
                    var id_entity = list_entity_id[x].split("____")[1];
                    
                    $.ajax({
                        url:'geojson/'+dir_layer+'/'+id_entity+'.json',
                        async: false,
                        type: "POST",
                        datatype: "text",
                        error: function()
                        {
                            //file not exists
                            alert("L'entité suivante '"+id_entity+"' ne fait pas parti du réseau de mare...\nVeuillez supprimer et rajouter cette entité en cliquant dessus.");
                        },
                        success: function(data)
                        {
                            //file exists
                            save_geojson_to_pg(JSON.stringify(data) , "analyse", $('#analyseName').attr('value'));//JSON.stringify(
                        }
                    });
                }
            
            
            //efface les geometrie temporaires sauvegardées dispatche soit dans analyse soit dans zones specifiques
            delete_temp_entities_db();
            
            //enregistre la liste des entités dans la table users.analyse dans le champ composition
            var entities_str = '';
            for (x in list_entity_id) {
                    var dir_layer = list_entity_id[x].split("____")[0];
                    var id_entity = list_entity_id[x].split("____")[1];
                    var dir_nom_couche = '';
                    
                    switch (dir_layer) {
                        case "com":
                            dir_nom_couche = 'Communes';
                            break;
                        case "zhy":
                            dir_nom_couche = 'Zone Hydrographique de l\'IGN';
                            break;
                        case "epci":
                            dir_nom_couche = 'EPCI';
                            break;
                        default:
                            dir_nom_couche = 'Zone spécifique';
                    }
                    
                    entities_str += id_entity+'|'+ dir_layer+'||';
            }
            console.log(entities_str);
            save_composition_str(entities_str);
            
            
            //Créé et envoi un formulaire pour transmettre des variables et rediriger vers analyse 
            var url = 'analyse.php';
            console.log($('#user_name').val()+' '+$('#id_user').val()+' '+$('#analyseName').val());
            var form = $(
            '<form action="' + url + '" method="post">' +
            '<input type="text" name="user_name"       class="hide" value="' + $('#user_name').text()    + '" />' +
            '<input type="text" name="id_user"         class="hide" value="' + $('#id_user').text()      + '" />' +
            '<input type="text" name="id_nom_analyse"  class="hide" value="' + $('#analyseName').val()   + '" />' +
            '<input type="text" name="password"        class="hide" value="' + $('#password').val()      + '" />' +
            '</form>');
            $('body').append(form);
            form.submit();
        }
        else {
            //Name for analyse is empty
            alert('Donnez un nom a votre analyse !')
        }
    } else {
        //array selection is empty
        alert('Aucune entité sélectionnée !!')
    }
}

function save_composition_str (entities_list) {
    $.ajax({
            url:'php/save_composition_analyse.js.php',
            async: false,
            type: "POST",
            data: {str: entities_list , id_user: $('#id_user').text(), id_nom_analyse : $('#analyseName').val() },
            datatype: "text",
            error: function()
            {
                alert('enregistrement impossible');
            },
            success: function(data)
            {
                //console.log('composition updated.');
            }
        });
}


function display_drawing_items () {
    //Drawing items are not visible
    if (drawing == false) {
        map.addControl(drawControl);
        drawing = true;
        $('#drawundraw').attr("value", "Désactiver le dessin");
        //Affiche le bouton de sauvegarde du dessin
        $('#send_drawn_items').removeClass("invisible");
        $('#send_drawn_items').addClass("visible");
        console.log(drawnItems);
    }
    //Drawing items are visible
    else {
        map.removeControl(drawControl);
        drawing = false;
        $('#drawundraw').attr("value", "Dessiner une zone spécifique");
        //Cache le bouton de sauvegarde du dessin
        $('#send_drawn_items').removeClass("visible");
        $('#send_drawn_items').addClass("invisible");
        drawnItems.clearLayers();
    }
};




// Drop down list
var value_selected_layer;
var previous = 1;
$("#select_layer").change(function() {
    // Do something with the previous value after the change
    value_selected_layer = $(this).children(":selected").attr("id");
    display_active_layer_on_map(previous,value_selected_layer);
    // Make sure the previous value is updated
    previous = this.value;
    if (previous > 3) {
        $("#remove_layer").removeClass("invisible");
        $("#remove_layer").addClass("visible");
    } else {
        $("#remove_layer").removeClass("visible");
        $("#remove_layer").addClass("invisible");
    }
});




function store_temp_drawing() {
//fonction ajoutant le dessin dans postgis
//cree un nouvel objet l_array() contenant
// ajoute une option dans le select
    
    //console.log(drawnItems._layers);
    
    if ($('#drawName').attr("value") != "") {
        if (drawnItems.getBounds().isValid() ) {
            //save_geojson_to_pg(to_geojson(drawnItems._layers) , "specific_area");
            console.log("draw");
            //console.log(JSON.stringify(drawnItems.toGeoJSON() ));
            save_geojson_to_pg(  JSON.stringify(drawnItems.toGeoJSON() ) , "draw", $("#drawName").val() );
            
            var new_key = 0;
            //boucle pour avoir le dernier id du select
            for (r_key in l_array) {
                new_key = l_array[r_key].l_id;
                //console.log(l_array[r_key].l_id);
            }
            new_key += 1;
            console.log('KEY :'+new_key);
            var t_id_user = $("#id_user").text();
            var t_nom_zone = $("#drawName").val();
            $("#select_layer").append(
                "<option id='" + parseInt(new_key) +"' name='"+t_id_user+"|"+t_nom_zone+"' value='"+parseInt(new_key)+"'>"+t_nom_zone+"</option>");
                console.log("append layer in select : "+parseInt(new_key));
                l_array[parseInt(new_key)] = new ObjArrayLayers(
                    parseInt(new_key)                                   ,//l_id   = unique_id_layer;
                    t_id_user                                           ,//l_dir  = dir_layer;
                    t_nom_zone                                          ,//l_name = name_layer;
                    null                                                ,//l_geoj = geojson;
                    t_nom_zone                                          ,//l_display_select = display;
                    t_id_user+"|"+t_nom_zone                             //l_unique_name
                );
            //vide la couche de dessin
            drawnItems.clearLayers();
            //remove and add drawnitems
            map.removeLayer(drawnItems);
            //rajoute la couche vide
            map.addLayer(drawnItems);
            
            //DELETE temp_entities
            delete_temp_entities_db();
            
            //drawnItems.toGeoJSON() is not necessary
            //read from file to get properties : ID, NOM & NB_MARES
            console.log("geojson/"+l_array[new_key].l_dir+"/"+l_array[new_key].l_name+".json");
            $.ajax({
                    url: ("geojson/"+l_array[new_key].l_dir+"/"+l_array[new_key].l_name+".json"),
                    dataType: 'json',
                    async: false,
                    start: function() {
                        console.log("start");
                        },
                    success: function(data) {
                        l_array[new_key].l_geoj = data;
                        console.log("get data :");
                        console.log(l_array[new_key].l_geoj);
                        create_layer_with_geojson (
                                            l_array[new_key].l_geoj, 
                                            parseInt(new_key), 
                                            l_array[new_key].l_unique_name
                                            )
                    }
                });
            display_drawing_items();
        } else //la couche de dessin est vide pas de polygon dessine....
        {
            alert('Veuillez dessiner un polygone avant d\'enregistrer une zone');
        }
    } else //Aucun nom n'est donne a la zone specifique
    {
        alert('Veuillez donner un nom à votre zone specifique');
    }
};




function delete_specific_area_from_list() {
    //supprime la zone specifique du select
    if (previous > 3) {
    //display
    display_active_layer_on_map(previous,1);
    $.ajax({
        type: "POST",
        url: 'php/delete_specific_area.js.php',
        async: false,
        datatype: "text",
        data: { id_to_delete: $("#select_layer").children(":selected").attr("id") },
        start: function() {$("#ajax_save_geojson").css("visibility", "visible");},
        success : function(data) {
            console.log(data);
            $("#select_layer").children(":selected").remove();
            $("#ajax_save_geojson").css("visibility", "hidden");
        }
    });
    // A AJOUTER --> supprimer les json correspondant sur le serveur
    
    } else {
        alert('Vous ne pouvez pas supprimer ces couches');
    }
}




function delete_temp_entities_db () {
    //Function vidant la table temporaire des geometrie et les ajoutant soit dans analyse soit dans zone_specifique
    // genere egalement un geojson sur le serveur dans le dossier correspondant aux geojson de lutilisateur
    $.ajax({
        url:'php/delete_temp_entities.js.php',
        async: false,
        type: "POST",
        datatype: "text",
        success: function(data)
        {
            //console.log('geojson + deleted : '+data);
            //console.log('deleted');
        }
    });
}





