//INIT VAR
let projets = '';//projets all (1er chargement)
let projets_f = '';//projets filtrés
let actions = '';//actions all (1er chargement)
let actions_f = '';//actions filtrés

//EVENT ON SWITCH
//$('#2021').change(function() {filters_active["2021"] = ( $(this).prop('checked') );apply_filters();});
//$('#2022').change(function() {filters_active["2022"] = ( $(this).prop('checked') );apply_filters();});
//...
//$('#all').change(function() {clear_dep_filter ();apply_filters();});

//CALL DATA PROJETS
//Le json de l'ensemble des projets sera contenu dans la variable "projets"
//
function load_projets_ajax () {
    $.ajax({
        url      : "php/ajax/analytique/projets_user.ajax.js.php",
        data     : {},
        method   : "POST",
        dataType : "json",
        async    : true,
        error    : function(request, error) { alert("Erreur : responseText: "+request.responseText);},
        success  : function(data) {
            projets = data ;
            
            //Ajoute les projets dans le select 1 fois
            let projets_array = [];
            for (const projet in projets) {
                projets_array.push(projets[projet].name+' | '+projets[projet].id);
            }
            projets_array.sort();
            for (const projet_ in projets_array) {
                $("#input_projet").append($('<option>', {
                    value: projets_array[projet_],
                    text: projets_array[projet_]
                }));
                $("#update_input_projet").append($('<option>', {
                    value: projets_array[projet_],
                    text: projets_array[projet_]
                }));
            }
            //Chargement des actions
            load_actions_ajax();
            }
    });
}
function load_actions_ajax () {
    $.ajax({
        url      : "php/ajax/analytique/actions_user.ajax.js.php",
        data     : {},
        method   : "POST",
        dataType : "json",
        async    : true,
        error    : function(request, error) { alert("Erreur : responseText: "+request.responseText);},
        success  : function(data) {
            actions = data ;
            apply_filters();
            change_load();
            
            //Filtre pour le 1er chargement
            keys["id_projet"][0] = $('#input_projet').val().split(' | ')[1];
            filters_active["id_projet"] = true;
            apply_filters();
            
            }
    });
}



//APPLY FILTERS ON CORRECT FIELD
function apply_filters() {
    projets_f = projets;
    actions_f = actions;
    for (const property in filters_active) {
        if(filters_active[property]) {
            //console.log(`${property}: ${filters_active[property]}`);
            projets_f = filtre_obj(projets_f, property);
            actions_f = filtre_obj(actions_f, property);
        }
    }
    //update_projet(projets_f);
    update_action(actions_f);
};

function update_projet(projets_json) {

}




function update_action(actions_json) {
    let actions_array = [];
    for (const action in actions_json) {
        actions_array.push(actions_json[action].code_action+' | '+actions_json[action].id_action+' | '+actions_json[action].site+ ' reste '+actions_json[action].diff+' heures');
        //affiche nom_action | id_action | site | diff heures
        //actions_array.push(actions_json[action].code_action+' | '+actions_json[action].id_action+' | '+actions_json[action].site+ ' reste '+actions_json[action].diff+' heures');
    }
    actions_array.sort();
    //Clear select action before add
    $('#input_action').empty();
    $('#update_input_action').empty();
    //Add options
    for (const action_ in actions_array) {
        $("#input_action").append($('<option>', {
            id:actions_array[action_].split(' | ')[1],
            value: actions_array[action_].split(' | ')[0]+' | '+actions_array[action_].split(' | ')[1],
            text: actions_array[action_]
        }));
        $("#update_input_action").append($('<option>', {
            id:actions_array[action_].split(' | ')[1],
            value: actions_array[action_].split(' | ')[0]+' | '+actions_array[action_].split(' | ')[1],
            text: actions_array[action_]
        }));
    }
}
change_load("Chargement des données");


//Listen Select to filter projets & actions
$('#input_projet').on('change', function() {
  keys["id_projet"][0] = $('#input_projet').val().split(' | ')[1];
  filters_active["id_projet"] = true;
  //filtre les données
  apply_filters();
});
$('#update_input_projet').on('change', function() {
  keys["id_projet"][0] = $('#update_input_projet').val().split(' | ')[1];
  filters_active["id_projet"] = true;
  //filtre les données
  apply_filters();
});
//Listen objet input update in modal
$("#input_objet").on("input", function() {
    ( ($("#input_objet").val() == '') ? $("#help").addClass('visible_s') : $("#help").removeClass('visible_s'));
    $('#event_title').text($("#input_objet").val());
    $('#event_title').attr('title',$("#input_objet").val());
});
// Listen fro save update event 
$('#update_e').on('click', function() {
    let event = calendar.getEventById( $('#update_id_e').html() );
    add_update_event_properties(event,event._def.publicId);
    save_update_event(event);
    calendar.refetchEvents();
    modal_edit.hide()
});
// Listen from del update event 
$('#delete_e').on('click', function() {
    let event = calendar.getEventById( $('#update_id_e').html() );
    delete_event(event._def.publicId);
    calendar.refetchEvents();
    modal_edit.hide()
});




//////////////////////////////////////////////MODAL
let modal_edit = new bootstrap.Modal(document.getElementById('ModalEditEvent'), {
  keyboard: false
})


//////////////////////////////////////////////CALENDAR
//Calendar
var calendarEl = document.getElementById('calendar');
var calendar = new FullCalendar.Calendar(calendarEl, {
  timeZone:'UTC',  
  headerToolbar: {
    left: 'prev,next today',
    center: 'title',
    right: 'dayGridMonth,timeGridWeek'
  },
  editable: true,
  droppable: true, // this allows things to be dropped onto the calendar
  drop: function(arg) {},
  forceEventDuration:true,
  initialView: 'timeGridWeek',
  locale: 'fr',
  slotDuration: '00:30:00',
  slotLabelInterval: 30,
  slotMinTime: '04:00:00', // Start time for the calendar
  slotMaxTime: '24:00:00', // End time for the calendar
  allDaySlot: true,
  editable: true,
  droppable: true, // this allows things to be dropped onto the calendar
  selectable: true,
  eventReceive: function (event) {
      get_uuid();
      add_event_properties(event.event);
      save_event(event.event);
      event.event.remove();
      calendar.refetchEvents();
  },
  eventDrop: function (arg) {
      arg.event.e_start = arg.event.start;
      arg.event.e_end = arg.event.end;
      arg.event.e_uuid = arg.event._def.publicId;
      save_update_event_resized (arg.event);
      calendar.refetchEvents();
  },
  eventClick: function(arg) {
    modal_edit.show();
    $("#update_id_e").html(arg.event._def.publicId);
    $('#update_input_objet').val(arg.event._def.extendedProps.e_objet);
    $('#update_input_projet').val(arg.event._def.extendedProps.e_nom_projet+' | '+arg.event._def.extendedProps.e_id_projet);
    $('#input_projet').val(arg.event._def.extendedProps.e_nom_projet+' | '+arg.event._def.extendedProps.e_id_projet);

    // Choisir l'option du select avec value = nom_action | id_action
    //--> get the option by value

    //$('#update_input_action').val(arg.event._def.extendedProps.e_nom_action+' | '+arg.event._def.extendedProps.e_id_action);

        //Mets à jour les actions liées au projet
        //Same like select input projet change
        if ( ($('#update_input_projet').val() ) !== null) {
            keys["id_projet"][0] = $('#update_input_projet').val().split(' | ')[1];
            filters_active["id_projet"] = true;
            //filtre les données
            apply_filters();
        }
        console.log(arg.event._def.extendedProps.e_id_action);
        $('#input_action option[id="'+arg.event._def.extendedProps.e_id_action+'"]').attr("selected", "selected");
        $('#update_input_action option[id="'+arg.event._def.extendedProps.e_id_action+'"]').attr("selected", "selected");
        //$('#update_input_action').val(arg.event._def.extendedProps.e_nom_action+' | '+arg.event._def.extendedProps.e_id_action).attr("selected", "selected");

    //$('#update_input_action').val(arg.event._def.extendedProps.e_nom_action+' | '+arg.event._def.extendedProps.e_id_action);
    document.getElementById("update_input_panier").checked = (arg.event._def.extendedProps.e_panier == "t") ? true : false;
    document.getElementById("update_input_salissure").checked = (arg.event._def.extendedProps.e_salissure == "t") ? true : false;
    $('#update_input_commentaire').val(arg.event._def.extendedProps.e_commentaire);
    switch (arg.event._def.extendedProps.e_lieu) {
        case "Bureau":
            document.getElementById("update_lieu_bureau").checked = true;
            break;
        case "Réunion":
            document.getElementById("update_lieu_reunion").checked = true;
            break;
        case "Terrain":
            document.getElementById("update_lieu_terrain").checked = true;
            break;
        case "Télétravail":
            document.getElementById("update_lieu_teletravail").checked = true;
            break;
        case "Grève":
            document.getElementById("update_lieu_greve").checked = true;
            break;
        case "Modulation":
            document.getElementById("update_lieu_modulation").checked = true;
            break;
        default:
            document.getElementById("update_lieu_bureau").checked = true;
            break;
    };
    
    //calendar.render();
  },
  eventResize: function(arg) {
    arg.event.e_start = arg.event.start;
    arg.event.e_end = arg.event.end;
    arg.event.e_uuid = arg.event._def.publicId;
    save_update_event_resized (arg.event);
    calendar.refetchEvents();
  },
  events: "php/ajax/analytique/load_events_calendar.js.php"
});
calendar.render();





//configure External Event
var containerEl = document.getElementById('external-events-list');
new FullCalendar.Draggable(containerEl, {
  itemSelector: '.fc-event',
  eventData: function(eventEl) {
    return {
      title: eventEl.innerText.trim()
    }
  }
});

var lieu_event_update = "Bureau";
$(':radio[name="update_Lieux"]').change(function() {
    lieu_event_update = $(this).filter(':checked').val();
    //console.log(lieu_event_update);
});
var lieu_event = "Bureau";
$(':radio[name="Lieux"]').change(function() {
    lieu_event = $(this).filter(':checked').val();
    //console.log(lieu_event);
});

function add_event_properties (event) {
    //uuid
    event.e_uuid = new_uuid;
    //projet
    event.e_id_projet = $('#input_projet option:selected').text().split(' | ')[1];
    //nom projet
    event.e_nom_projet = $('#input_projet option:selected').text().split(' | ')[0];
    //action
    event.e_id_action = $('#input_action option:selected').attr('value').split(' | ')[1];
    //nom action
    event.e_nom_action = $('#input_action option:selected').attr('value').split(' | ')[0];
    //id_site
    //event.e_id_site = 'null';
    //objet
    event.e_objet = $('#input_objet').val();
    //start
    event.e_start = event._instance.range.start.toString().split(' GMT')[0];
    //event.e_start = event._instance.range.start;
    //end
    event.e_end = event._instance.range.end.toString().split(' GMT')[0];
    //lieu
    ///////////////////////////
    //const lieu = $("input[name='Lieux']:checked").val();
    //event.e_lieu = ( typeof lieu === 'undefined' ) ? 'null' : lieu;
    event.e_lieu = lieu_event;
    //commentaires
    event.e_commentaire = $("#input_commentaire").val() ;
    //personne
    //store in php var
    //nb_h
    //calcul entre start et end
    //date_saisie
    //pg --> now()
    //salissure
    event.e_salissure = $("#input_salissure:checked").is(':checked') ? true : false ;
    //panier
    event.e_panier =  $("#input_panier:checked").is(':checked') ? true : false ;
    //date_saisie_salissure
    //pg --> now() event.e_date_saisie_salissure
}
function add_update_event_properties (event,uuid_) {
    //uuid
    event.e_uuid = uuid_;
    //projet
    event.e_id_projet = $('#update_input_projet option:selected').text().split(' | ')[1];
    //nom projet
    event.e_nom_projet = $('#update_input_projet option:selected').text().split(' | ')[0];
    //action
    event.e_id_action = $('#update_input_action option:selected').attr('value').split(' | ')[1];
    console.log(event.e_id_action);
    //nom action
    event.e_nom_action = $('#update_input_action option:selected').attr('value').split(' | ')[0];
    console.log(event.e_nom_action);
    //id_site
    //event.e_id_site = 'null';
    //objet
    event.e_objet = $('#update_input_objet').val();
    //start
    event.e_start = event._instance.range.start.toString().split(' GMT')[0];
    //end
    event.e_end = event._instance.range.end.toString().split(' GMT')[0];
    //lieu
    /////////////////////////////
    //const lieu = $("input[name='update_Lieux']:checked").val();
    //event.e_lieu = ( typeof lieu === 'undefined' ) ? 'null' : lieu;
    event.e_lieu = lieu_event_update;
    //commentaires
    event.e_commentaire = $("#update_input_commentaire").val() ;
    //personne
    //store in php var
    //nb_h
    //calcul entre start et end
    //date_saisie
    //pg --> now()
    //salissure
    event.e_salissure = $("#update_input_salissure:checked").is(':checked') ? true : false ;
    //panier
    event.e_panier =  $("#update_input_panier:checked").is(':checked') ? true : false ;
    //date_saisie_salissure
    //pg --> now() event.e_date_saisie_salissure
}

function save_event (event) {
    console.log("Save event : ");
    console.log(event.e_id_action);
    console.log(event.e_nom_action);
    $.ajax({
        url      : "php/ajax/analytique/event/save_event.js.php",
        data     : {
            e_id_projet:event.e_id_projet,
            e_uuid:event.e_uuid,
            e_nom_projet:event.e_nom_projet,
            e_id_action:event.e_id_action,
            e_nom_action:event.e_nom_action,
            //e_id_site:event.e_id_site,
            e_objet:event.e_objet,
            e_start:event.e_start,
            e_end:event.e_end,
            e_lieu:event.e_lieu,
            e_commentaire:event.e_commentaire,
            //e_personne:event.e_personne,
            //e_nb_h:event.e_nb_h,
            //e_date_saisie:event.e_date_saisie,
            e_salissure:event.e_salissure,
            e_panier:event.e_panier
            //e_date_saisie_salissure:event.e_date_saisie_salissure
        },
        method   : "POST",
        dataType : "text",
        async    : true,
        error    : function(request, error) { alert("Erreur : responseText: "+request.responseText);},
        success  : function(data) {
            //reload() calendar;
            console.log(data);
            }
    });
    calendar.gotoDate(event.e_start);
}

function save_update_event (event) {
    console.log("Save update event : ");
    console.log(event.e_id_action);
    console.log(event.e_nom_action);
    $.ajax({
        url      : "php/ajax/analytique/event/update_event.js.php",
        data     : {
            e_id_projet:event.e_id_projet,
            e_uuid:event.e_uuid,
            e_nom_projet:event.e_nom_projet,
            e_id_action:event.e_id_action,
            //e_id_site:event.e_id_site,
            e_objet:event.e_objet,
            e_start:event.e_start,
            e_end:event.e_end,
            e_lieu:event.e_lieu,
            e_commentaire:event.e_commentaire,
            //e_personne:event.e_personne,
            //e_nb_h:event.e_nb_h,
            //e_date_saisie:event.e_date_saisie,
            e_salissure:event.e_salissure,
            e_panier:event.e_panier
            //e_date_saisie_salissure:event.e_date_saisie_salissure
        },
        method   : "POST",
        dataType : "text",
        async    : true,
        error    : function(request, error) { alert("Erreur : responseText: "+request.responseText);},
        success  : function(data) {
            //reload() calendar;
            console.log(data);
            }
    });
}

function save_update_event_resized (event) {
    $.ajax({
        url      : "php/ajax/analytique/event/update_event_resized.js.php",
        data     : {
            e_uuid:event.e_uuid,
            e_start:event.e_start.toString().split(' GMT')[0],
            e_end:event.e_end.toString().split(' GMT')[0]
        },
        method   : "POST",
        dataType : "text",
        async    : true,
        error    : function(request, error) { alert("Erreur : responseText: "+request.responseText);},
        success  : function(data) {
            //reload() calendar;
            console.log(data);
            }
    });
}

function delete_event (uuid_) {
    $.ajax({
        url      : "php/ajax/analytique/event/delete_event.js.php",
        data     : {
            e_uuid:uuid_
        },
        method   : "POST",
        dataType : "text",
        async    : true,
        error    : function(request, error) { alert("Erreur : responseText: "+request.responseText);},
        success  : function(data) {
            //reload() calendar;
            console.log(data);
            }
    });
}



























































