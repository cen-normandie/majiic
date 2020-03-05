

//function display_parcelles_in_area(id, table_name) {
//    $.ajax({
//        url      : "php/ajax/load_parcelles_in_area.js.php",
//        data     : {id: id, table_name: table_name},
//        method   : "POST",
//        dataType : "json",
//        async    : true,
//        error    : function(request, error) { alert("Erreur : responseText: "+request.responseText);},
//        success  : function(data) {
//            parcelles.clearLayers();
//                if (data.features != null) {
//                    $(data.features).each(function(key, value) {
//                        parcelles.addData(data.features[key]);
//                    });
//                }
//            //session storage
//            sessionStorage.setItem('parcelles', JSON.stringify(data.features));
//            change_load ();
//        }
//    });// End ajax
//}

//listener
x = {
  aInternal: 0,
  aListener: function(val) {},
  set t_nb_rows(val) {
    this.aInternal = val;
    this.aListener(val);
  },
  get t_nb_rows() {
    return this.aInternal;
  },
  registerListener: function(listener) {
    this.aListener = listener;
  }
};
x.registerListener(function(val) {
  if (val > 0) {
      $("#clear_table").removeClass("disabled");
  } else {
      $("#clear_table").addClass("disabled");
  };
});




x.t_nb_rows = 0;
$("#clear_table").click( function (){
    dt4.clear().draw();
    x.t_nb_rows = 0;
});


//-----------------------------------------------------------------
//AUTOCOMPLETE ADMIN
//-----------------------------------------------------------------
$( "#layers_autocomplete" ).autocomplete({
  source: function( request, response ) {
    $.ajax( {
      method   : "POST",
      url: "php/ajax/load_layers_admin.js.php",
      dataType : "json",
      data: {
        term: request.term
      },
      success: function( data ) {
        var JSON_values_completed = [];
        $.each(data.features, function (index, value) {
            
            //console.log(data.features[0].properties.l_id );
            var id = data.features[index].properties.l_id;
            var nom = data.features[index].properties.l_nom;
            var table_name_ = data.features[index].properties.l_table_name;
             JSON_values_completed.push({
                label: id+ ' - '+nom,
                value: id+ ' - '+nom,
                feature: data.features[index],
                table_name: table_name_
            });
        })
        response(JSON_values_completed);
      }
    } );
  },
  minLength : 3,
  select: function AutoCompleteSelectHandler(event, ui)
    {
        change_load ('Chargement des parcelles');
        var id_selected = ui.item.value.split(' - ')[0];
        admin_geojson_feature.clearLayers();
        admin_geojson_feature.addData(ui.item.feature);
        map.fitBounds(admin_geojson_feature.getBounds());
        display_parcelles_in_area(id_selected, ui.item.table_name );
    }
    
});
function display_parcelles_in_area(id, table_name) {
    var array_mares=[];
    $.ajax({
        url      : "php/ajax/load_parcelles_in_area.js.php",
        data     : {id: id, table_name: table_name},
        method   : "POST",
        dataType : "json",
        async    : true,
        error    : function(request, error) { alert("Erreur : responseText: "+request.responseText);},
        success  : function(data) {
            parcelles.clearLayers();
                if (data.features != null) {
                    $(data.features).each(function(key, value) {
                        parcelles.addData(data.features[key]);
                       
                            //var id_ = data.features[key].properties.loc_id_plus;
                            //var statut_ = data.features[key].properties.loc_statut;
                    });
                }
            change_load ();
        }
    });// End ajax
}



function change_load (txt_what) {
    $("#loader").toggleClass("visible_s");
    $("#loader").html('<i class="fa fa-refresh fa-spin"></i> '+txt_what);
};
















