//INIT VAR
let sites = '';//sites all (1er chargement)
let sites_f = '';//sites filtrés
let parcelles = '';//parcelles all (1er chargement)
let parcelles_f = '';//parcelles filtrés
let updateTableauParcelle = 'false';
// const years_ = [1970,1971,1972,1973,1974,1975,1976,1977,1978,1979,1980,1981,1982,1983,1984,1985,1986,1987,1988,1989,1990,1991,1992,1993,1994,1995,1996,1997,1998,1999,2000,2001,2002,2003,2004,2005,2006,2007,2008,2009,2010,2011,2012,2013,2014,2015,2016,2017,2018,2019,2020,2021,2022,2023,2024,2025];

//EVENT ON SWITCH
$('#zh').change(function() {filters_active["zh"] = ( $(this).prop('checked') );apply_filters();});
$('#aesn').change(function() {filters_active["aesn"] = ( $(this).prop('checked') );apply_filters();});
$('#doc_de_gestion').change(function() {filters_active["ddg"] = ( $(this).prop('checked') );apply_filters();});
$('#ens').change(function() {filters_active["ens"] = ( $(this).prop('checked') );apply_filters();});
$('#acquisition').change(function() {filters_active["acquisition"] = ( $(this).prop('checked') );apply_filters();});
$('#convention').change(function() {filters_active["convention"] = ( $(this).prop('checked') );apply_filters();});
$('#mc').change(function() {filters_active["mc"] = ( $(this).prop('checked') );apply_filters();});
$('#calvados').change(function() {clear_dep_filter ();filters_active["calvados"] = ( $(this).prop('checked') );apply_filters();});
$('#eure').change(function() {clear_dep_filter ();filters_active["eure"] = ( $(this).prop('checked') );apply_filters();});
$('#manche').change(function() {clear_dep_filter ();filters_active["manche"] = ( $(this).prop('checked') );apply_filters();});
$('#orne').change(function() {clear_dep_filter ();filters_active["orne"] = ( $(this).prop('checked') );apply_filters();});
$('#seine-maritime').change(function() {clear_dep_filter ();filters_active["seine-maritime"] = ( $(this).prop('checked') );apply_filters();});
$('#all').change(function() {clear_dep_filter ();apply_filters();});

$('#updateTableauParcelle').click(function() {
    update_chiffres_parcelles(parcelles_f,'true');
});


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
    update_graphs(sites_f);
    update_chiffres_sites(sites_f);
    update_chiffres_parcelles(parcelles_f);
    //map.removeLayer( sites_geojson_feature );
    sites_geojson_feature.clearLayers();
    if ( !(Object.keys(sites_f).length === 0) ) {
        update_map(sites_f);
    }
};


function clear_dep_filter () {
    filters_active["calvados"]=false;
    filters_active["eure"]=false;
    filters_active["orne"]=false;
    filters_active["manche"]=false;
    filters_active["seine-maritime"]=false;
}

function update_map(sites_json) {
    //ClearLayer
    //map.removeLayer( sites_geojson_feature );
    $(sites_json).each(function(key, data) {
        //ajoute les geojson
        sites_geojson_feature.addData(data.geojson);
    });
    map.fitBounds(sites_geojson_feature.getBounds());
    
}


document.getElementById("export_geopackage").addEventListener("click", function() {
    //console.log(JSON.stringify(sites_geojson_feature.toGeoJSON()));
    $.ajax({
        url: "php/export_geo_sites.php",
        type: "POST",
        dataType: "text",
        async    : true,
        data: {
            'json':JSON.stringify(sites_geojson_feature.toGeoJSON())
        },
        error    : function(request, error) { 
            alert("Erreur : responseText: "+request.responseText);
            },
        success  : function(data) {
            //console.log('Done');
            //console.log(data);
            //console.log(data);
            //window.location = 'php/files/'+data;
            fetch('php/files/'+data)
                .then(resp => resp.blob())
                .then(blob => {
                    const url = window.URL.createObjectURL(blob);
                    const a = document.createElement('a');
                    a.style.display = 'none';
                    a.href = url;
                    // the filename you want
                    a.download = 'export.gpkg';
                    document.body.appendChild(a);
                    a.click();
                    window.URL.revokeObjectURL(url);
                });
            }
    });
})



function update_chiffres_parcelles (parcelles_json, updateTableauParcelle) {
    let nb_parcelles = 0;
    let parcelle_convention = 0;
    let parcelle_acquisition = 0;
    dtParcelles.clear();
    for (const parcelle in parcelles_json) {
        nb_parcelles++;
        parcelle_convention += ( (parcelles_json[parcelle].is_convention == 1) ? 1 : 0);
        parcelle_acquisition += ( (parcelles_json[parcelle].is_acquisition == 1) ? 1 : 0);

        if(updateTableauParcelle) {
                let splitted = parcelles_json[parcelle].id_unique.split('|');
                let id_site = splitted[0];
                let id_parcelle = splitted[1];
                let document_reference = splitted[2];
                parcelles_json[parcelle].document_reference = document_reference;

                let rowNode = dtParcelles.row.add( [
                    id_site,
                    parcelles_json[parcelle].nom_site,
                    id_parcelle,
                    document_reference,
                    parcelles_json[parcelle].surface,
                    parcelles_json[parcelle].is_acquisition,
                    parcelles_json[parcelle].is_convention
                ] ).draw( true ).node();
                $(rowNode).attr("id", parcelles_json[parcelle].id_unique);     
        }
    }
    updateTableauParcelle = 'false';
    dtParcelles.columns.adjust();
    $("#nb_parcelles").text(nb_parcelles);
    $("#parcelles_convention").text(parcelle_convention);
    $("#parcelles_acquisition").text(parcelle_acquisition);
};

function update_chiffres_sites (sites_json) {
    let sites_array = [];
    let nb_sites = 0;
    let surface = 0;
    let site_convention = 0;
    let site_acquisition = 0;
    let nb_sites_ddg =0;
    let nb_parc =0;
    dtSites.clear();
    for (const site in sites_json) {
        sites_array.push(sites_json[site].id+' - '+sites_json[site].name);
        nb_sites++;
        surface += sites_json[site].surface;
        site_convention += ( (sites_json[site].is_convention == 1) ? 1 : 0);
        site_acquisition += ( (sites_json[site].is_acquisition == 1) ? 1 : 0);
        nb_parc += sites_json[site].nb_parc;
        nb_sites_ddg += ( (sites_json[site].is_ddg == 1) ? 1 : 0);
        //Update Datatable
        //$(data.parcelles).each(function(key, values) {
        //var dep             = values.properties.dep; 
        
        
        //DATATABLES
        let rowNode = dtSites.row.add( [
            sites_json[site].name,
            sites_json[site].surface,
            sites_json[site].bassin,
            sites_json[site].is_zh,
            sites_json[site].ens,
            sites_json[site].dep,
            sites_json[site].is_acquisition,
            sites_json[site].is_convention,
            sites_json[site].ucg,
            code_milieu_string(sites_json[site].code_milieu_princ),
            sites_json[site].is_ddg,
            sites_json[site].statuts_protection,
            sites_json[site].nb_parc
        ] ).draw( true ).node();
        $(rowNode).attr("id", sites_json[site].id_site);
        dtSites.columns.adjust();
    }
    sites_array.sort();
    autocompleteArray(document.getElementById("input_site"), sites_array);
    $("#nb_sites").text(nb_sites);
    $("#surface").text(Math.round(surface));
    $("#sites_convention").text(site_convention);
    $("#sites_acquisition").text(site_acquisition);
    gNbDDG.series[1].setData([nb_sites],false,false);
    gNbDDG.series[0].setData([nb_sites_ddg],false,false);
    gNbDDG.redraw();
};


function code_milieu_string(code) {
    let ended ='';
    switch (code) {
        case '0': ended ='inconnu';break;
        case '1': ended ='tourbières et marais';break;
        case '10': ended ='milieux variés';break;
        case '11': ended ='milieux rupestres ou rocheux';break;
        case '12': ended ='milieux artificialisés (carrières, terrils, gravières…)';break;
        case '13': ended ='sites géologiques';break;
        case '14': ended ='écosystèmes montagnards';break;
        case '16': ended ='autres';break;
        case '2': ended ='pelouses sèches';break;
        case '3': ended ='landes, fruticées et prairies';break;
        case '4': ended ='écosystèmes alluviaux';break;
        case '5': ended ='gîtes à chiroptères';break;
        case '6': ended ='écosystèmes littoraux et marins';break;
        case '7': ended ='écosystèmes aquatiques';break;
        case '8': ended ='écosystèmes forestiers';break;
        case '9': ended ='écosystèmes lacustres';break;
        default: ended ='inconnu';
          console.log(`Not Found --> la valeur n'est pas reconnue ${code}.`);
      }
      return ended;
}
/* Typologie des sites CENs
    0	inconnu
    1	tourbières et marais
    10	milieux variés
    11	milieux rupestres ou rocheux
    12	milieux artificialisés (carrières, terrils, gravières…)
    13	sites géologiques
    14	écosystèmes montagnards
    16	autres
    2	pelouses sèches
    3	landes, fruticées et prairies
    4	écosystèmes alluviaux
    5	gîtes à chiroptères
    6	écosystèmes littoraux et marins
    7	écosystèmes aquatiques
    8	écosystèmes forestiers
    9	écosystèmes lacustres
    */
function update_graphs (sites_json) {
    let array_milieu = [];
    let array_nb_sites_dep = [];
    let d14s = d27s = d50s = d61s = d76s = 0;
    //CODE MILIEU PRINCIPAL
    for (const site in sites_json) {
        array_milieu.push(sites_json[site].code_milieu_princ);
        array_nb_sites_dep.push(sites_json[site].dep);
        switch (sites_json[site].dep) {
            case '14':
                d14s += sites_json[site].surface;
                break;
            case '27':
                d27s += sites_json[site].surface;
                break;
            case '50':
                d50s += sites_json[site].surface;
                break;
            case '61':
                d61s += sites_json[site].surface;
                break;
            case '76':
                d76s += sites_json[site].surface;
                break;
            default:
              console.log(`Département non-défini pour le site ${sites_json[site].id}.`);
          }
    }
    // graph_typologie_site("graph_typologie_site", 
    // [
    //     array_milieu.filter(element => element == "0").length,
    //     array_milieu.filter(element => element == "1").length,
    //     array_milieu.filter(element => element == "10").length,
    //     array_milieu.filter(element => element == "11").length,
    //     array_milieu.filter(element => element == "12").length,
    //     array_milieu.filter(element => element == "13").length,
    //     array_milieu.filter(element => element == "14").length,
    //     array_milieu.filter(element => element == "16").length,
    //     array_milieu.filter(element => element == "2").length,
    //     array_milieu.filter(element => element == "3").length,
    //     array_milieu.filter(element => element == "4").length,
    //     array_milieu.filter(element => element == "5").length,
    //     array_milieu.filter(element => element == "6").length,
    //     array_milieu.filter(element => element == "7").length,
    //     array_milieu.filter(element => element == "8").length,
    //     array_milieu.filter(element => element == "9").length
    // ]
    // );
    // //NB SITE DEPARTEMENT ET 
    // graph_nbSite_Dep("nbSite_Dep",
    // [
    //     array_nb_sites_dep.filter(element => element == "14").length,
    //     array_nb_sites_dep.filter(element => element == "27").length,
    //     array_nb_sites_dep.filter(element => element == "50").length,
    //     array_nb_sites_dep.filter(element => element == "61").length,
    //     array_nb_sites_dep.filter(element => element == "76").length
    // ]
    // );
    // graph_surface_Dep("surface_Dep",array_surf_dep);

    gNbsiteDep.series[0].setData([array_nb_sites_dep.filter(element => element == "14").length],false,false);
    gNbsiteDep.series[1].setData([array_nb_sites_dep.filter(element => element == "27").length],false,false);
    gNbsiteDep.series[2].setData([array_nb_sites_dep.filter(element => element == "50").length],false,false);
    gNbsiteDep.series[3].setData([array_nb_sites_dep.filter(element => element == "61").length],false,false);
    gNbsiteDep.series[4].setData([array_nb_sites_dep.filter(element => element == "76").length],false,false);
    gSurfaceDep.series[0].setData([Math.round(d14s)],false,false);
    gSurfaceDep.series[1].setData([Math.round(d27s)],false,false);
    gSurfaceDep.series[2].setData([Math.round(d50s)],false,false);
    gSurfaceDep.series[3].setData([Math.round(d61s)],false,false);
    gSurfaceDep.series[4].setData([Math.round(d76s)],false,false);
    gTypoSite.series[0].setData([
        array_milieu.filter(element => element == "0").length,
        array_milieu.filter(element => element == "1").length,
        array_milieu.filter(element => element == "10").length,
        array_milieu.filter(element => element == "11").length,
        array_milieu.filter(element => element == "12").length,
        array_milieu.filter(element => element == "13").length,
        array_milieu.filter(element => element == "14").length,
        array_milieu.filter(element => element == "16").length,
        array_milieu.filter(element => element == "2").length,
        array_milieu.filter(element => element == "3").length,
        array_milieu.filter(element => element == "4").length,
        array_milieu.filter(element => element == "5").length,
        array_milieu.filter(element => element == "6").length,
        array_milieu.filter(element => element == "7").length,
        array_milieu.filter(element => element == "8").length,
        array_milieu.filter(element => element == "9").length
    ],false);
    gNbsiteDep.redraw();
    gSurfaceDep.redraw();
    gTypoSite.series[0].redraw();

};





//init datatable sites
var dtSites =    $('#sites').DataTable({
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
dom: 't<"bottom"<"d-flex justify-content-between align-items-center"fpB>>',
buttons: [
    { 
    extend: 'excel', 
    text:'Excel',
    className: 'btn btn-success my-2'
    }
    ]
,paging: true
 });




//init datatable parcelles
var dtParcelles =    $('#parcelles').DataTable({
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
dom: 't<"bottom"<"d-flex justify-content-between align-items-center"fpB>>',
buttons: [
    { 
    extend: 'excel', 
    text:'Excel',
    className: 'btn btn-success my-2'
    }
    ],
paging: true
 });





//CALL DATA SITES
function load_sites_ajax () {
    $.ajax({
        url      : "php/ajax/dashboard/sites_.ajax.js.php",
        data     : {},
        method   : "POST",
        dataType : "json",
        async    : true,
        error    : function(request, error) { alert("Erreur : responseText: "+request.responseText);},
        success  : function(data) {
            sites = data ;
            dtSites.clear();
            dtSites.columns.adjust().draw();
            //INIT
            load_parcelles_ajax();
            }
    });
}
function load_parcelles_ajax () {
    $.ajax({
        url      : "php/ajax/dashboard/parcelles.ajax.js.php",
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


//Datepicker
// $('#testpicker').datepicker({
// 	format: 'dd-mm-yyyy',
// 	language: "fr"
// });


