let projets = '';//projets all (1er chargement)
let projets_f = '';//projets filtrés



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
            let str__ = '';

            $('#console').html('');
            $str__ = '<table>';
            $str__ += '<thead><tr><th>Personne</th><th>ID Projet</th><th>Nom Projet</th><th>Date</th><th>Janvier</th><th>Février</th><th>Mars</th><th>Avril</th><th>Mai</th><th>Juin</th><th>Juillet</th><th>Août</th><th>Septembre</th><th>Octobre</th><th>Novembre</th><th>Décembre</th><th>e_ids</th></tr></thead>';
            $str__ += '<tbody>';
            for (const temps_ in temps_liste) {
                //temps_liste_array.push(temps_liste[temps_].id+' - '+actions_liste[action].name);
                //DATATABLES
                let rowNode = dtTemps.row.add( [
                    temps_liste[temps_].personne, 
                    temps_liste[temps_].id_projet, 
                    temps_liste[temps_].nom_projet, 
                    temps_liste[temps_].date, 
                    temps_liste[temps_].janvier, 
                    temps_liste[temps_].fevrier, 
                    temps_liste[temps_].mars, 
                    temps_liste[temps_].avril, 
                    temps_liste[temps_].mai, 
                    temps_liste[temps_].juin, 
                    temps_liste[temps_].juillet, 
                    temps_liste[temps_].aout, 
                    temps_liste[temps_].septembre, 
                    temps_liste[temps_].octobre, 
                    temps_liste[temps_].novembre, 
                    temps_liste[temps_].decembre,
                    temps_liste[temps_].e_ids
                ] ).draw( true ).node();
                $str__ += '<tr>';
                $str__ += '<td>'+temps_liste[temps_].personne+'</td>';
                $str__ += '<td>'+temps_liste[temps_].id_projet+'</td>';
                $str__ += '<td>'+temps_liste[temps_].nom_projet+'</td>';
                $str__ += '<td>'+temps_liste[temps_].date+'</td>';
                $str__ += '<td>'+temps_liste[temps_].janvier+'</td>';
                $str__ += '<td>'+temps_liste[temps_].fevrier+'</td>';
                $str__ += '<td>'+temps_liste[temps_].mars+'</td>';
                $str__ += '<td>'+temps_liste[temps_].avril+'</td>';
                $str__ += '<td>'+temps_liste[temps_].mai+'</td>';
                $str__ += '<td>'+temps_liste[temps_].juin+'</td>';
                $str__ += '<td>'+temps_liste[temps_].juillet+'</td>';
                $str__ += '<td>'+temps_liste[temps_].aout+'</td>';
                $str__ += '<td>'+temps_liste[temps_].septembre+'</td>';
                $str__ += '<td>'+temps_liste[temps_].octobre+'</td>';
                $str__ += '<td>'+temps_liste[temps_].novembre+'</td>';
                $str__ += '<td>'+temps_liste[temps_].decembre+'</td>';
                $str__ += '<td>'+temps_liste[temps_].e_ids+'</td>';
                $str__ += '</tr>';
                //console.log( 'Row index: '+dtTemps.row(rowNode).index()+' data: '+dtTemps.row(rowNode).data() );
                //ids_blocked.push(temps_liste[temps_].e_ids);

                
            }
            //dtTemps.columns.adjust().draw();
            $str__ += '</tbody></table>';
            $('#console').text($str__);

            $.ajax({
                url      : "pdf.php",
                data     : {tableau : $str__, name : 'export_horodate.pdf'},
                method   : "POST",
                dataType : 'text',
                contentType : 'application/pdf',
                async    : false,
                error    : function(request, error) { alert("Erreur : responseText: "+request.responseText);},
                success  : function(data) {
                    console.log('success pdf');
                    }
            });

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


var ids_blocked = [];
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
                                text: ''
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

                var lines = doc.content[0].table.body;
                //lines.shift();
                lines.forEach((element) => 
                    //ids_blocked.push(element[0].text)
                    //bloc_event_ids(element[0].text)
                    console.log('10 : ' + element[10].text)
                );
                //bloc_event_ids(ids_blocked);
            }
    }
    ]
,paging: true
 });

dtTemps.column(1).visible(false);
dtTemps.column(16).visible(false); // Hide the column with index 16 (0-based index)


function bloc_event_ids(ids_blocked) {
    $.ajax({
        url      : "php/ajax/export/bloc_event_ids.ajax.js.php",
        data     : { 'id_to_bloc' : ids_blocked},
        method   : "POST",
        dataType : "json",
        async    : true,
        error    : function(request, error) { alert("Erreur : responseText: "+request.responseText);},
        success  : function(data) {
            console.log(data);
            }
    });
}











