let projets = '';//projets all (1er chargement)
let projets_f = '';//projets filtrés
function load_personnes_ajax () {
    $.ajax({
        url      : "php/ajax/projets/personnes_liste.ajax.js.php",
        data     : {},
        method   : "POST",
        dataType : "json",
        async    : true,
        error    : function(request, error) { alert("Erreur : responseText: "+request.responseText);},
        success  : function(data) {
            personnes_liste = data ;
            let personnes_liste_array = [];
            for (const personne in personnes_liste) {
                personnes_liste_array.push(personnes_liste[personne].id+' - '+personnes_liste[personne].name);
            }
            personnes_liste_array.sort();
            autocompleteArray_personnes(document.getElementById("input_personnes"), personnes_liste_array);
            }
    });
}
//export excel temps des projets
document.getElementById("export_p_2022").addEventListener("click", function() {
    export_(2022);
});
document.getElementById("export_p_2023").addEventListener("click", function() {
    export_(2023);
});
document.getElementById("export_p_2024").addEventListener("click", function() {
    export_(2024);
});
document.getElementById("export_p_2025").addEventListener("click", function() {
    export_(2025);
});

//export excel temps des projets GLOBAL
document.getElementById("export_2022").addEventListener("click", function() {
    export_global(2022);
});
document.getElementById("export_2023").addEventListener("click", function() {
    export_global(2023);
});
document.getElementById("export_2024").addEventListener("click", function() {
    export_global(2024);
});
document.getElementById("export_2025").addEventListener("click", function() {
    export_global(2025);
});

function export_(year) {
    if (document.getElementById("input_personnes").value !== '') {
        change_load('chargement des données veuillez patienter...');
        $.ajax({
            url: "php/export_excel/export_temps_personne_year.php",
            type: "POST",
            dataType: "text",
            async    : true,
            data: {
                'year': year,
                'nom_personne': document.getElementById("input_personnes").value.split(' - ')[1]
            },
            error    : function(request, error) { 
                alert("Erreur : responseText: "+request.responseText);
                change_load();
                },
            success  : function(data) {
                console.log(data);
                change_load();
                window.location = 'php/files/'+data;
                }
        });
    } else {
        alert('Aucune personne n\'est selectionnée');
    }
}
function export_global(year) {
    change_load('chargement des données veuillez patienter...');
    $.ajax({
        url: "php/export_excel/export_global.php",
        type: "POST",
        dataType: "text",
        async    : true,
        data: {
            'year': year
        },
        error    : function(request, error) { 
            alert("Erreur : responseText: "+request.responseText);
            change_load();
            },
        success  : function(data) {
            console.log(data);
            window.location = 'php/files/'+data;
            change_load();
            }
    });
}

function load_temps_ajax () {
    $.ajax({
        url      : "php/ajax/export/load_temps_from_projet.js.php",
        data     : { 'projet_param' : projets_f[0].id},
        method   : "POST",
        dataType : "json",
        async    : true,
        error    : function(request, error) { alert("Erreur : responseText: "+request.responseText);},
        success  : function(data) {
            temps_liste = data ;
            dtTemps.clear();
            //console.log(data);
            let temps_liste_array = [];
            for (const temps_ in temps_liste) {
                //temps_liste_array.push(temps_liste[temps_].id+' - '+actions_liste[action].name);
                //DATATABLES

                let rowNode = dtTemps.row.add( [
                    temps_liste[temps_].e_id, 
                    temps_liste[temps_].e_id_projet, 
                    temps_liste[temps_].e_nom_projet, 
                    temps_liste[temps_].e_id_action, 
                    temps_liste[temps_].e_nom_action, 
                    temps_liste[temps_].e_id_site, 
                    temps_liste[temps_].e_objet, 
                    temps_liste[temps_].e_start, 
                    temps_liste[temps_].e_end, 
                    //temps_liste[temps_].e_lieu,
                    //temps_liste[temps_].e_commentaire, 
                    temps_liste[temps_].e_personne, 
                    temps_liste[temps_].e_nb_h,
                    temps_liste[temps_].e_blocked 
                ] ).draw( true ).node();
            }
            //dtTemps.columns.adjust().draw();
            }
    });
}


function load_projets_ajax () {
    $.ajax({
        url      : "php/ajax/export/load_export_h_projet.ajax.js.php",
        data     : {},
        method   : "POST",
        dataType : "json",
        async    : true,
        error    : function(request, error) { alert("Erreur : responseText: "+request.responseText);},
        success  : function(data) {
            projets = data ;
            //console.log(`Projets ${projets}`);
            let projets_array = [];
            for (const projet in projets) {
                projets_array.push(projets[projet].id+' - '+projets[projet].name);
            }
            projets_array.sort();
            autocompleteArray(document.getElementById("input_projet"), projets_array);
            }
    });
}
//APPLY FILTERS ON CORRECT FIELD
function apply_filters() {
    projets_f = projets;
    for (const property in filters_active) {
        if(filters_active[property]) {
            projets_f = filtre_obj(projets_f, property);
            console.log(projets_f[0].id);
            load_temps_ajax();
        }
    }
    //si pas de filtre sur les projets clear all data
    if (!filters_active["id_projet"]) {
    }
};


var dtTemps =    $('#temps').DataTable({
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
dom: '<"top"<"d-flex justify-content-end align-items-center"fB>>t<"bottom"<"d-flex justify-content-between align-items-center"p>>',
buttons: [
    { 
        text: 'Export PDF',
        extend: 'pdfHtml5',
        filename: 'export_horodate',
        orientation: 'landscape', //portrait
        pageSize: 'A3', //A3 , A5 , A6 , legal , letter
        className: 'btn btn-danger mx-2',
        exportOptions: {
            columns: ':visible',
            search: 'applied',
            order: 'applied'
        },
        customize: function (doc) {
                //Remove the title created by datatTables
                doc.content.splice(0,1);
                //Create a date string that we use in the footer. Format is dd-mm-yyyy
                var now = new Date();
                var jsDate = now.getDate()+'-'+(now.getMonth()+1)+'-'+now.getFullYear()+' '+now.getHours()+':'+now.getMinutes()+':'+now.getSeconds();
                // Logo converted to base64
                doc.pageMargins = [20,60,20,30];
                doc.defaultStyle.fontSize = 7;
                doc.styles.tableHeader.fontSize = 7;
                doc['header']=(function() {
                    return {
                        columns: [
                            {
                                image: logo,
                                width: 24
                            },
                            {
                                alignment: 'left',
                                italics: true,
                                text: ['Export des temps créé le : ', { text: jsDate.toString() }],
                                fontSize: 14,
                                margin: [10,0]
                            },
                            {
                                alignment: 'right',
                                fontSize: 14,
                                text: 'Projet : '+projets_f[0].name
                            }
                        ],
                        margin: 20
                    }
                });
                doc['footer']=(function(page, pages) {
                    return {
                        columns: [
                            {
                                alignment: 'left',
                                text: ['Créé le : ', { text: jsDate.toString() }]
                            },
                            {
                                alignment: 'right',
                                text: ['page ', { text: page.toString() },	' sur ',	{ text: pages.toString() }]
                            }
                        ],
                        margin: 20
                    }
                });
                var objLayout = {};
                objLayout['hLineWidth'] = function(i) { return .5; };
                objLayout['vLineWidth'] = function(i) { return .5; };
                objLayout['hLineColor'] = function(i) { return '#aaa'; };
                objLayout['vLineColor'] = function(i) { return '#aaa'; };
                objLayout['paddingLeft'] = function(i) { return 4; };
                objLayout['paddingRight'] = function(i) { return 4; };
                doc.content[0].layout = objLayout;
            }
    }
    ]
,paging: true
 });
 dtTemps.column(11).visible(false); // Hide the column with index 11 (0-based index)










