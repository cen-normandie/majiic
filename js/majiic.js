
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
    $("#loader").html('<img style="" src="./img/spin.png" class="rotate_ mx-4"> '+txt_what);
};

