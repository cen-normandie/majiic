var docref_actif_ ='';
var p_id_site_ ='';
var p_ddg_ ='';
var arr_doc_ref = new Array();
var list_doc_ref = new Array();
var directory_ = "majiic";
var i=0;






// function display_docs() {
//     var str="<p></p>";
//     if (arr_acqu.length > 0 ) {
//         str +="<p>Actes Notariés : </p>";
//         arr_acqu.forEach(element => str+="<a class='p-2 border border-danger rounded link-danger' href ='/php/docs/foncier/"+element+".pdf' target='_blank' ><i class='fas fa-file-pdf text-danger py-2'></i>"+element+"</a> ");
//     };
//     if (arr_conv.length > 0 ) {
//         str +="<p>Conventions : </p>";
//         arr_conv.forEach(element => str+="<a class='p-2 border border-danger rounded link-danger' href ='/php/docs/foncier/"+element+".pdf' target='_blank'><i class='fas fa-file-pdf text-danger py-2'></i>"+element+"</a> ");
//     };
//     if (arr_ore.length > 0 ) {
//         str +="<p>ORE : </p>";
//         arr_ore.forEach(element => str+="<a class='p-2 border border-danger rounded link-danger' href ='/php/docs/foncier/"+element+".pdf' target='_blank'><i class='fas fa-file-pdf text-danger py-2'></i>"+element+"</a> ");
//     };
//     if (arr_ddg.length > 0 ) {
//         str +="<p>DDG : </p>";
//         arr_ddg.forEach(element => str+="<a class='p-2 border border-danger rounded link-danger' href ='/php/docs/gestion/"+element+".pdf' target='_blank'><i class='fas fa-file-pdf text-danger py-2'></i>"+element+"</a> ");
//     };
//     $('#list_docs').html(str);
// }




/////////////////////////////////////////////////////////////////
// DECLARATION DE L'ELEMENT DATATABLE POUR AFFICHER LES PARCELLES
let dtParcelles = $('#parcelles_dt').DataTable({
  "language": {
    "paginate": {
      "previous": "Préc.",
      "next": "Suiv."
    },
    "search": "Filtrer :",
    "sLengthMenu":     "Afficher _MENU_ &eacute;l&eacute;ments",
    "sInfo":           "Affichage de l'&eacute;l&eacute;ment _START_ &agrave; _END_ sur _TOTAL_ &eacute;l&eacute;ments",
    "sInfoEmpty":      "Affichage de l'&eacute;l&eacute;ment 0 &agrave; 0 sur 0 &eacute;l&eacute;ment",
    "sInfoFiltered":   "(filtr&eacute; de _MAX_ &eacute;l&eacute;ments au total)",
    "sInfoPostFix":    "",
    "sLoadingRecords": "Chargement en cours...",
    "sZeroRecords":    "Aucun &eacute;l&eacute;ment &agrave; afficher",
    "sEmptyTable":     "Aucune donn&eacute;e disponible dans le tableau"
  },
  //,"bPaginate": false
  
  //buttons: [
  //          'copy', 'excel', 'pdf', 'csv'
  //      ]
  dom: 't<"bottom"<"d-flex justify-content-between align-items-center"fpB>>',
  buttons: [
    { 
    extend: 'excel', 
    text:'Excel',
    className: 'btn btn-success m-2',
    init: function(api, node, config) {
       $(node).removeClass('dt-button')
    }
    }
    ]
});
$('#parcelles_dt tbody').on( 'click', 'tr', function () {
        if ( $(this).hasClass('selected') ) {
            $(this).removeClass('selected');
        }
        else {
            $('tr.selected').removeClass('selected');
            $(this).addClass('selected');
            //map.setView(mare_array_stamp_id[$(this).attr('id')]._latlng,17,'animate');
            console.log($(this).attr("id"));
            map.fitBounds(layer_in_geojson[$(this).attr("id")]);
            //sites_parcelles_geojson_feature
        }
});


// ##########################################################################################################
//INIT VAR
let sites = '';//sites all (1er chargement)
let sites_f = '';//sites filtrés
let parcelles = '';//parcelles all (1er chargement)
let parcelles_f = '';//parcelles filtrés
let updateTableauParcelle = 'false';

function init_sites_array() {
    let sites_array = [];
    for (const site in sites) {
        sites_array.push(sites[site].id+' - '+sites[site].name);
    }
    autocompleteArray(document.getElementById("input_site"), sites_array);
}


//APPLY FILTERS ON CORRECT FIELD
function apply_filters() {
    sites_f = sites;
    parcelles_f = parcelles;
    for (const property in filters_active) {
        if(filters_active[property]) {
            //console.log(`${property}: ${filters_active[property]}`);
            sites_f = filtre_obj(sites_f, property);
            parcelles_f = filtre_obj(parcelles_f, property);
        }
    }

    update_chiffres_parcelles(parcelles_f);
    if ( !(Object.keys(sites_f).length === 0) ) {
        update_map(sites_f, "sites");
    }
};
function update_map(json_, layers_) {
    //ClearLayer
    //map.removeLayer( sites_geojson_feature );
    if (layers_ == "sites") {
        sites_geojson_feature.clearLayers();
        console.log(json_);
        $(json_).each(function(key, data) {
            //ajoute les geojson
            sites_geojson_feature.addData(data.geojson);
            
        });
        map.fitBounds(sites_geojson_feature.getBounds());
    } else if (layers_ == "parcelles") {
        sites_parcelles_geojson_feature.clearLayers();
        $(json_).each(function(key, data) {
            //ajoute les geojson
            sites_parcelles_geojson_feature.addData(data.geojson);
            
        });
    }
}

$('#updateTableauParcelle').click(function() {
    update_dtParcelle();
});

function update_dtParcelle () {
    dtParcelles.clear();
    for (const parcelle in parcelles_f) {
        let splitted = parcelles_f[parcelle].id_unique.split('|');
        let id_site = splitted[0];
        let id_parcelle = splitted[1];
        let insee = id_parcelle.substring(0, 5);
        let prefixe = id_parcelle.substring(5, 8);
        let section = id_parcelle.substring(8, 10);
        let numero = id_parcelle.substring(10);
        let rowNode = dtParcelles.row.add( [
            id_site,
            parcelles_f[parcelle].dep, //dep
            insee,
            prefixe,
            section,
            numero,
            parcelles_f[parcelle].doc_reference,
            parcelles_f[parcelle].is_convention,
            parcelles_f[parcelle].is_acquisition,
            parcelles_f[parcelle].is_ddg,
            parcelles_f[parcelle].is_ddg,
            parcelles_f[parcelle].surface
        ] ).draw();
    }            
}

function update_chiffres_parcelles (parcelles_json, updateTableauParcelle) {
    let nb_parcelles = 0;
    let sum_surf = 0;
    sites_f.surface;
    dtParcelles.clear();
    for (const parcelle in parcelles_json) {
                nb_parcelles++;
                sum_surf += parcelles_json[parcelle].surface;
    }
    dtParcelles.columns.adjust();
    $("#nb_parcelles").text(nb_parcelles);
    $("#sum_surface").text(Math.round(sum_surf));
    if ( !(Object.keys(parcelles_json).length === 0) ) {
        update_map(parcelles_json, "parcelles");
    }
};


// LOAD DATA 
function load_sites_ajax () {
    $.ajax({
        url      : "php/ajax/sites/sites.ajax.js.php",
        data     : {},
        method   : "POST",
        dataType : "json",
        async    : true,
        error    : function(request, error) { alert("Erreur : responseText: "+request.responseText);},
        success  : function(data) {
            sites = data ;
            //Load autocomplete
            init_sites_array();
            //INIT
            load_parcelles_ajax();
            }
    });
}
function load_parcelles_ajax () {
    $.ajax({
        url      : "php/ajax/sites/parcelles.ajax.js.php",
        data     : {},
        method   : "POST",
        dataType : "json",
        async    : true,
        error    : function(request, error) { alert("Erreur : responseText: "+request.responseText);},
        success  : function(data) {
            parcelles = data ;
            // for (const parcelle in parcelles) {
            //     //DATATABLES
            //     let splitted = parcelles[parcelle].id_unique.split('|');
            //     let id_site = splitted[0];
            //     let id_parcelle = splitted[1];
            //     let document_reference = splitted[2];
            //     parcelles[parcelle].document_reference = document_reference;

            //     let rowNode = dtParcelles.row.add( [
            //         id_site,
            //         parcelles[parcelle].nom_site,
            //         id_parcelle,
            //         document_reference,
            //         parcelles[parcelle].surface,
            //         parcelles[parcelle].is_acquisition,
            //         parcelles[parcelle].is_convention
            //     ] ).draw( true ).node();
            //     $(rowNode).attr("id", parcelles[parcelle].id_unique);
            // }
            dtParcelles.columns.adjust().draw();
            //INIT
            change_load ();
            apply_filters();
            }
    });
}
change_load("Chargement des données");
//chargement des sites
//cette fonction appelle ensuite load_parcelles_ajax
load_sites_ajax();

























